<?php

namespace YWatchman\LaravelEPP\Epp;

use Illuminate\Support\Str;
use Metaregistrar\EPP\eppCheckDomainRequest;
use Metaregistrar\EPP\eppCheckDomainResponse;
use Metaregistrar\EPP\eppContactHandle;
use Metaregistrar\EPP\eppCreateDomainRequest;
use Metaregistrar\EPP\eppCreateDomainResponse;
use Metaregistrar\EPP\eppDeleteDomainRequest;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppHost;
use Metaregistrar\EPP\sidnEppInfoDomainRequest;
use Metaregistrar\EPP\sidnEppInfoDomainResponse;

class Domain extends Connection
{
    const DOMAIN_FREE = 1;
    const DOMAIN_TAKEN = 0;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get availability of multiple domains.
     *
     * @param array|string $domain
     *
     * @return array|bool
     */
    public function getAvailability($domain)
    {
        $eppDomain = $domain;
        if (!is_array($domain)) {
            try {
                $eppDomain = new eppDomain($domain);
            } catch (eppException $e) {
                return false;
            }
        }
        // Construct domain request for EPP
        $request = new eppCheckDomainRequest($eppDomain);

        // Fire request to EPP Service and save response
        /* @var $res eppCheckDomainResponse */
        try {
            if ($res = $this->epp->request($request)) {
                $checked = $res->getCheckedDomains();
                $info = [];
                // Loop over checked domains
                foreach ($checked as $check) {
                    // set domain status to free if available
                    if ($check['available']) {
                        $info[$check['domainname']] = self::DOMAIN_FREE;
                    } else {
                        $info[$check['domainname']] = self::DOMAIN_TAKEN;
                    }
                }

                return $info;
            }

            return false;
        } catch (eppException $e) {
            return false;
        }
    }

    /**
     * @param string      $name        Domain name
     * @param string      $registrant  Registrant contact
     * @param string      $admin       Admin contact
     * @param string      $tech        Technical contact
     * @param string|null $billing     Billing contact
     * @param array       $nameservers Preferred nameservers
     *
     * @return bool|\YWatchman\LaravelEPP\Models\Domain
     */
    public function createDomain(string $name, string $registrant, string $admin, string $tech, ?string $billing, array $nameservers)
    {
        if (!(new Nameserver())->checkNameservers($nameservers)) {
            if (!(new Nameserver())->createNameservers($nameservers)) {
                return false;
            }
        }

        try {
            $domain = new eppDomain($name);
            $domain->setRegistrant($registrant);
            $domain->addContact(new eppContactHandle($admin, eppContactHandle::CONTACT_TYPE_ADMIN));
            $domain->addContact(new eppContactHandle($tech, eppContactHandle::CONTACT_TYPE_TECH));
            if (1 != 1) {
                // SIDN only supports Admin and tech contact
                $domain->addContact(new eppContactHandle($billing, eppContactHandle::CONTACT_TYPE_BILLING));
            }
            $domain->setAuthorisationCode(Str::random(8));
            if (is_array($nameservers)) {
                foreach ($nameservers as $nameserver) {
                    $domain->addHost(new eppHost($nameserver)); // Todo: add compatibility for glue records
                }
            }

            $request = new eppCreateDomainRequest($domain);
            /** @var $res eppCreateDomainResponse epp create domain response */
            if ($res = $this->epp->request($request)) {
                $d = new \YWatchman\LaravelEPP\Models\Domain();
                $d->name = $res->getDomainName();

                return $d;
            }

            return false;
        } catch (eppException $e) {
            return false;
        }
    }

    public function deleteDomain($domain)
    {
        try {
            $eppDomain = new eppDeleteDomainRequest(new eppDomain($domain));
            $this->epp->request($eppDomain);

            return true;
        } catch (eppException $e) {
            return false;
        }
    }

    public function getDomainInfo($domain)
    {
        try {
            $eppDomain = new sidnEppInfoDomainRequest(new eppDomain($domain));
            /** @var sidnEppInfoDomainResponse $res */
            if ($res = $this->epp->request($eppDomain)) {
                return $res;
            }

            return false;
        } catch (eppException $e) {
            return false;
        }
    }
}
