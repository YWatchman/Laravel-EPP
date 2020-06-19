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
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->code = $crawler->attr('code');
        $this->field = $crawler->attr('field');
        $this->message = $crawler->text();
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
