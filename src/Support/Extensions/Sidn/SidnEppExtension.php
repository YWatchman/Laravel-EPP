<?php

namespace YWatchman\LaravelEPP\Support\Extensions\Sidn;

use Symfony\Component\DomCrawler\Crawler;
use YWatchman\LaravelEPP\Support\Extensions\Extension;

class SidnEppExtension extends Extension
{
    /** @var string */
    protected $code;

    /** @var string */
    protected $field;

    /** @var string */
    protected $message;

    /**
     * SidnEppExtension constructor.
     */
    public function __construct(Crawler $crawler)
    {
        $this->code = $crawler->attr('code');
        $this->field = $crawler->attr('field');
        $this->message = $crawler->text();
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
