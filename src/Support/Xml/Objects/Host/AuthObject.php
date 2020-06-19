<?php


namespace YWatchman\LaravelEPP\Support\Xml\Objects\Host;

use DOMDocument;
use DOMElement;
use YWatchman\LaravelEPP\Exceptions\EppException;
use YWatchman\LaravelEPP\Support\Xml\Commands\Command;

class AuthObject
{
    public const AUTH_DOMAIN = 'domain:authInfo';
    public const AUTH_CONTACT = 'contact:authInfo';
    public const AUTH_TYPES = [
        self::AUTH_DOMAIN,
        self::AUTH_CONTACT,
    ];

    public const PW_DOMAIN = 'domain:pw';
    public const PW_CONTACT = 'contact:pw';
    public const PW_TYPES = [
        self::PW_DOMAIN,
        self::PW_CONTACT,
    ];

    public const DOMAIN_NAMESPACE = 'urn:ietf:params:xml:ns:domain-1.0';
    public const CONTACT_NAMESPACE = 'urn:ietf:params:xml:ns:contact-1.0';
    public const NAMESPACES = [
        self::DOMAIN_NAMESPACE,
        self::CONTACT_NAMESPACE,
    ];

    public static function getAuthInfo(string $type, string $value = Command::NOT_USED) // Todo: move constant to global class
    {
        if (!in_array($type, self::AUTH_TYPES)) {
            throw EppException::authTypeDoesNotExist($type);
        }

        if (!in_array($type, self::NAMESPACES)) {
//            throw EppException::namespac($type); namespace err
        }

        switch ($type) {
            case self::AUTH_DOMAIN:
                $namespace = self::DOMAIN_NAMESPACE;
                $pwNode = new DOMElement(self::PW_DOMAIN, $value, $namespace);
                break;
            case self::AUTH_CONTACT:
                $namespace = self::CONTACT_NAMESPACE;
                $pwNode = new DOMElement(self::PW_CONTACT, $value, $namespace);
                break;
            default:
                throw EppException::notImplemented();
        }


        $document = new DOMDocument();
        $authObject = $document
            ->createElement($type);
        $authObject->appendChild($pwNode);
    }
}
