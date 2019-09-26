<?php

namespace YWatchman\LaravelEPP\Epp;

use Illuminate\Support\Str;
use Metaregistrar\EPP\eppCheckDomainRequest;
use Metaregistrar\EPP\eppCheckDomainResponse;
use Metaregistrar\EPP\eppCheckHostRequest;
use Metaregistrar\EPP\eppCheckHostResponse;
use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppContactHandle;
use Metaregistrar\EPP\eppCreateDomainRequest;
use Metaregistrar\EPP\eppCreateDomainResponse;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppHost;
use Metaregistrar\EPP\eppInfoDomainRequest;
use YWatchman\LaravelEPP\Models\Domain\Contact;

class Domain extends Connection
{

    const DOMAIN_FREE = 1;
    const DOMAIN_TAKEN = 0;
    /** @var bool|eppConnection|mixed $epp Constructed eppConnection */
    private $epp;

    public function __construct()
    {
        parent::__construct();
        $this->epp = $this->getConnection();
    }

    /**
     * Get availability of multiple domains
     *
     * @param array|string $domain
     * @return array|bool
     * @throws eppException
     */
    public function getAvailability($domain)
    {
        $eppDomain = $domain;
        if(!is_array($domain)) {
            $eppDomain = new eppDomain($domain);
        }
        // Construct domain request for EPP
        $request = new eppCheckDomainRequest($eppDomain);

        // Fire request to EPP Service and save response
        /* @var $res eppCheckDomainResponse|null */
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
    }

    public function checkNameservers($nameservers)
    {
        $checks = [];
        if(is_array($nameservers)) {
            foreach($nameservers as $nameserver) {
                $checkNames[] = new eppHost($nameserver);
            }
            $check = new eppCheckHostRequest($checkNames);
            /** @var eppCheckHostResponse $response */
            if($response = $this->epp->request($check)) {
                $checks = $response->getCheckedHosts();
                $allchecksok = true;
                $errors = [];
                foreach($checks as $server => $check) {
                    if($check) {
                        $errors[] = "$server does not exist..." . PHP_EOL;
                        $allchecksok = false;
                    }
                }
                return $allchecksok;
            }
        }
        return false;
    }

    public function createNameserver($nameserver)
    {
        // Todo: create nameserver
    }

    /**
     * @param string $name Domain name
     * @param Contact $registrant Registrant contact
     * @param Contact $admin Admin contact
     * @param Contact $tech Technical contact
     * @param Contact $billing Billing contact
     * @param array $nameservers Preferred nameservers
     * @return bool|\YWatchman\LaravelEPP\Models\Domain
     * @throws eppException
     */
    public function createDomain(?string $name, ?string $registrant, ?string $admin, ?string $tech, ?string $billing, array $nameservers)
    {
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
    }

}
