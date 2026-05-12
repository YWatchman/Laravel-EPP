<?php

namespace YWatchman\LaravelEPP\Responses\Domain;

use YWatchman\LaravelEPP\Responses\Response;

class UpdateResponse extends Response
{
    /**
     * CheckResponse constructor.
     */
    public function __construct(string $rawXml)
    {
        parent::__construct($rawXml);
    }
}
