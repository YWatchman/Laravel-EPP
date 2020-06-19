<?php

namespace YWatchman\LaravelEPP\Support\Traits\Transformers;

use YWatchman\LaravelEPP\Support\Xml\Commands\Command;

trait HasAuthentication
{

    /**
     * Append authentication node to the end.
     *
     * @param string $password
     */
    public function includeAuth($password = Command::NOT_USED)
    {
        $this->transformed['authInfo'] = [
            'pw' => $password
        ];
    }
}
