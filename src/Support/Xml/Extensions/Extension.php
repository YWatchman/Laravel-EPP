<?php

namespace YWatchman\LaravelEPP\Support\Xml\Extensions;

use DOMElement;
use YWatchman\LaravelEPP\Exceptions\EppException;
use YWatchman\LaravelEPP\Support\Xml\Extensions\Sidn\ContactExtension as SidnContactExtension;
use YWatchman\LaravelEPP\Support\Xml\Objects\Registrar;
use YWatchman\LaravelEPP\Support\Xml\XmlHelper;

abstract class Extension
{

    /** @var string $prefix Extension node prefix */
    protected $prefix = null;

    /**
     * @var XmlHelper
     */
    protected $helper;

    /**
     * @var DOMElement
     */
    protected $extension;

    /**
     * Extension constructor.
     *
     * @throws EppException
     */
    public function __construct()
    {
        if ($this->prefix === null) {
            throw new EppException('Extension prefix cannot be null.');
        }
        $this->helper = new XmlHelper;
        $this->extension = $this->helper->createElement($this->prefix . ':ext');
    }

    /**
     * Return new ContactExtension instance.
     *
     * @param string $registrar
     * @return string
     * @throws EppException
     */
    public static function contactInstance($registrar = Registrar::REGISTRAR_SIDN): string
    {
        switch ($registrar) {
            case Registrar::REGISTRAR_SIDN:
                $extension = SidnContactExtension::class;
                break;
            default:
                throw new EppException('Invalid registrar, not implemented.');
        }

        return $extension;
    }

    /**
     * Retrieve extension.
     *
     * @return DOMElement
     */
    public function getExtension(): DOMElement
    {
        return $this->extension;
    }

    /**
     * Get prefixed name.
     *
     * @param string $name
     * @return string
     */
    public function prefixedName(string $name): string
    {
        return $this->prefix . ':' . $name;
    }
}
