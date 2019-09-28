<?php

namespace YWatchman\LaravelEPP\Epp;

use Exception;
use Metaregistrar\EPP\eppCheckHostRequest;
use Metaregistrar\EPP\eppCheckHostResponse;
use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppCreateHostRequest;
use Metaregistrar\EPP\eppCreateHostResponse;
use Metaregistrar\EPP\eppDeleteHostRequest;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppHost;

class Nameserver extends Connection
{

    /** @var bool|eppConnection|mixed $epp Constructed eppConnection */
    private $epp;

    public function __construct()
    {
        parent::__construct();
        $this->epp = $this->getConnection();
    }

    /**
     * @param array|string $nameservers
     * @return bool
     * @throws eppException
     */
    public function checkNameservers($nameservers)
    {
        $checks = [];
        if (is_array($nameservers)) {
            foreach ($nameservers as $nameserver) {
                $checkNames[] = new eppHost($nameserver);
            }
            $check = new eppCheckHostRequest($checkNames);
            /** @var eppCheckHostResponse $response */
            if ($response = $this->epp->request($check)) {
                $checks = $response->getCheckedHosts();
                $allchecksok = true;
                $errors = [];
                foreach ($checks as $server => $check) {
                    if ($check) {
                        $errors[] = "$server does not exist..." . PHP_EOL;
                        $allchecksok = false;
                    }
                }
                if (env('APP_DEBUG', false)) {
                    print_r($errors);
                }
                return $allchecksok;
            }
        } else {
            throw new Exception("\$nameserver not an array");
        }
        return false;
    }

    /**
     * Create nameservers
     *
     * @param $nameservers
     * @return bool
     * @throws eppException
     */
    public function createNameservers($nameservers)
    {
        $errors = [];
        if (is_string($nameservers)) {
            $nameservers = [$nameservers];
        }
        foreach ($nameservers as $nameserver) {
            $eppHost = new eppCreateHostRequest(new eppHost($nameserver));
            /** @var eppCreateHostResponse $res */
            if ($res = $this->epp->request($eppHost)) {
                // success
            } else {
                $errors[] = "$nameserver couldn't be created";
            }
        }
        return count($errors) == 0;
    }

    public function deleteNameserver($nameserver)
    {
        try {
            $eppHost = new eppDeleteHostRequest(new eppHost($nameserver));
            $this->epp->request($eppHost);
            return true;
        } catch (eppException $e) {
            return false;
        }
    }

}
