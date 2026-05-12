<?php

namespace YWatchman\LaravelEPP\Support\Traits\Commands;

trait HasExtensions
{
    protected array $extensions = [];

    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function setExtensions(array $extensions): void
    {
        $this->extensions = $extensions;
    }
}
