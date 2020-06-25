<?php

namespace YWatchman\LaravelEPP\Models;

use YWatchman\LaravelEPP\Contracts\Transformable;

class Domain extends Model implements Transformable
{
    /**
     * Model columns.
     *
     * @var string[]
     */
    protected $columns = [
        'name',
        'sld',
        'tld',
    ];
}
