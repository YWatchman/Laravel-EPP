<?php

namespace YWatchman\LaravelEPP\Support\Xml\Extensions\Sidn;

use DOMElement;
use YWatchman\LaravelEPP\Contracts\IsContact;
use YWatchman\LaravelEPP\Exceptions\EppException;

class ContactExtension extends SidnExtension
{
    /**
     * ContactExtension constructor.
     *
     * @param IsContact $contact
     * @throws EppException
     */
    public function __construct(IsContact $contact)
    {
        parent::__construct();

        $this->extension->appendChild(
            $this->helper->createElement(
                $this->prefixedName('create')
            )
        )->appendChild(
            $this->getContactData($contact->fields())
        );
    }

    /**
     * Return the fields array as a DOM node.
     *
     * @param array $fields
     * @return DOMElement
     */
    private function getContactData(array $fields): DOMElement
    {
        $node = $this->helper->createElement(
            $this->prefixedName('contact')
        );

        foreach ($fields as $key => $value) {
            $node->appendChild(
                $this->helper->createElement(
                    $this->prefixedName($key),
                    $value
                )
            );
        }

        return $node;
    }
}
