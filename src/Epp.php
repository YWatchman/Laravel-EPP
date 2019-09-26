<?php

namespace YWatchman\LaravelEPP;

use Illuminate\Foundation\Application;
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


}
