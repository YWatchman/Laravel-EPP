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
        $this->code = (int) $result->attr('code');
        $this->succeeded = ($msg->count() === 1 && $this->code === 1000);
        $this->message = $msg->text();

        $this->serverTransaction = $this->response->filter('response > trID > svTRID')->text();

        $transaction = $this->response->filter('response > trID > clTRID');
        if ($transaction->count() > 0) {
            $this->clientTransaction = $transaction->text();
        }
    }

    public function getServerTransaction(): string
    {
        return $this->serverTransaction;
    }

    public function getClientTransaction(): ?string
    {
        return $this->clientTransaction;
    }

    public function getCrawler(): Crawler
    {
        return $this->crawler;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getRawXml(): string
    {
        return $this->rawXml;
    }

    public function isSucceeded(): bool
    {
        return $this->succeeded;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
