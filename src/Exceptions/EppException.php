<?php


namespace YWatchman\LaravelEPP\Exceptions;

use Exception;

class EppException extends Exception
{
    public const SERVER_CLOSED_CONNECTION = 100;
    public const AUTHENTICATION_FAILED = 101;
    public const MISSING_REGISTRAR_CONFIG = 102;
    public const MISSING_CREDENTIALS = 103;
    public const INVALID_OPERATION = 104;

    public const CONTACT_TYPE_DOES_NOT_EXIST = 200;

    public const CONTACT_DOES_NOT_EXIST = 201;

    public const AUTH_TYPE_DOES_NOT_EXIST = 300;

    public const NOT_IMPLEMENTED = 999;

    public static function serverClosedConnection(int $code, string $msg = 'Empty response'): EppException
    {
        return new self(
            sprintf('`%d`: The EPP server unexpectedly closed the connection. Message: %s', $code, $msg),
            self::SERVER_CLOSED_CONNECTION
        );
    }

    public static function InvalidOperation(string $operation)
    {
        return new self(
            sprintf('The given operation was invalid, operation: %s', $operation),
            self::INVALID_OPERATION
        );
    }

    public static function authenticationFailed(int $code): EppException
    {
        return new self(
            sprintf('`%d`: Failed authenticating with the EPP server.', $code),
            self::AUTHENTICATION_FAILED
        );
    }

    public static function missingCredentials(string $registrar): EppException
    {
        return new self(
            sprintf('Failed to validate config variables for registrar %s.', $registrar),
            self::MISSING_CREDENTIALS
        );
    }

    public static function missingRegistrarConfig(string $registrar): EppException
    {
        return new self(
            sprintf('Registrar %s is not configured.', $registrar),
            self::MISSING_REGISTRAR_CONFIG
        );
    }

    public static function contactTypeDoesNotExist(string $type)
    {
        return new self(
            sprintf('Contact type %s does not exist.', $type),
            self::CONTACT_TYPE_DOES_NOT_EXIST
        );
    }

    public static function contactDoesNotExist(string $handle)
    {
        return new self(
            sprintf('Contact `%s` does not exist.', $handle),
            self::CONTACT_DOES_NOT_EXIST
        );
    }

    public static function authTypeDoesNotExist(string $type)
    {
        return new self(
            sprintf('AuthInfo type %s does not exist.', $type),
            self::AUTH_TYPE_DOES_NOT_EXIST
        );
    }
}
