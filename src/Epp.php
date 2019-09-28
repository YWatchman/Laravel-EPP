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

    public static function createDomain($data)
    {
        return (new Domain)->createDomain($data['domainname'], $data['registrant']['handle'], $data['admin']['handle'], $data['tech']['handle'], null, $data['nameservers']);
    }

    public static function checkContact($handle)
    {
        return (new Contact)->checkContact($handle);
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
