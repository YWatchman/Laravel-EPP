<?php

namespace YWatchman\LaravelEPP\Support\Xml\Commands\Session;

use DOMElement;
use YWatchman\LaravelEPP\Support\Xml\Commands\Command;

class HelloCommand extends Command
{
    /** @var DOMElement */
    protected $node;

    /**
     * HelloCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->addNode('hello');
    }
}
