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
     *
     * @return DOMElement
     */
    public function handleContact(array $contact): DOMElement
    {
        $node = $this->createElement(self::NODE);
        $node->setAttributeNodeNS(
            new DOMAttr('xmlns:contact', self::NAMESPACE)
        );

        // We don't need empty keys.
        ArrayHelper::filterEmpty($contact);

        foreach ($contact as $tag => $child) {
            if (is_array($child)) {
                $tempnode = $this->createElement($this->getCommandTag($tag));
                $this->recurse($child, $tempnode);
            } else {
                $tempnode = $this->createElement(
                    $this->getCommandTag($tag),
                    $child
                );
            }

            $node->appendChild($tempnode);
        }

        return $node;
    }

    /**
     * Recurse tags for child.
     *
     * @param array      $childTags
     * @param DOMElement $node
     */
    private function recurse(array &$childTags, DOMElement $node): void
    {
        if (isset($childTags['attributes'])) {
            // Loop over attributes and add them to the element.
            foreach ($childTags['attributes'] as $attribute => $value) {
                $node->setAttribute($attribute, $value);
            }
            unset($childTags['attributes']);
        }

        // This function is created to do recursion...
        foreach ($childTags as $tag => $child) {
            if (is_array($child)) {
                $tempnode = $this->createElement(
                    $this->getCommandTag($tag)
                );
                foreach ($child as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $v) {
                            if (empty($v)) {
                                continue;
                            }
                            $tempnode->appendChild(
                                $this->createElement(
                                    $this->getCommandTag($key),
                                    $v
                                )
                            );
                        }
                    } else {
                        $tempnode->appendChild(
                            $this->createElement(
                                $this->getCommandTag($key),
                                $value
                            )
                        );
                    }
                }

                $node->appendChild($tempnode);
            } else {
                $node->appendChild(
                    $this->createElement(
                        $this->getCommandTag($tag),
                        $child
                    )
                );
            }
        }
    }
}
