<?php

namespace YWatchman\LaravelEPP;

use Illuminate\Foundation\Application;
use YWatchman\LaravelEPP\Epp\Contact;
use YWatchman\LaravelEPP\Epp\Domain;

class Epp
{

    /** @var Application */
    protected $app;

    /**
     * Epp constructor.
     * @param Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    public static function getDomainInfo($domain)
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


}
