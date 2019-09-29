<?php

namespace YWatchman\LaravelEPP\Epp;

use Metaregistrar\EPP\eppDnssecUpdateDomainRequest;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppSecdns;
use YWatchman\LaravelEPP\Epp;

class Dnssec extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createKey($domain, $signingkey, $algorithm, $publickey)
    {
        try {
            $eppDomain = new eppDomain($domain);
            $eppSec = new eppSecdns();
            $eppSec->setKey($signingkey, $algorithm, $publickey);
            $eppDomain->addSecdns($eppSec);
            $eppDnssec = new eppDnssecUpdateDomainRequest($domain, $eppDomain);
            $this->epp->request($eppDnssec);

            return true;
        } catch (eppException $e) {
            return false;
        }
    }

    public function deleteKey($domain)
    {
        $eppSec = Epp::getDomainInfo($domain)->getKeydata();

        try {
            $rem = $eppDomain = new eppDomain($domain);
            foreach ($eppSec as $key) {
                $rem->addSecdns($key);
            }
            $eppDnssec = new eppDnssecUpdateDomainRequest($eppDomain, null, $rem);
            $this->epp->request($eppDnssec);

            return true;
        } catch (eppException $e) {
            return false;
        }
    }
}
