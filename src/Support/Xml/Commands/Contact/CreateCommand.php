<?php


namespace YWatchman\LaravelEPP\Support\Xml\Commands\Contact;

use DOMElement;
use ReflectionClass;
use ReflectionException;
use YWatchman\LaravelEPP\Exceptions\EppException;
use YWatchman\LaravelEPP\Models\Contact;
use YWatchman\LaravelEPP\Support\Traits\Commands\ProvidesContactCommand;
use YWatchman\LaravelEPP\Support\Xml\Commands\Command;
use YWatchman\LaravelEPP\Support\Xml\Extensions\Extension;
use YWatchman\LaravelEPP\Support\Xml\Objects\Registrar;
use YWatchman\LaravelEPP\Transformers\ContactTransformer;

class CreateCommand extends Command
{
    use ProvidesContactCommand;

    public const NODE_BASE = 'contact';
    public const NODE = 'contact:create';
    public const NAMESPACE = 'urn:ietf:params:xml:ns:contact-1.0';

    /**
     * @var DOMElement
     */
    protected $node;

    /**
     * @var Contact
     */
    protected $contact;

    /**
     * CreateCommand constructor.
     *
     * @param Contact $contact
     * @throws EppException
     */
    public function __construct(Contact $contact)
    {
        parent::__construct();

        $this->contact = $contact;

        $this->node = $this->createElement('create');

        $n = $this->addNode($this->getCommandNode()->nodeName);
        $n
            ->appendChild($this->node)
            ->appendChild($this->getCreateNode());
        $n->appendChild($this->getExtensionNode());
    }

    /**
     * @return DOMElement
     */
    protected function getCreateNode()
    {
        $contact = new ContactTransformer($this->contact);
        $contact = $contact->toArray();

        return $this->handleContact($contact);
    }

    /**
     * Generate contact extension.
     *
     * @return DOMElement
     * @throws EppException
     */
    protected function getExtensionNode()
    {
        $node = $this->createElement('extension');


        return $node;
    }
}
