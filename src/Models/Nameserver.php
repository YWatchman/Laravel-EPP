<?php

namespace YWatchman\LaravelEPP\Models;

use YWatchman\LaravelEPP\Contracts\Transformable;

class Nameserver extends Model implements Transformable
{
    protected $columns = [
        'name',
        'address', // Should be given in format '127.0.0.1-v4' or '::1-v4'
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

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getAddresses(): array
    {
        return explode(',', $this->address);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
