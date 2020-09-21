<?php

namespace YWatchman\LaravelEPP\Support\Xml\Commands\Domain;

use DOMAttr;
use DOMElement;
use YWatchman\LaravelEPP\Models\Domain;
use YWatchman\LaravelEPP\Support\Traits\Commands\HasDnssec;
use YWatchman\LaravelEPP\Support\Xml\Commands\Command;

class TransferCommand extends Command
{
    use HasDnssec;

    public const NODE_BASE = 'domain';
    public const NODE = 'domain:transfer';
    public const NAMESPACE = 'urn:ietf:params:xml:ns:domain-1.0';

    /**
     * @var DOMElement
     */
    protected $node;

    /**
     * @var Domain
     */
    protected $domain;

    /**
     * EPP Extensions to be used.
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * @var string|null
     */
    protected $transactionId;

    /**
     * @var string
     */
    private $token;

    /**
     * CreateCommand constructor.
     *
     * @param Domain $domain
     * @param string $token
     * @param array $extensions
     * @param string|null $transactionId
     */
    public function __construct(
        Domain $domain,
        string $token,
        array $extensions = [],
        string $transactionId = null
    ) {
        parent::__construct();

        $this->domain = $domain;
        $this->token = $token;
        $this->extensions = $extensions;
        $this->transactionId = $transactionId;

        $this->node = $this->createElement('transfer');
        $this->node->setAttributeNodeNS(
            new DOMAttr('op', 'request')
        );

        $n = $this->addNode(
            $this->getCommandNode()->nodeName
        );

        $n
            ->appendChild($this->node)
            ->appendChild($this->getCreateNode());
    }

    /**
     * Fill command.
     *
     * @return DOMElement
     */
    protected function getCreateNode(): DOMElement
    {
        $node = $this->createElement(self::NODE);
        $node->setAttributeNodeNS(
            new DOMAttr('xmlns:domain', self::NAMESPACE)
        );

        $node->appendChild(
            $this->createElement(
                $this->getCommandTag('name'),
                sprintf('%s.%s', $this->domain->sld, $this->domain->tld)
            )
        );

        $period = $this->createElement($this->getCommandTag('period'), 1);
        $period->setAttributeNodeNS(
            new DOMAttr('unit', 'y')
        );

        $node->appendChild($period);

        $auth = $this->createElement($this->getCommandTag('authInfo'));

        $auth->appendChild(
            $this->createElement(
                $this->getCommandTag('pw'),
                $this->token
            )
        );

        $node->appendChild($auth);

        return $node;
    }
}
