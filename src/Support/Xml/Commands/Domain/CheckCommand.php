<?php


namespace YWatchman\LaravelEPP\Support\Xml\Commands\Domain;

use DOMElement;
use YWatchman\LaravelEPP\Models\Domain;
use YWatchman\LaravelEPP\Support\Traits\Commands\ProvidesCheckCommand;
use YWatchman\LaravelEPP\Support\Xml\Commands\Command;

class CheckCommand extends Command
{
    use ProvidesCheckCommand;

    public const NODE_BASE = 'domain';
    public const NODE = 'domain:check';
    public const NAMESPACE = 'urn:ietf:params:xml:ns:domain-1.0';

    /** @var DOMElement $node */
    protected $node;

    /** @var array Domains to check */
    protected $iterable;

    /**
     * CheckCommand constructor.
     * @param array $domains
     */
    public function __construct(array $domains)
    {
        parent::__construct();

        $this->iterable = $domains;

        $this->node = $this->createElement('check');

        $n = $this->addNode($this->getCommandNode()->nodeName);
        $n->appendChild($this->node)->appendChild($this->generateCheck(self::NODE_BASE));
    }
}
