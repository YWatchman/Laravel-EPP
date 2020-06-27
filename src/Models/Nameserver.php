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

    public const VERSION_V4 = 'v4';
    public const VERSION_V6 = 'v6';

    /**
     * @return array
     */
    public function fields(): array
    {
        $data = $this->attributes;

        return $data;
    }
}
