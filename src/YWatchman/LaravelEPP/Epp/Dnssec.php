<?php

namespace YWatchman\LaravelEPP\Epp;

use Metaregistrar\EPP\eppDnssecUpdateDomainRequest;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppSecdns;
use YWatchman\LaravelEPP\Epp;
use YWatchman\LaravelEPP\Exceptions\DnssecSigningException;

class Dnssec extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Sign a domain name.
     *
     * @param $domain
     * @param $signingkey
     * @param $algorithm
     * @param $publickey
     *
     * @throws DnssecSigningException
     *
     * @return bool
     */
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
            throw new DnssecSigningException($e->getMessage(), $e->getCode());
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
