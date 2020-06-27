<?php

namespace YWatchman\LaravelEPP\Responses\Host;

use Symfony\Component\DomCrawler\Crawler;
use YWatchman\LaravelEPP\Responses\Response;

class CheckResponse extends Response
{
    /** @var array */
    protected $availableNameservers = [];

    /** @var array */
    protected $occupiedNameservers = [];

    /**
     * CheckResponse constructor.
     *
     * @param string $rawXml
     */
    public function __construct(string $rawXml)
    {
        parent::__construct($rawXml);
        $data = $this->response->filter('response > resData > chkData > cd');

        $data->each(function (Crawler $domain) {
            $domain = $domain->filter('cd > name');
            if ($domain->attr('avail') === 'true') {
                $this->addAvailableNameserver($domain->text());
            } else {
                $this->addOccupiedNameserver($domain->text());
            }
        });
    }

    /**
     * @return array
     */
    public function getAvailableNameservers(): array
    {
        return $this->availableNameservers;
    }

    /**
     * @return array
     */
    public function getOccupiedNameservers(): array
    {
        return $this->occupiedNameservers;
    }

    /**
     * Add nameserver to available list.
     *
     * @param string $nameserver
     */
    private function addAvailableNameserver(string $nameserver): void
    {
        $this->availableNameservers[] = $nameserver;
    }

    /**
     * Add domain to occupied list.
     *
     * @param string $nameserver
     */
    private function addOccupiedNameserver(string $nameserver): void
    {
        $this->occupiedNameservers[] = $nameserver;
    }
}
