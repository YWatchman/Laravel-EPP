<?php

namespace YWatchman\LaravelEPP\Epp;

use Illuminate\Database\Eloquent\Model;
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
use Metaregistrar\EPP\sidnEppException;
use Metaregistrar\EPP\sidnEppInfoDomainRequest;
use Metaregistrar\EPP\sidnEppInfoDomainResponse;
use YWatchman\LaravelEPP\Exceptions\DomainRegistrationException;
use YWatchman\LaravelEPP\Exceptions\EppCheckException;
use YWatchman\LaravelEPP\Models\Domain as DomainModel;
use YWatchman\LaravelEPP\Models\Contact as ContactModel;

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
     * @throws EppCheckException
     */
    public function getAvailability($domain)
    {
        $eppDomain = $domain;
        if (!is_array($domain)) {
            try {
                $eppDomain = new eppDomain($domain);
            } catch (eppException $e) {
                throw new EppCheckException($e->getMessage(), $e->getCode());
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
            throw new EppCheckException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $name
     * @param string $code
     * @param string $registrant
     * @param $admin
     * @param $tech
     * @param $billing
     * @param array $nameservers
     * @param int $period
     * @param string $periodUnit
     * @return bool|DomainModel
     * @throws DomainRegistrationException
     * @throws EppCheckException
     * @throws eppException
     */
    public function transferDomain(string $name, string $code, $registrant, $admin, $tech, $billing, array $nameservers, int $period = 12, string $periodUnit = 'm')
    {
        return $this->createDomain($name, $registrant, $admin, $tech, $billing, $nameservers, $period, $periodUnit, $code);
    }

    /**
     * @param string $name Domain name
     * @param string $registrant Registrant contact
     * @param string $admin Admin contact
     * @param string $tech Technical contact
     * @param string|null $billing Billing contact
     * @param array $nameservers Preferred nameservers
     *
     * @param int $period
     * @param string $periodUnit
     * @param string|bool $code
     * @return bool|DomainModel
     * @throws DomainRegistrationException
     * @throws EppCheckException
     * @throws eppException
     */
    public function createDomain(string $name, string $registrant, $admin, $tech, $billing, array $nameservers, int $period = 12, string $periodUnit = 'm', $code = false)
    {
        $nameserver = new Nameserver();
        if ($srvs = $nameserver->checkNameservers($nameservers)) {
            if (!$nameserver->createNameservers($srvs)) {
                throw new EppCheckException('No nameservers available, creating nameservers failed', 120);
            }
        }

        try {
            $domain = new eppDomain($name);
            $domain->setRegistrant($registrant);

            try {
                $domain->setPeriodUnit($periodUnit);
                $domain->setPeriod($period);
            } catch (eppException $e) {
                throw new DomainRegistrationException('Setting subscription period failed.', 105);
            }

            if($admin instanceof ContactModel) {
                $admin = $admin->{config('laravel-epp.model.handle_key', 'handle')};
            }
            $domain->addContact(new eppContactHandle($admin, eppContactHandle::CONTACT_TYPE_ADMIN));

            if ($tech instanceof ContactModel) {
                $tech = $tech->{config('laravel-epp.model.handle_key', 'handle')};
            }
            $domain->addContact(new eppContactHandle($tech, eppContactHandle::CONTACT_TYPE_TECH));

            // SIDN only supports Admin and tech contact so if contains DRS of SIDN, don't handle add billing contact
            if (!Str::contains($this->getConnection()->getHostname(), 'domain-registry.nl')) {
                if ($billing instanceof ContactModel) {
                    $billing = $billing->{config('laravel-epp.model.handle_key', 'handle')};
                }
                $domain->addContact(new eppContactHandle($billing, eppContactHandle::CONTACT_TYPE_BILLING));
            }

            if ($code) {
                $domain->setAuthorisationCode($code);
            }

            if (is_array($nameservers)) {
                foreach ($nameservers as $key => $nameserver) {
                    if (count($nameservers) == count($nameservers, COUNT_RECURSIVE)) {
                        $nameserver = new eppHost($nameserver);
                    } else {
                        if (!filter_var($nameserver, FILTER_VALIDATE_IP)) {
                            throw new DomainRegistrationException('Glue record value does not have a valid IP Address.', 106);
                        }
                        $nameserver = new eppHost($key, $nameserver);
                    }
                    $domain->addHost($nameserver);
                }
            }

            $request = new eppCreateDomainRequest($domain);
            /** @var $res eppCreateDomainResponse epp create domain response */
            if ($res = $this->getConnection()->request($request)) {
                $d = new DomainModel();
                $d->name = $res->getDomainName();

                return $d;
            }

            return false;
        } catch (sidnEppException $e) {
            throw new EppCheckException($e->getMessage(), $e->getCode(), $e);
        } catch (eppException $e) {
            throw new EppCheckException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete domain name registration
     *
     * @param $domain
     * @return bool
     * @throws EppCheckException
     */
    public function deleteDomain($domain)
    {
        try {
            $eppDomain = new eppDeleteDomainRequest(new eppDomain($domain));
            $this->epp->request($eppDomain);

            return true;
        } catch (sidnEppException $e) {
            throw new EppCheckException($e->getMessage(), $e->getCode(), $e);
        } catch (eppException $e) {
            throw new EppCheckException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get basic domain name information
     *
     * @param $domain
     * @return bool|sidnEppInfoDomainResponse
     * @throws EppCheckException
     */
    public function getDomainInfo($domain)
    {
        try {
            $eppDomain = new sidnEppInfoDomainRequest(new eppDomain($domain));
            /** @var sidnEppInfoDomainResponse $res */
            if ($res = $this->epp->request($eppDomain)) {
                return $res;
            }

            return false;
        } catch (sidnEppException $e) {
            throw new EppCheckException($e->getSidnErrorMessage(), $e->getSidnErrorCode());
        } catch (eppException $e) {
            throw new EppCheckException($e->getMessage(), $e->getCode());
        }
    }
}
