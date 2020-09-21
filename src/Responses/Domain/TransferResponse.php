<?php

namespace YWatchman\LaravelEPP\Responses\Domain;

use YWatchman\LaravelEPP\Responses\Response;

class TransferResponse extends Response
{
    /** @var string */
    protected $status;

    /** @var string */
    protected $transferDate;

    /** @var string */
    protected $expirationDate;

    /** @var string */
    protected $token;

    /** @var string */
    protected $name;

    /**
     * CreateResponse constructor.
     *
     * @param string $rawXml
     */
    public function __construct(string $rawXml)
    {
        parent::__construct($rawXml);
        $data = $this->response->filter('response > resData > trnData');
        $extData = $this->response->filter('response > extension > ext > trnData');

        if ($this->isSucceeded()) {
            $expirationDate = $data->filter('trnData > exDate');
            if ($expirationDate->count() !== 0) {
                $this->expirationDate = $expirationDate->text();
            }

            $this->transferDate = $data->filter('trnData > acDate')->text();
            $this->name = $data->filter('trnData > name')->text();
            $this->status = $data->filter('trnData > trStatus')->text();

            $this->token = $extData->filter('trnData > pw');
        }
    }

    /**
     * Get creation date.
     *
     * @return string
     */
    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getTransferDate(): string
    {
        return $this->transferDate;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Get domain name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
