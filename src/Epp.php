<?php

namespace YWatchman\LaravelEPP;

use Exception;
use YWatchman\LaravelEPP\Exceptions\EppException;
use YWatchman\LaravelEPP\Support\Xml\Commands\Session\HelloCommand;
use YWatchman\LaravelEPP\Support\Xml\Commands\Session\LoginCommand;
use YWatchman\LaravelEPP\Support\Xml\Commands\Session\LogoutCommand;

class Epp
{
    /** @var resource */
    protected $socket;

    /** @var bool */
    protected $loggedIn = false;

    /**
     * @var string|null
     */
    protected $helloMsg;

    /** @var string */
    private $registrar;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $hostname;

    /** @var int */
    private $port;

    /**
     * Epp constructor.
     *
     * @param string $registrar
     *
     * @throws EppException
     */
    public function __construct(string $registrar = 'sidn')
    {
        $this->registrar = $registrar;
        $this->setupRegistrar();
    }

    /**
     * Epp destruction...
     */
    public function __destruct()
    {
        $this->logout();
        if ($this->socket !== null) {
            fclose($this->socket);
        }
    }

    /**
     * Initiate EPP session login.
     *
     * @throws Exception
     */
    public function login()
    {
        $this->start();

        $command = new HelloCommand();
        $cmdString = (string) $command;

        $this->helloMsg = $this->sendRequest($cmdString);

        $command = new LoginCommand($this->username, $this->password);

        $cmdString = (string) $command;
        $this->loggedIn = true;

        return $this->sendRequest($cmdString);
    }

    /**
     * @return string|void
     */
    public function logout()
    {
        if ($this->loggedIn) {
            $this->loggedIn = false;
            $cmd = (string) (new LogoutCommand());

            return $this->sendRequest($cmd);
        }
    }

    /**
     * Connect to EPP server.
     *
     * @throws EppException
     *
     * @return string|null
     */
    public function start()
    {
        $ctx = stream_context_create();

        $this->socket = stream_socket_client(
            sprintf('ssl://%s:%d', $this->hostname, $this->port),
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $ctx
        );

        if (!$this->socket) {
            throw EppException::serverClosedConnection($errno, $errstr);
        }

        return $this->read();
    }

    /**
     * Read stream socket response.
     *
     * @return string|null
     */
    public function read(): ?string
    {
        if ($this->socket !== false) {
            if (@feof($this->socket)) {
                return new Exception('Server closed connection.');
            }

            $header = @fread($this->socket, 4);

            if (empty($header) && feof($this->socket)) {
                return new Exception('Server closed connection.');
            }

            $length = unpack('N', $header)[1];

            if ($length <= 4) {
                return new Exception(
                    sprintf(
                        'Got bad frame header, length of %d. Length should be higher than 5.',
                        $length
                    )
                );
            }

            $data = fread($this->socket, ($length - 4));

            if (config('epp.debug')) {
                echo 'Read data.. parsing:'.PHP_EOL.PHP_EOL;
                $cmd = dom_import_simplexml(simplexml_load_string($data))->ownerDocument;
                $cmd->formatOutput = true;
                echo $cmd->saveXML();
            }

            return $data;
        }

        return false;
    }

    /**
     * Send EPP Request.
     *
     * @param $xml
     *
     * @return string|null
     */
    public function sendRequest($xml)
    {
        $xml = trim(preg_replace('/\s\s+/', '', $xml));

        if ($this->socket !== false) {
            if (config('epp.debug', false)) {
                echo PHP_EOL;
                echo 'Writing command to socket...'.PHP_EOL;
                $cmd = dom_import_simplexml(simplexml_load_string($xml))->ownerDocument;
                $cmd->formatOutput = true;
                echo $cmd->saveXML();
            }
            fwrite($this->socket, $this->getBigEndianLength($xml).$xml);
        }

        return $this->read();
    }

    /**
     * First four bits of a packet are the request length.
     *
     * @param $xml
     *
     * @return false|string
     */
    public function getBigEndianLength($xml)
    {
        return pack('N', strlen($xml) + 4);
    }

    /**
     * Setup registrar credentials.
     *
     * @throws EppException
     */
    private function setupRegistrar()
    {
        $config = config(sprintf('epp.registrars.%s', $this->registrar));
        if ($config === null) {
            throw EppException::missingRegistrarConfig($this->registrar);
        }

        if (
            !isset($config['username'], $config['password'], $config['hostname'], $config['port'])
            || !is_string($config['username'])
            || !is_string($config['password'])
            || !is_string($config['hostname'])
            || !is_int($config['port'])
        ) {
            throw EppException::missingCredentials($this->registrar);
        }

        $this->hostname = $config['hostname'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->port = $config['port'];
    }
}
