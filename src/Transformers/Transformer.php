<?php

namespace YWatchman\LaravelEPP\Transformers;

use YWatchman\LaravelEPP\Contracts\Transformable;
use YWatchman\LaravelEPP\Exceptions\EppException;
use YWatchman\LaravelEPP\Exceptions\NotImplementedException;

abstract class Transformer
{

    /** @var Transformable */
    protected $transformable;

    /** @var array */
    protected $transformed;

    /**
     * Transformer constructor.
     *
     * @param Transformable $transformable
     */
    public function __construct(Transformable $transformable)
    {
        $this->transformable = $transformable;
    }

    /**
     * @throws EppException
     */
    public function toArray()
    {
        throw new NotImplementedException;
    }

    /**
     * @throws EppException
     */
    protected function transform()
    {
        throw new NotImplementedException;
    }
}
