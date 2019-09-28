<?php

namespace YWatchman\LaravelEPP\Epp;

use Exception;
use Metaregistrar\EPP\eppCheckHostRequest;
use Metaregistrar\EPP\eppCheckHostResponse;
use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppCreateHostRequest;
use Metaregistrar\EPP\eppCreateHostResponse;
use Metaregistrar\EPP\eppDeleteHostRequest;
use Metaregistrar\EPP\eppDnssecUpdateDomainRequest;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppHost;
use Metaregistrar\EPP\eppSecdns;

class Dnssec extends Connection
{

    /** @var bool|eppConnection|mixed $epp Constructed eppConnection */
    private $epp;

    public function __construct()
    {
        parent::__construct();
        $this->epp = $this->getConnection();
    }

    public function createKey($domain, $signingkey, $algorithm, $publickey)
    {
        try {
            $eppDomain = new eppDomain($domain);
            $eppSec = new eppSecdns;
            $eppSec->setKey($signingkey, $algorithm, $publickey);
            $eppDomain->addSecdns($eppSec);
            $eppDnssec = new eppDnssecUpdateDomainRequest($domain, $eppDomain);
            $this->epp->request($eppDnssec);
            return true;
        } catch (eppException $e) {
            return false;
        }
    }
}
