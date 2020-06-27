<?php

namespace YWatchman\LaravelEPP;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap application events.
     */
    public function boot()
    {
        $this->publishConfig();
    }

    public function register()
    {
        $this->app->alias(Epp::class, 'Epp');
    }

    private function publishConfig()
    {
        $path = $this->getConfigPath();
        $this->publishes([$path => config_path('epp.php')], 'config');
    }

    private function getConfigPath()
    {
        return __DIR__.'/../config/epp.php';
    }
}
