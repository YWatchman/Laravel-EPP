<?php


namespace YWatchman\LaravelEPP\Support\Xml\Objects\Host;

use DOMElement;

class HostObject
{

    /**
     * Get array of DOM elements.
     *
     * @param array $nameservers
     * @return array
     */
    public static function getNameservers(array $nameservers)
    {
        return array_map(function ($item) {
            return static::getNameserver($item);
        }, $nameservers);
    }

    /**
     * Get DOM element for single nameserver.
     *
     * @param $nameserver
     * @return DOMElement
     */
    public static function getNameserver($nameserver)
    {
        return new DOMElement('domain:hostObj', $nameserver, 'urn:ietf:params:xml:ns:host-1.0');
    }
}
