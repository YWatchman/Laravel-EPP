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

class UpdateCommand extends Command
{
    use ProvidesContactCommand;

    public const NODE_BASE = 'contact';
    public const NODE = 'contact:update';
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
     * @var string
     */
    protected $registrar;

    /**
     * CreateCommand constructor.
     *
     * @param Contact $contact
     * @param string  $registrar
     *
     * @throws EppException
     */
    public function __construct(Contact $contact, $registrar = Registrar::REGISTRAR_SIDN)
    {
        parent::__construct();

        $this->contact = $contact;
        $this->registrar = $registrar;

        $this->node = $this->createElement('update');

        $n = $this->addNode($this->getCommandNode()->nodeName);
        $n
            ->appendChild($this->node)
            ->appendChild($this->getUpdateNode());
        $n->appendChild($this->getExtensionNode());
    }

    /**
     * @return DOMElement
     */
    protected function getUpdateNode()
    {
        $contact = new ContactTransformer($this->contact);
        $contact = $contact->toArray();

        return $this->handleContact($contact);
    }

    /**
     * Generate contact extension.
     *
     * @throws EppException
     *
     * @return DOMElement
     */
    protected function getExtensionNode()
    {
        $node = $this->createElement('extension');
        $classPath = Extension::contactInstance($this->registrar);

        try {
            $reflection = new ReflectionClass($classPath);
            /** @var Extension $extension */
            $extension = $reflection->newInstance($this->contact);
        } catch (ReflectionException $e) {
            throw new EppException('Could not load extension class.', 999, $e);
        }

        $node->appendChild(
            $this->document->importNode(
                $extension->getExtension(),
                true
            )
        );

        return $node;
    }
}
