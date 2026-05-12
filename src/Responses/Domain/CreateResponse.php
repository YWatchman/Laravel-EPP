<?php

namespace YWatchman\LaravelEPP\Responses\Domain;

use YWatchman\LaravelEPP\Responses\Response;

class CreateResponse extends Response
{
    /** @var string */
    protected $date;

    /** @var string */
    protected $name;

    /**
     * CreateResponse constructor.
     */
    public function __construct(string $rawXml)
    {
        parent::__construct($rawXml);
        $data = $this->response->filter('response > resData > creData');

        if ($this->isSucceeded()) {
            $this->date = $data->filter('creData > crDate')->text();
            $this->name = $data->filter('creData > name')->text();
        }
    }

    /**
     * Get creation date.
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Get domain name.
     */
    public function getName(): string
    {
        return $this->name;
    }
}
