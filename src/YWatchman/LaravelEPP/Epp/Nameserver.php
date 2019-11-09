<?php

namespace YWatchman\LaravelEPP\Epp;

use Metaregistrar\EPP\eppCheckHostRequest;
use Metaregistrar\EPP\eppCheckHostResponse;
use Metaregistrar\EPP\eppCreateHostRequest;
use Metaregistrar\EPP\eppCreateHostResponse;
use Metaregistrar\EPP\eppDeleteHostRequest;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppHost;
use Metaregistrar\EPP\sidnEppException;
use YWatchman\LaravelEPP\Exceptions\EppCheckException;

class Nameserver extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $nameservers
     *
     * @return bool|array
     * @throws EppCheckException
     */
    public function checkNameservers($nameservers)
    {
        if (is_array($nameservers)) {
            $checkNames = [];
            foreach ($nameservers as $nameserver) {
                $checkNames[$nameserver] = new eppHost($nameserver);
            }
            /* @var eppCheckHostResponse $response */
            try {
                $chksrvs = [];
                $check = new eppCheckHostRequest($checkNames);
                if ($response = $this->epp->request($check)) {
                    $checks = $response->getCheckedHosts();
                    $allchecksok = true;
                    $errors = [];
                    foreach ($checks as $server => $check) {
                        if ($check) {
                            $errors[] = "$server does not exist...".PHP_EOL;
                            $chksrvs[] = $server;
                            $allchecksok = false;
                        }
                    }

                    return $chksrvs;
                }
            } catch (sidnEppException $e) {
                throw new EppCheckException($e->getSidnErrorMessage(), $e->getSidnErrorCode());
            } catch (eppException $e) {
                throw new EppCheckException($e->getMessage(), $e->getCode());
            }
        }

        return false;
    }

    /**
     * Create nameservers.
     *
     * @param $nameservers
     *
     * @return bool
     */
    public function createNameservers($nameservers)
    {
        $errors = [];
        if (is_string($nameservers)) {
            $nameservers = (array) $nameservers;
        }
        foreach ($nameservers as $nameserver) {
            try {
                $eppHost = new eppCreateHostRequest(new eppHost($nameserver));
                /** @var eppCreateHostResponse $res */
                if ($res = $this->epp->request($eppHost)) {
                    // success
                } else {
                    $errors[] = "$nameserver couldn't be created";
                }
            } catch (sidnEppException $e) {
                $errors[] = "$nameserver couldn't be created: " . $e->getSidnErrorMessage();
            } catch (eppException $e) {
                $errors[] = "$nameserver couldn't be created: " . $e->getMessage();
            }
        }

        print_r($errors);

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
