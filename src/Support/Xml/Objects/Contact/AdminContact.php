<?php

namespace YWatchman\LaravelEPP\Support\Xml\Objects\Contact;

class AdminContact extends ContactObject
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct(...func_get_args());
        $this->setType(self::CONTACT_ADMIN);
    }
}
