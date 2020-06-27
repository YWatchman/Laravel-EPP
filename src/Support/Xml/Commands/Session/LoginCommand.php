<?php

namespace YWatchman\LaravelEPP\Support\Xml\Commands\Session;

use DOMElement;
use YWatchman\LaravelEPP\Support\Xml\Commands\Command;

class LoginCommand extends Command
{
    /** @var DOMElement */
    protected $node;

    /**
     * LoginCommand constructor.
     *
     * @param string $username EPP Username
     * @param string $password EPP Password
     */
    public function __construct(string $username, string $password)
    {
        parent::__construct();

        $this->node = $this->createElement('login');

        $node = $this->addNode($this->getCommandNode()->nodeName);

        $loginNode = $node->appendChild($this->node);
        $loginNode->appendChild($this->createElement('clID', $username));
        $loginNode->appendChild($this->createElement('pw', $password));

        $optionNode = $loginNode->appendChild($this->createElement('options'));
        $optionNode->appendChild($this->createElement('version', '1.0'));
        $optionNode->appendChild($this->createElement('lang', 'en'));

        $svcNode = $loginNode->appendChild($this->createElement('svcs'));
        $svcNode->appendChild($this->createElement('objURI', 'urn:ietf:params:xml:ns:contact-1.0'));
        $svcNode->appendChild($this->createElement('objURI', 'urn:ietf:params:xml:ns:host-1.0'));
        $svcNode->appendChild($this->createElement('objURI', 'urn:ietf:params:xml:ns:domain-1.0'));

        $svcExt = $svcNode->appendChild($this->createElement('svcExtension'));
        $svcExt->appendChild($this->createElement('extURI', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0'));
        $svcExt->appendChild($this->createElement('extURI', 'http://rxsd.domain-registry.nl/sidn-ext-epp-registry-contacts-delete-1.0'));
        $svcExt->appendChild($this->createElement('extURI', 'http://rxsd.domain-registry.nl/sidn-ext-epp-scheduled-delete-1.0'));
        $svcExt->appendChild($this->createElement('extURI', 'urn:ietf:params:xml:ns:secDNS-1.1'));
        $svcExt->appendChild($this->createElement('extURI', 'urn:ietf:params:xml:ns:keyrelay-1.0'));
    }
}
