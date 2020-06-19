<?php

namespace YWatchman\LaravelEPP\Support\Xml\Commands;

use DOMAttr;
use DOMElement;
use DOMNode;
use Illuminate\Support\Arr;
use YWatchman\LaravelEPP\Support\Xml\XmlHelper;

class Command extends XmlHelper
{
    public const NOT_USED = 'NOT_USED';

    /** @var DOMNode */
    protected $walknode;
    /**
     * @var DOMElement
     */
    private $commandNode;

    public function __construct()
    {
        parent::__construct();
        $this->commandNode = $this->createElement('command');

        // Todo: clTRID
    }

    /**
     * Return XML document.
     *
     * @return string
     */
    public function __toString()
    {
        $node = $this
            ->document
            ->createElement('epp');
        $node->setAttributeNodeNS(new DOMAttr('xmlns', 'urn:ietf:params:xml:ns:epp-1.0'));

        foreach ($this->nodes as $newNode) {
            $node->appendChild($newNode);
        }

        $this->document->appendChild($node);

        return $this->document->saveXML(null, LIBXML_NOEMPTYTAG);
    }

    /**
     * @param $cmd
     * @return string
     */
    public function getCommandTag($cmd): string
    {
        return sprintf('%s:%s', static::NODE_BASE, $cmd);
    }

    /**
     * Get command node element.
     *
     * @return DOMElement
     */
    protected function getCommandNode()
    {
        return $this->commandNode;
    }

    /**
     * @param $value
     * @param $key
     * @param null $node
     */
    protected function recurseCallback($value, $key, $node = null): void
    {
        foreach (Arr::wrap($value) as $k => $val) {
            if ($val === null) {
                continue;
            }
            if ($node !== null) {
                $newNode = $this
                    ->createElement($this->getCommandTag($key), $val);
            } else {
                $newNode = $this->createElement(
                    $this->getCommandTag($key),
                    $val
                );
            }

            if (isset($attrs)) {
                foreach ($attrs as $key => $attr) {
                    $newNode->setAttribute($key, $attr);
                }
            }

            $this->walknode->appendChild($newNode);
        }
    }

    /**
     * @param $child
     */
    protected function setAttributes($child): void
    {
        foreach ($child as $key => $item) {
            $this->walknode->setAttribute($key, $item);
        }
    }

    /**
     * @param array $tag
     * @param DOMNode $node
     */
    protected function recurseTags(array $tag, DOMNode $node): void
    {
        array_walk($tag, function ($value, $key) use ($node) {
            if (is_array($value)) {
                $vals = $this->createElement($this->getCommandTag($key));
                array_walk($value, [self::class, 'recurseCallback'], $vals);
                return $value;
            }

            $this->walknode->appendChild(
                $this->createElement(
                    $this->getCommandTag($key),
                    $value
                )
            );
            return $value;
        });
    }

    /**
     * Generate empty extension node.
     *
     * @return DOMElement
     */
    protected function getExtensionNode()
    {
        return $this->createElement('extension');
    }
}
