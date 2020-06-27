<?php


namespace YWatchman\LaravelEPP\Support\Xml\Commands\Host;

use DOMElement;
use Illuminate\Database\Eloquent\Model;
use YWatchman\LaravelEPP\Models\Nameserver;
use YWatchman\LaravelEPP\Support\Traits\Commands\ProvidesCheckCommand;
use YWatchman\LaravelEPP\Support\Xml\Commands\Command;

class CheckCommand extends Command
{
    use ProvidesCheckCommand;

    public const NODE_BASE = 'host';
    public const NODE = 'host:check';
    public const NAMESPACE = 'urn:ietf:params:xml:ns:host-1.0';

    /**
     * @var DOMElement
     */
    protected $node;

    /**
     * @var Nameserver[]
     */
    protected $iterable;

    /**
     * CreateCommand constructor.
     *
     * @param Nameserver[]|Model[] $nameservers
     */
    public function __construct(array $nameservers)
    {
        parent::__construct();

        $this->iterable = $nameservers;

        $node = $this->addNode($this->getCommandNode()->nodeName);
        $node
            ->appendChild($this->createElement('check'))
            ->appendChild($this->generateCheck(self::NODE_BASE));
    }
}
