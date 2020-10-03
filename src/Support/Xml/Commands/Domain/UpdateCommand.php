<?php

namespace YWatchman\LaravelEPP\Support\Xml\Commands\Domain;

use DOMAttr;
use DOMElement;
use Exception;
use Illuminate\Support\Collection;
use YWatchman\LaravelEPP\Models\Domain;
use YWatchman\LaravelEPP\Support\Traits\Commands\HasDnssec;
use YWatchman\LaravelEPP\Support\Traits\Commands\HasScheduledDeletion;
use YWatchman\LaravelEPP\Support\Xml\Commands\Command;

class UpdateCommand extends Command
{
    use HasDnssec;
    use HasScheduledDeletion;

    public const NODE_BASE = 'domain';
    public const NODE = 'domain:update';
    public const NAMESPACE = 'urn:ietf:params:xml:ns:domain-1.0';

    /**
     * @var DOMElement
     */
    protected $node;

    /**
     * @var Domain
     */
    protected $domain;

    /**
     * EPP Extensions to be used.
     *
     * @var array
     */
    protected $extensions;

    /**
     * @var array
     */
    protected $delete;

    /**
     * @var array
     */
    protected $add;

    /**
     * @var array
     */
    protected $update;

    /**
     * UpdateCommand constructor.
     *
     * @param Domain $domain
     * @param array  $add
     * @param array  $delete
     * @param array  $update
     * @param array  $extensions
     */
    public function __construct(
        Domain $domain,
        array $add = [],
        array $delete = [],
        array $update = [],
        array $extensions = []
    ) {
        parent::__construct();

        $this->domain = $domain;
        $this->add = $add;
        $this->delete = $delete;
        $this->update = $update;
        $this->extensions = $extensions;

        $this->node = $this->createElement('update');

        if (array_key_exists('dnssec', $this->extensions)) {
            $this->enableDNSSEC();
        }

        if (array_key_exists('scheduledDelete', $this->extensions)) {
            $this->enabledScheduledDeletion();
        }

        $n = $this->addNode($this->getCommandNode()->nodeName);
        $n->appendChild($this->node)->appendChild($this->getUpdateNode());
        $n->appendChild($this->getExtensionNodes());
    }

    /**
     * Fill command.
     *
     * @return DOMElement
     */
    protected function getUpdateNode()
    {
        $node = $this->createElement(self::NODE);
        $namespace = new DOMAttr('xmlns:domain', self::NAMESPACE);
        $node->setAttributeNodeNS($namespace);

        $node->appendChild(
            $this->createElement(
                'domain:name',
                sprintf('%s.%s', $this->domain->sld, $this->domain->tld)
            )
        );

        // Todo: dedupe
        $node->appendChild($this->getAddNodes());
        $node->appendChild($this->getRemNodes());
        $node->appendChild($this->getChgNodes());

        return $node;
    }

    /**
     * Get add nodes.
     *
     * @throws Exception
     *
     * @return DOMElement
     */
    protected function getAddNodes(): DOMElement
    {
        $node = $this->createElement('domain:add');

        foreach ($this->add as $key => $addNode) {
            if (is_array($addNode)) {
                if (!is_string($key)) {
                    throw new Exception('Key should be a string.');
                }
                $element = $this->createElement($key);
                Collection::make($addNode)->each(function ($subNode, $key) use ($element) {
                    if ($subNode instanceof DOMElement) {
                        $element->appendChild($subNode);
                    } else {
                        $element->appendChild($this->createElement($key, $subNode));
                    }
                });
            } else {
                $element = $this->createElement($key, $addNode);
            }

            $node->appendChild($element);
        }

        return $node;
    }

    /**
     * Get remove nodes.
     *
     * @throws Exception
     *
     * @return DOMElement
     */
    protected function getRemNodes()
    {
        $node = $this->createElement('domain:rem');

        foreach ($this->delete as $key => $delNode) {
            if (is_array($delNode)) {
                if (!is_string($key)) {
                    throw new Exception('Key should be a string.');
                }
                $element = $this->createElement($key);
                Collection::make($delNode)->each(function ($subNode, $key) use ($element) {
                    if ($subNode instanceof DOMElement) {
                        $element->appendChild($subNode);
                    } else {
                        $element->appendChild($this->createElement($key, $subNode));
                    }
                });
            } else {
                $element = $this->createElement($key, $delNode);
            }

            $node->appendChild($element);
        }

        return $node;
    }

    /**
     * Get update nodes.
     *
     * @throws Exception
     *
     * @return DOMElement
     */
    protected function getChgNodes()
    {
        $node = $this->createElement('domain:chg');

        foreach ($this->update as $key => $chgNode) {
            if (is_array($chgNode)) {
                if (!is_string($key)) {
                    throw new Exception('Key should be a string.');
                }
                $element = $this->createElement($key);
                Collection::make($chgNode)->each(function ($subNode, $key) use ($element) {
                    if ($subNode instanceof DOMElement) {
                        $element->appendChild($subNode);
                    } else {
                        $element->appendChild($this->createElement($key, $subNode));
                    }
                });
            } else {
                $element = $this->createElement($key, $chgNode);
            }

            $node->appendChild($element);
        }

        return $node;
    }

    protected function getExtensionNodes()
    {
        $extNode = $this->createElement('extension');
        $nodes = [];

        if ($this->dnssec === true) {
            $nodes['secDNS'] = $this->createDnssecExtension(true);
        }

        if ($this->planned_cancellation === true) {
            $nodes['scheduledDelete'] = $this->scheduledCancellationNode();
        }

        foreach ($nodes as $node) {
            $extNode->appendChild($node);
        }

        return $extNode;
    }

    /**
     * Get contact nodes.
     *
     * @return array
     */
    protected function getContactNodes()
    {
        $nodes = [];

        $nodes[] = $this->createElement(
            'domain:registrant',
            $this->registrant->handle
        );

        $node = $this->createElement(
            'domain:contact',
            $this->admin->handle
        );
        $node->setAttribute('type', 'admin');
        $nodes[] = $node;

        $node = $this->createElement(
            'domain:contact',
            $this->tech->handle
        );
        $node->setAttribute('type', 'tech');
        $nodes[] = $node;

        return $nodes;
    }
}
