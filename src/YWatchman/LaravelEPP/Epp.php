<?php

namespace YWatchman\LaravelEPP;

use Illuminate\Foundation\Application;
use Metaregistrar\EPP\eppCreateDomainResponse;
use Metaregistrar\EPP\eppException;
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
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Check if domain is available.
     *
     * @param $domain
     *
     * @throws Exceptions\EppCheckException
     * @throws eppException
     *
     * @return array|bool
     */
    public static function getDomainAvailability($domain)
    {
        return (new Domain())->getAvailability($domain);
    }

    /**
     * Register domain name.
     *
     * @param $name
     * @param $registrant
     * @param $admin
     * @param $tech
     * @param $nameservers
     * @param null $billing
     *
     * @throws Exceptions\DomainRegistrationException
     * @throws Exceptions\EppCheckException
     * @throws eppException
     *
     * @return bool|eppCreateDomainResponse
     * @return bool|Models\Domain
     */
    public static function createDomain($name, $registrant, $admin, $tech, $nameservers, $billing = null)
    {
        return (new Domain())->createDomain($name, $registrant, $admin, $tech, $billing, $nameservers);
    }

    /**
     * Transfer domain name.
     *
     * @param $name
     * @param $code
     * @param $registrant
     * @param $admin
     * @param $tech
     * @param $nameservers
     * @param null $billing
     *
     * @throws Exceptions\DomainRegistrationException
     * @throws Exceptions\EppCheckException
     * @throws eppException
     *
     * @return bool|eppCreateDomainResponse
     * @return bool|Models\Domain
     */
    public static function transferDomain($name, $code, $registrant, $admin, $tech, $nameservers, $billing = null)
    {
        return (new Domain())->transferDomain($name, $code, $registrant, $admin, $tech, $billing, $nameservers);
    }

    /**
     * Move domain name to quarantine.
     *
     * @param $domain
     *
     * @throws Exceptions\EppCheckException
     * @throws eppException
     *
     * @return bool
     */
    public static function deleteDomain($domain)
    {
        return (new Domain())->deleteDomain($domain);
    }

    /**
     * Check if nameserver existance.
     *
     * @param $nameserver
     *
     * @throws Exceptions\EppCheckException
     * @throws eppException
     *
     * @return array|bool
     */
    public static function checkNameserver($nameserver)
    {
        return (new Nameserver())->checkNameservers($nameserver);
    }

    /**
     * Create a 'host'.
     *
     * @param $nameservers
     *
     * @throws eppException
     *
     * @return bool
     */
    public static function createNameserver($nameservers)
    {
        return (new Nameserver())->createNameservers($nameservers);
    }

    public static function deleteNameserver($nameserver)
    {
        return (new Nameserver())->deleteNameserver($nameserver);
    }

    public static function checkContact($handle)
    {
        return (new Contact())->checkContact($handle);
    }

    public static function updateContact($handle, $name, $email, $phone, $city, $countryCode, $street, $province, $zip, $org = null)
    {
        return (new Contact())->updateContact($handle, $name, $email, $phone, $city, $countryCode, $street, $province, $zip, $org);
    }

    public static function createContact($name, $email, $phone, $city, $countryCode, $street, $province, $zip, $org = null, $registrar = 'sidn')
    {
        return (new Contact())->createContact($name, $email, $phone, $city, $countryCode, $street, $province, $zip, $org, $registrar);
    }

    public static function deleteContact($handle)
    {
        return (new Contact())->deleteContact($handle);
    }

    public static function createDnssec($domain, $signingkey, $algorithm, $publickey)
    {
        return (new Dnssec())->createKey($domain, $signingkey, $algorithm, $publickey);
    }

    public static function getDomainInfo($domain)
    {
        return (new Domain())->getDomainInfo($domain);
    }

    public function deleteDnssec($domain)
    {
        return (new Dnssec())->deleteKey($domain);
    }
}
