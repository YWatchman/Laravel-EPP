<?php

namespace YWatchman\LaravelEPP\Support\Xml\Commands\Session;

use DOMElement;
use YWatchman\LaravelEPP\Support\Xml\Commands\Command;

class LogoutCommand extends Command
{
    /** @var DOMElement */
    protected $node;

    /**
     * LogoutCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->node = $this->createElement('logout');
        $this
            ->addNode($this->getCommandNode()->nodeName)
            ->appendChild($this->node);
    }
}
