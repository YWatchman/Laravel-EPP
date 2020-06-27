<?php

namespace YWatchman\LaravelEPP\Support\Xml;

use DOMDocument;
use DOMElement;
use DOMNode;

class XmlHelper
{
    /** @var DOMDocument */
    protected $document;

    /** @var array List of all nodes */
    protected $nodes = [];

    /**
     * XmlHelper constructor.
     *
     * Setup XML Document
     */
    public function __construct()
    {
        $this->document = new DOMDocument('1.0', 'UTF-8');
        $this->document->preserveWhiteSpace = false;
        $this->document->xmlStandalone = false;
        if (function_exists('config')) {
            $this->document->formatOutput = config('epp.debug', false);
        }
    }

    /**
     * Add DOM node to current XML Document.
     *
     * @param string       $name
     * @param DOMNode|null $node if should be layered
     *
     * @return DOMNode
     */
    public function addNode(string $name, $node = null)
    {
        if ($node instanceof DOMNode) {
            $node = $this->createElement($node->nodeName);
        } else {
            $node = $this->createElement($name);
        }

        return $this->nodes[] = $node;
    }

    /**
     * Alias for DOMDocument::createElement().
     *
     * @param $name
     * @param null $value
     *
     * @return DOMElement
     */
    public function createElement($name, $value = null): DOMElement
    {
        return $this->document->createElement($name, $value);
    }
}
