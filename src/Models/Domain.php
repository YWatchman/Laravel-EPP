<?php

namespace YWatchman\LaravelEPP\Models;

use YWatchman\LaravelEPP\Contracts\Transformable;

class Domain extends Model implements Transformable
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id'];
}
