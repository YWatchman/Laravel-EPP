<?php


namespace YWatchman\LaravelEPP\Support\Xml\Objects;

class Registrar
{

    /** @var string[] */
    public const AVAILABLE_REGISTRIES = [
        self::REGISTRAR_SIDN,
    ];

    /** @var string */
    public const REGISTRAR_SIDN = 'SIDN';
}
