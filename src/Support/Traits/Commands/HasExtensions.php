<?php

namespace YWatchman\LaravelEPP\Support\Traits\Commands;

trait HasExtensions
{

    /** @var array */
    protected $extensions = [];

    public function getExtensions()
    {
        //
    }

    /**
     * @param array $extensions
     */
    public function setExtensions(array $extensions): void
    {
        $this->extensions = $extensions;
    }
}
