<?php

namespace YWatchman\LaravelEPP\Support\Xml\Commands\Host;

use DOMAttr;
use DOMElement;
use YWatchman\LaravelEPP\Exceptions\EppException;
use YWatchman\LaravelEPP\Models\Nameserver;
use YWatchman\LaravelEPP\Support\Xml\Commands\Command;

class CreateCommand extends Command
{
    public const NODE_BASE = 'host';
    public const NODE = 'host:create';
    public const NAMESPACE = 'urn:ietf:params:xml:ns:host-1.0';

    /**
     * @var DOMElement
     */
    protected $node;

    /**
     * @var Nameserver
     */
    protected $nameserver;

    /**
     * CreateCommand constructor.
     *
     * @param Nameserver
     *
     * @throws EppException
     */
    public function __construct(Nameserver $nameserver)
    {
        parent::__construct();

        $this->nameserver = $nameserver;

        $n = $this->addNode($this->getCommandNode()->nodeName);
        $n
            ->appendChild($this->getCreateNode());
    }

    /**
     * @return DOMElement
     */
    protected function getCreateNode(): DOMElement
    {
        $node = $this->createElement('create');

        $node->appendChild($this->handleNamserver($this->nameserver));

        return $node;
    }

    private function handleNamserver(Nameserver $nameserver): DOMElement
    {
        $node = $this->createElement(self::NODE);
        $node->setAttributeNodeNS(new DOMAttr(sprintf('xmlns:%s', self::NODE_BASE), self::NAMESPACE));
        $node->appendChild(
            $this->createElement('host:name', $nameserver->getName())
        );

        $addresses = $nameserver->getAddresses();
        foreach ($addresses as $address) {
            $address = explode('-', $address, 2);
            if (!empty($address[0])) {
                $ipNode = $this->createElement('host:addr', $address[0]);

                if (count($address) > 1) {
                    $ipNode->setAttribute('ip', $address[1]);
                }

                $node->appendChild(
                    $ipNode
                );
            }
        }

        return $node;
    }
}
