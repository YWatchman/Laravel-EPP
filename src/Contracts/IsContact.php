<?php

namespace YWatchman\LaravelEPP\Contracts;

interface IsContact
{
    /**
     * Fields containing data such as legalForm.
     *
     * @return array
     */
    public function fields(): array;
}
