<?php

namespace YWatchman\LaravelEPP\Support\Traits\Commands;

use DOMAttr;
use DOMElement;
use YWatchman\LaravelEPP\Support\ArrayHelper;

trait ProvidesContactCommand
{

    /**
     * Transform contact array into DOMElement.
     *
     * @param array $contact
     * @return DOMElement
     */
    public function handleContact(array $contact): DOMElement
    {
        $node = $this->createElement(self::NODE);
        $node->setAttributeNodeNS(
            new DOMAttr('xmlns:contact', self::NAMESPACE)
        );
        
        ArrayHelper::filterEmpty($contact);

        foreach ($contact as $tag => $child) {
            if (is_array($child)) {
                $this->walknode = $this->createElement($this->getCommandTag($tag));
                if (isset($child['attributes'])) {
                    $this->setAttributes($child['attributes']);
                    unset($child['attributes']);
                }
                $this->recurseTags($child, $this->walknode);
                $node->appendChild($this->walknode);
                continue;
            }

            // Just append string only tags
            $node->appendChild($this->createElement($this->getCommandTag($tag), $child));
        }

        return $node;
    }
}
