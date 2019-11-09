<?php

namespace YWatchman\LaravelEPP\Epp;

use Metaregistrar\EPP\eppConnection;

class Connection
{
    /** @var $connection \Metaregistrar\EPP\eppConnection */
    protected $connection;
    /** @var bool|eppConnection|mixed $epp Constructed eppConnection */
    protected $epp;

    /**
     * Connection constructor.
     *
     * @throws \Metaregistrar\EPP\eppException
     */
    public function __construct()
    {
        $this->connection = eppConnection::create(
            env('EPP_SETTINGS_FILE', config('laravel-epp.settingsFile', '/etc/cyberfusion/epp.ini')),
            env('EPP_DEBUG', false)
        );
        $this->epp = $this->getConnection();
    }

    /**
     * Get logged in connection.
     *
     * @return bool|eppConnection|mixed
     */
    public function getConnection()
    {
        if ($this->connection->isLoggedin() || $this->connection->login(true)) {
            return $this->connection;
        }

        return false;
    }
}
