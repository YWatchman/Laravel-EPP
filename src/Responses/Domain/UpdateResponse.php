<?php


namespace YWatchman\LaravelEPP\Responses\Domain;

use Symfony\Component\DomCrawler\Crawler;
use YWatchman\LaravelEPP\Responses\Response;

class UpdateResponse extends Response
{
    
    /**
     * CheckResponse constructor.
     *
     * @param string $rawXml
     */
    public function __construct(string $rawXml)
    {
        parent::__construct($rawXml);
    }
}
