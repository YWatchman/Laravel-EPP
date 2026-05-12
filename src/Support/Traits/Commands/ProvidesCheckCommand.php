<?php

namespace YWatchman\LaravelEPP\Support\Traits\Commands;

use DOMAttr;
use DOMElement;
use DOMException;

trait ProvidesCheckCommand
{
    /**
     * Generate check node.
     *
     * @throws DOMException
     */
    private function generateCheck(string $type): DOMElement
    {
        /** @var DOMElement $node */
        $node = $this->createElement(self::NODE);
        $node->setAttributeNodeNS(new DOMAttr(sprintf('xmlns:%s', self::NODE_BASE), self::NAMESPACE));

        foreach ($this->iterable as $iterable) {
            [$key, $value] = $this->checkKey($type);
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
     *
     * @return string[]
     */
    private function checkKey(string $type): array
    {
        // format: ['external_key', 'local_key']
        return match ($type) {
            'domain', 'host' => ['name', 'name'],
            default => ['id', 'handle'],
        };
    }
}
