<?php

namespace YWatchman\LaravelEPP\Responses\Contact;

use YWatchman\LaravelEPP\Responses\Response;

class CreateResponse extends Response
{
    /** @var string */
    protected $date;

    /** @var string */
    protected $id;

    /**
     * CreateResponse constructor.
     *
     * @param string $rawXml
     */
    public function __construct(string $rawXml)
    {
        parent::__construct($rawXml);
        $data = $this->response->filter('response > resData > creData');

        if ($this->isSucceeded()) {
            $this->date = $data->filter('creData > crDate')->text();
            $this->id = $data->filter('creData > id')->text();
        }
    }

    /**
     * Get creation date.
     *
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Get domain name.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
