<?php


namespace YWatchman\LaravelEPP\Support\Xml\Commands\Contact;

use DOMElement;
use Illuminate\Database\Eloquent\Model;
use YWatchman\LaravelEPP\Models\Contact;
use YWatchman\LaravelEPP\Support\Traits\Commands\ProvidesCheckCommand;
use YWatchman\LaravelEPP\Support\Xml\Commands\Command;
use YWatchman\LaravelEPP\Support\Xml\Objects\Registrar;

class CheckCommand extends Command
{
    use ProvidesCheckCommand;

    public const NODE_BASE = 'contact';
    public const NODE = 'contact:check';
    public const NAMESPACE = 'urn:ietf:params:xml:ns:contact-1.0';

    /**
     * @var DOMElement
     */
    protected $node;

    /**
     * @var Contact[]
     */
    protected $iterable;

    /**
     * CreateCommand constructor.
     *
     * @param Contact[]|Model[] $contacts
     */
    public function __construct(array $contacts)
    {
        parent::__construct();

        $this->iterable = $contacts;

        $node = $this->addNode($this->getCommandNode()->nodeName);
        $node
            ->appendChild($this->createElement('check'))
            ->appendChild($this->generateCheck(self::NODE_BASE));
    }
}
