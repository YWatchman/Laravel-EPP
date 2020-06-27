<?php

namespace YWatchman\LaravelEPP\Responses;

use Symfony\Component\DomCrawler\Crawler;

class Response
{
    /** @var string */
    protected $rawXml = null;

    /** @var Crawler */
    protected $crawler;

    /** @var Crawler */
    protected $response;

    /** @var bool */
    protected $succeeded = false;

    /** @var string|null */
    protected $message = null;

    /** @var int */
    protected $code = 0;

    /**
     * @var string
     */
    protected $serverTransaction;

    /**
     * @var string
     */
    protected $clientTransaction;

    /**
     * Response constructor.
     *
     * @param string $rawXml
     */
    public function __construct(string $rawXml)
    {
        $this->rawXml = $rawXml;
        $this->crawler = new Crawler($rawXml);

        // Create base response.
        $this->response = $this->crawler->filter('epp > response');
        $result = $this->response->filter('response > result');

        $msg = $result->filter('result > msg');
        // Todo: implement RFC 5730 sec. 3
        $this->code = $result->attr('code');
        $this->succeeded = ($msg->count() === 1 && $this->code === '1000');
        $this->message = $msg->text();

        $this->serverTransaction = $this->response->filter('response > trID > svTRID')->text();

        $transaction = $this->response->filter('response > trID > clTRID');
        if ($transaction->count() > 0) {
            $this->clientTransaction = $transaction->text();
        }
    }

    /**
     * @return string
     */
    public function getServerTransaction(): string
    {
        return $this->serverTransaction;
    }

    /**
     * @return null|string
     */
    public function getClientTransaction(): ?string
    {
        return $this->clientTransaction;
    }

    /**
     * @return Crawler
     */
    public function getCrawler(): Crawler
    {
        return $this->crawler;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getRawXml(): string
    {
        return $this->rawXml;
    }

    /**
     * @return bool
     */
    public function isSucceeded(): bool
    {
        return $this->succeeded;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
}
