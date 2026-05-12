<?php

namespace YWatchman\LaravelEPP\Responses\Domain;

use Symfony\Component\DomCrawler\Crawler;
use YWatchman\LaravelEPP\Responses\Response;

class CheckResponse extends Response
{
    /** @var array */
    protected $availableDomains = [];

    /** @var array */
    protected $occupiedDomains = [];

    /**
     * CheckResponse constructor.
     */
    public function __construct(string $rawXml)
    {
        parent::__construct($rawXml);
        $data = $this->response->filter('response > resData > chkData > cd');

        $data->each(function (Crawler $domain) {
            $domain = $domain->filter('cd > name');
            if ($domain->attr('avail') === 'true') {
                $this->addAvailableDomain($domain->text());
            } else {
                $this->addOccupiedDomain($domain->text());
            }
        });
    }

    public function getAvailableDomains(): array
    {
        return $this->availableDomains;
    }

    public function getOccupiedDomains(): array
    {
        return $this->occupiedDomains;
    }

    /**
     * Add domain to available list.
     */
    private function addAvailableDomain(string $domainName): void
    {
        $this->availableDomains[] = $domainName;
    }

    /**
     * Add domain to occupied list.
     */
    private function addOccupiedDomain(string $domainName): void
    {
        $this->occupiedDomains[] = $domainName;
    }
}
