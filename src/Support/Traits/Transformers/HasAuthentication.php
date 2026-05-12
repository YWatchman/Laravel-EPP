<?php

namespace YWatchman\LaravelEPP\Support\Traits\Transformers;

use YWatchman\LaravelEPP\Support\Xml\Commands\Command;

trait HasAuthentication
{
    /**
     * Append authentication node to the end.
     */
    public function includeAuth(string $password = Command::NOT_USED): void
    {
        $this->transformed['authInfo'] = [
            'pw' => $password,
        ];
    }
}
