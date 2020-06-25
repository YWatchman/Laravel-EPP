<?php

namespace YWatchman\LaravelEPP\Models;

use YWatchman\LaravelEPP\Contracts\IsContact;
use YWatchman\LaravelEPP\Contracts\Transformable;

class Nameserver extends Model implements Transformable
{
    protected $columns = [
        'name',
        'address',
        'version',
    ];

    /**
     * @return array
     */
    public function fields(): array
    {
        $data = $this->attributes;

        return $data;
    }
}
