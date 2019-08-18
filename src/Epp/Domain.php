<?php

namespace YWatchman\LaravelEPP\Epp;

use Metaregistrar\EPP\eppCheckDomainRequest;
use Metaregistrar\EPP\eppCheckDomainResponse;
use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppContactHandle;
use Metaregistrar\EPP\eppCreateDomainRequest;
use Metaregistrar\EPP\eppCreateDomainResponse;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppHost;
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
     * @param array $domains
     * @return array|bool
     * @throws eppException
     */
    public function getAvailability(array $domains)
    {
        // Construct domain request for EPP
        $request = new eppCheckDomainRequest($domains);

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
    public function createDomain(string $name, Contact $registrant, Contact $admin, Contact $tech, Contact $billing, array $nameservers)
    {
        $domain = new eppDomain($name, $registrant->contactId);
        $domain->setRegistrant(new eppContactHandle($registrant));
        $domain->addContact(new eppContactHandle($admin, eppContactHandle::CONTACT_TYPE_ADMIN));
        $domain->addContact(new eppContactHandle($tech, eppContactHandle::CONTACT_TYPE_TECH));
        $domain->addContact(new eppContactHandle($billing, eppContactHandle::CONTACT_TYPE_BILLING));
        $domain->setAuthorisationCode('cyb3rfus10n');
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