<?php

namespace YWatchman\LaravelEPP\Support\Xml\Objects\Contact;

class TechContact extends ContactObject
{
    public function __construct()
    {
        parent::__construct(self::CONTACT_TECH);
    }
}
