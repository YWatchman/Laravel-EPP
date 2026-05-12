<?php

namespace YWatchman\LaravelEPP;

use Illuminate\Support\Facades\Log;
use YWatchman\LaravelEPP\Exceptions\EppException;
use YWatchman\LaravelEPP\Support\Xml\Commands\Session\HelloCommand;
use YWatchman\LaravelEPP\Support\Xml\Commands\Session\LoginCommand;
use YWatchman\LaravelEPP\Support\Xml\Commands\Session\LogoutCommand;

class Epp
{
    /** @var resource */
    protected $socket;

    protected bool $loggedIn = false;

    protected ?string $helloMsg;

    private string $registrar;

    private string $username;

    private string $password;

    private string $hostname;

    private int $port;

    /**
     * Epp constructor.
     *
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
     *
     * @throws EppException
     */
    public function __destruct()
    {
        if ($this->socket !== null) {
            $this->logout();
            fclose($this->socket);
        }
    }

    /**
     * Initiate EPP session login.
     *
     * @throws EppException
     */
    public function login(): ?string
    {
        $this->start();

        $command = new HelloCommand;
        $cmdString = (string) $command;

        $this->helloMsg = $this->sendRequest($cmdString);

        $command = new LoginCommand($this->username, $this->password);

        $cmdString = (string) $command;
        $this->loggedIn = true;

        return $this->sendRequest($cmdString);
    }

    /**
     * @return string|void
     *
     * @throws EppException
     */
    public function logout()
    {
        if ($this->loggedIn) {
            $this->loggedIn = false;
            $cmd = (string) (new LogoutCommand);

            return $this->sendRequest($cmd);
        }
    }

    /**
     * Connect to EPP server.
     *
     * @throws EppException
     */
    public function start(): ?string
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

        if (! $this->socket) {
            throw EppException::serverClosedConnection($errno, $errstr);
        }

        return $this->read();
    }

    /**
     * Read stream socket response.
     *
     * @throws EppException
     */
    public function read(): ?string
    {
        if ($this->socket) {
            if (@feof($this->socket)) {
                throw EppException::serverClosedConnection(0, 'Server closed connection.');
            }

            $header = @fread($this->socket, 4);

            if (empty($header) && feof($this->socket)) {
                throw EppException::serverClosedConnection(0, 'Server closed connection.');
            }

            $length = unpack('N', $header)[1];

            if ($length <= 4) {
                throw EppException::badFrame($length);
            }

            $data = fread($this->socket, ($length - 4));

            if (config('epp.debug')) {
                $cmd = dom_import_simplexml(simplexml_load_string($data))->ownerDocument;
                $cmd->formatOutput = true;
                Log::debug('EPP read: '.$cmd->saveXML());
            }

            return $data;
        }

        return null;
    }

    /**
     * Send EPP Request.
     *
     *
     * @throws EppException
     */
    public function sendRequest($xml): ?string
    {
        $xml = trim(preg_replace('/\s\s+/', '', $xml));

        if ($this->socket) {
            if (config('epp.debug', false)) {
                $cmd = dom_import_simplexml(simplexml_load_string($xml))->ownerDocument;
                $cmd->formatOutput = true;
                Log::debug('EPP write: '.$cmd->saveXML());
            }
            fwrite($this->socket, $this->getBigEndianLength($xml).$xml);
        }

        return $this->read();
    }

    /**
     * First four bits of a packet are the request length.
     */
    public function getBigEndianLength($xml): false|string
    {
        return pack('N', strlen($xml) + 4);
    }

    public function isLoggedIn(): bool
    {
        return $this->loggedIn;
    }

    /**
     * Setup registrar credentials.
     *
     * @throws EppException
     */
    private function setupRegistrar(): void
    {
        $config = config(sprintf('epp.registrars.%s', $this->registrar));
        if ($config === null) {
            throw EppException::missingRegistrarConfig($this->registrar);
        }

        if (
            ! isset($config['username'], $config['password'], $config['hostname'], $config['port'])
            || ! is_string($config['username'])
            || ! is_string($config['password'])
            || ! is_string($config['hostname'])
            || ! is_int($config['port'])
        ) {
            throw EppException::missingCredentials($this->registrar);
        }

        $this->hostname = $config['hostname'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->port = $config['port'];
    }
}
