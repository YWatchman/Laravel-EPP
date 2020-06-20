# Laravel EPP
[![Latest Stable Version](https://poser.pugx.org/ywatchman/laravel-epp/v/stable)](https://packagist.org/packages/ywatchman/laravel-epp)
![StyleCI](https://github.styleci.io/repos/211557879/shield)

# Currently being totally rewritten, getting rid of metaregistrar/php-epp-client dependency.

## Installing
```bash
composer require "ywatchman/laravel-epp=dev-develop"
php artisan vendor:publish --provider="YWatchman\LaravelEPP\ServiceProvider"
```

## Setup registry in config/epp.php
Append registrar to registrars array.

```php
'sidn' => [
  'username' => env('SIDN_USERNAME'),
  'password' => env('SIDN_PASSWORD'),
  'hostname' => env('SIDN_HOSTNAME'),
  'port' => env('SIDN_PORT', 700),
  'timeout' => env('SIDN_TIMEOUT', 30),
],
```

Setup environment variables for registrar in environment file

```
SIDN_USERNAME=123456
SIDN_PASSWORD=superpass123!
SIDN_HOSTNAME=drs.domain-registry.nl
```

Start using Laravel EPP !