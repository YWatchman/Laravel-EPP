<?php

namespace YWatchman\LaravelEPP\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use YWatchman\LaravelEPP\ServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('epp.debug', false);
    }
}
