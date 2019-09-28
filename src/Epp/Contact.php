<?php

namespace YWatchman\LaravelEPP\Epp;

use Metaregistrar\EPP\eppCheckContactRequest;
use Metaregistrar\EPP\eppCheckContactResponse;
use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppContact;
use Metaregistrar\EPP\eppContactHandle;
use Metaregistrar\EPP\eppContactPostalInfo;
use Metaregistrar\EPP\eppCreateContactRequest;
use Metaregistrar\EPP\eppCreateContactResponse;
use Metaregistrar\EPP\eppDeleteContactRequest;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppUpdateContactRequest;
use Metaregistrar\EPP\sidnEppCreateContactRequest;

class Contact extends Connection
{

    /** @var bool|eppConnection|mixed $epp Constructed eppConnection */
    private $epp;

    public function __construct()
    {
        parent::__construct();
        $this->epp = $this->getConnection();
    }

    /**
     * @param $handle
     * @return bool
     * @throws \Metaregistrar\EPP\eppException
     */
    public function checkContact($handle)
    {
        $eppHandle = new eppCheckContactRequest(new eppContactHandle($handle));
        /** @var eppCheckContactResponse $res */
        try {
            $this->epp->request($eppHandle);
            return true;
        } catch (eppException $e) {
            return false;
        }
    }

    /**
     * Create contact
     *
     * @param $name
     * @param $email
     * @param $phone
     * @param $city
     * @param $countryCode
     * @param $street
     * @param $province
     * @param $zip
     * @param $org
     * @param string $registrar
     * @return bool|eppContactHandle
     */
    public function createContact($name, $email, $phone, $city, $countryCode, $street, $province, $zip, $org = null, $registrar = 'sidn')
    {
        $eppPostalInfo = new eppContactPostalInfo($name, $city, $countryCode, $org, $street, $province, $zip, 'loc');
        try {
            if($registrar == 'sidn') {
                $eppContact = new sidnEppCreateContactRequest(new eppContact($eppPostalInfo, $email, $phone));
            } else {
                $eppContact = new eppCreateContactRequest(new eppContact($eppPostalInfo, $email, $phone));
            }
            /** @var eppCreateContactResponse $contact */
            $contact = $this->epp->request($eppContact);
            return $contact->getContactHandle();
        } catch (eppException $e) {
            return false;
        }
    }

    public function updateContact($handle, $name, $email, $phone, $city, $countryCode, $street, $province, $zip, $org = null)
    {
        $update = new eppContact(new eppContactPostalInfo($name, $city, $countryCode, $org, $street, $province, $zip, eppContact::TYPE_LOC), $email, $phone);
        try {
            $eppContact = new eppUpdateContactRequest($handle, null, null, $update);
            $this->epp->request($eppContact);
            return true;
        } catch (eppException $e) {
            return false;
        }
    }

    /**
     * Delete contact
     *
     * @param $handle
     * @return bool
     */
    public function deleteContact($handle)
    {
        try {
            $eppHandle = new eppDeleteContactRequest(new eppContactHandle($handle));
            $this->epp->request($eppHandle);
            return true;
        } catch (eppException $e) {
            return false;
        }
    }

}
