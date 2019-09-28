<?php

namespace YWatchman\LaravelEPP\Epp;

use Metaregistrar\EPP\eppCheckContactRequest;
use Metaregistrar\EPP\eppCheckContactResponse;
use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppContactHandle;
use Metaregistrar\EPP\eppException;

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

}
