<?php

namespace YWatchman\LaravelEPP\Contracts;

interface IsContact
{
    /**
     * Fields containing data such as legalForm.
     */
    public function fields(): array;
}
