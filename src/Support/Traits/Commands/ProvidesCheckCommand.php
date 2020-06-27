<?php

namespace YWatchman\LaravelEPP\Support\Traits\Commands;

use DOMAttr;
use DOMElement;

trait ProvidesCheckCommand
{
    /**
     * Generate check node.
     *
     * @param string $type
     *
     * @return DOMElement
     */
    private function generateCheck(string $type): DOMElement
    {
        /** @var DOMElement $node */
        $node = $this->createElement(self::NODE);
        $node->setAttributeNodeNS(new DOMAttr(sprintf('xmlns:%s', self::NODE_BASE), self::NAMESPACE));
        $node->setAttributeNodeNS(
            new DOMAttr('xmlns:'.self::NODE_BASE, self::NAMESPACE)
        );

        foreach ($this->iterable as $iterable) {
            list($key, $value) = $this->checkKey($type);
            $node->appendChild(
                $this->createElement(
                    self::NODE_BASE.':'.$key,
                    $iterable->{$value}
                )
            );
        }

        return $node;
    }

    /**
     * Get key for generateCheck(string).
     *
     * @param string $type
     *
     * @return string[]
     */
    private function checkKey(string $type): array
    {
        // format: ['external_key', 'local_key']
        switch ($type) {
            case 'domain':
                return ['name', 'domainname'];
            case 'host':
                return ['name', 'name'];
            default:
                return ['id', 'external_identifier'];
        }
    }
}
