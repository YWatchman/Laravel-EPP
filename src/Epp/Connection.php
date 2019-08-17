<?php

namespace YWatchman\LaravelEPP\Epp;

use Metaregistrar\EPP\eppConnection;

class Connection
{

    /** @var $connection \Metaregistrar\EPP\eppConnection */
    protected $connection;

    /**
     * Connection constructor.
     * @throws \Metaregistrar\EPP\eppException
     */
    public function __construct()
    {
        $this->connection = eppConnection::create(
            env('EPP_SETTINGS_FILE', config('laravel-epp.settingsFile')),
            env('APP_DEBUG', false)
        );
    }

    /**
     * Get logged in connection
     * @return bool|eppConnection|mixed
     */
    public function getConnection()
    {
        if($this->connection->login()) return $this->connection;
        return false;
    }

}
