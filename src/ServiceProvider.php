<?php

namespace YWatchman\LaravelEPP;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap application events.
     */
    public function boot(): void
    {
        $this->publishConfig();
    }

    public function register(): void
    {
        $this->app->alias(Epp::class, 'Epp');
    }

    private function publishConfig(): void
    {
        $path = $this->getConfigPath();
        $this->publishes([$path => config_path('epp.php')], 'config');
    }

    private function getConfigPath(): string
    {
        return __DIR__.'/../config/epp.php';
    }
}
