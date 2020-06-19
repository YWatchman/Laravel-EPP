<?php

namespace YWatchman\LaravelEPP\Support\Xml\Commands\Domain;

use DOMAttr;
use DOMElement;
use YWatchman\LaravelEPP\Models\Contact;
use YWatchman\LaravelEPP\Models\Domain;
use YWatchman\LaravelEPP\Support\Traits\Commands\HasDnssec;
use YWatchman\LaravelEPP\Support\Xml\Commands\Command;

class CreateCommand extends Command
{
    use HasDnssec;
    
    public const NODE = 'domain:create';
    public const NAMESPACE = 'urn:ietf:params:xml:ns:domain-1.0';

    /**
     * @var DOMElement $node
     */
    protected $node;

    /**
     * @var Domain $domain
     */
    protected $domain;

    /**
     * @var Contact $admin
     */
    protected $admin;

    /**
     * @var Contact $tech
     */
    protected $tech;

    /**
     * @var Contact $registrant
     */
    protected $registrant;

    /**
     * @var array $nameservers
     */
    protected $nameservers;

    /**
     * EPP Extensions to be used.
     *
     * @var array $extensions
     */
    protected $extensions = [];
    
    /**
     * @var string|null
     */
    protected $transactionId;

    /**
     * CreateCommand constructor.
     * @param Domain $domain
     * @param Contact $admin admin-c handle
     * @param Contact $tech tech-c handle
     * @param Contact $registrant registrant handle
     * @param array $nameservers
     * @param array $extensions
     * @param string|null $transactionId
     */
    public function __construct(
        Domain $domain,
        Contact $admin,
        Contact $tech,
        Contact $registrant,
        array $nameservers,
        array $extensions = [],
        string $transactionId = null
    ) {
        parent::__construct();

        $this->domain = $domain;
        $this->admin = $admin;
        $this->tech = $tech;
        $this->registrant = $registrant;
        $this->nameservers = $nameservers;
        $this->extensions = $extensions;
        $this->transactionId = $transactionId;

        $this->node = $this->createElement('create');
    }

    /**
     * Return XML document.
     *
     * @return string
     */
    public function __toString()
    {
        $n = $this->addNode($this->getCommandNode()->nodeName);
        $n->appendChild($this->node)->appendChild($this->getCreateNode());
        if (sizeof($this->extensions) > 0) {
            $n->appendChild($this->getExtensionNode());
        }
        
        // Todo: move $n to $this->node or something and move this code below to the Command class.
        if ($this->transactionId !== null && is_string($this->transactionId)) {
            $n->appendChild($this->createElement('clTRID', $this->transactionId));
        }
        
        return parent::__toString();
    }

    /**
     * Fill command.
     *
     * @return DOMElement
     */
    protected function getCreateNode(): DOMElement
    {
        $node = $this->createElement(self::NODE);
        $namespace = new DOMAttr('xmlns:domain', self::NAMESPACE);
        $node->setAttributeNodeNS($namespace);

        $node->appendChild(
            $this->createElement(
                'domain:name',
                sprintf('%s.%s', $this->domain->sld, $this->domain->tld)
            )
        );

        $element = $this->createElement('domain:period', 1);
        $element->setAttribute('unit', 'y');
        $node->appendChild($element);

        $nsNode = $this->createElement('domain:ns');
        foreach ($this->nameservers as $nameserver) {
            $nsNode->appendChild($this->createElement('domain:hostObj', $nameserver));
        }
        $node->appendChild($nsNode);

        foreach ($this->getContactNodes() as $contactNode) {
            $node->appendChild($contactNode);
        }

        $authNode = $this->createElement('domain:authInfo');
        $authNode->appendChild($this->createElement('domain:pw', self::NOT_USED));
        $node->appendChild($authNode);

        return $node;
    }

    /**
     * Get contact nodes.
     *
     * @return array
     */
    protected function getContactNodes(): array
    {
        $nodes = [];

        $nodes[] = $this->createElement(
            'domain:registrant',
            $this->registrant->handle
        );

        $node = $this->createElement(
            'domain:contact',
            $this->admin->handle
        );
        $node->setAttribute('type', 'admin');
        $nodes[] = $node;

        $node = $this->createElement(
            'domain:contact',
            $this->tech->handle
        );
        $node->setAttribute('type', 'tech');
        $nodes[] = $node;

        return $nodes;
    }

    /**
     * @return DOMElement
     */
    protected function getExtensionNode(): DOMElement
    {
        $extNode = $this->createElement('extension');
        $nodes = [];
        
        if (in_array('dnssec', $this->extensions) && $this->dnssec === true) {
            $nodes['secDNS'] = $this->createElement('secDNS:create');
            $nodes['secDNS']->setAttribute('xmlns:secDNS', 'urn:ietf:params:xml:ns:secDNS-1.1');
            $nodes['secDNS']->appendChild($this->dnssecNode());
        }
        
        foreach ($nodes as $node) {
            $extNode->appendChild($node);
        }
        
        return $extNode;
    }
}
