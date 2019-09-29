<?php

namespace YWatchman\LaravelEPP;

use Illuminate\Foundation\Application;
use YWatchman\LaravelEPP\Epp\Contact;
use YWatchman\LaravelEPP\Epp\Dnssec;
use YWatchman\LaravelEPP\Epp\Domain;
use YWatchman\LaravelEPP\Epp\Nameserver;

class Epp
{

    /** @var Application */
    protected $app;

    /**
     * Epp constructor.
     */
    public function __construct(){}

    public static function getDomainAvailability($domain)
    {
        return (new Domain)->getAvailability($domain);
    }

    public static function createDomain($name, $registrant, $admin, $tech, $nameservers, $billing = null)
    {
        return (new Domain)->createDomain($name, $registrant, $admin, $tech, $billing, $nameservers);
    }

    public static function deleteDomain($domain)
    {
        return (new Domain)->deleteDomain($domain);
    }

    public static function checkNameserver($nameserver)
    {
        return (new Nameserver)->checkNameservers($nameserver);
    }

    public static function createNameserver($nameservers)
    {
        return (new Nameserver)->createNameservers($nameservers);
    }

    public static function deleteNameserver($nameserver)
    {
        return (new Nameserver)->deleteNameserver($nameserver);
    }

    public static function checkContact($handle)
    {
        return (new Contact)->checkContact($handle);
    }

    public static function updateContact($handle, $name, $email, $phone, $city, $countryCode, $street, $province, $zip, $org = null)
    {
        return (new Contact)->updateContact($handle, $name, $email, $phone, $city, $countryCode, $street, $province, $zip, $org);
    }

    public static function createContact($name, $email, $phone, $city, $countryCode, $street, $province, $zip, $org = null, $registrar = 'sidn')
    {
        return (new Contact)->createContact($name, $email, $phone, $city, $countryCode, $street, $province, $zip, $org, $registrar);
    }

    public static function deleteContact($handle)
    {
        return (new Contact)->deleteContact($handle);
    }

    public static function createDnssec($domain, $signingkey, $algorithm, $publickey)
    {
        return (new Dnssec)->createKey($domain, $signingkey, $algorithm, $publickey);
    }

    public static function getDomainInfo($domain)
    {
        return (new Domain)->getDomainInfo($domain);
    }

    public function deleteDnssec($domain)
    {
        return (new Domain)->deleteDomain($domain);
    }


}
