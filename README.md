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

## Requirements

- PHP 8.1+
- Laravel 10 or 11

## Usage

### Starting a session

```php
use YWatchman\LaravelEPP\Epp;

$epp = new Epp('sidn');
$epp->start();
$epp->login();

// ... perform commands ...

$epp->logout();
```

### Domain commands

**Check availability**
```php
use YWatchman\LaravelEPP\Models\Domain;
use YWatchman\LaravelEPP\Support\Xml\Commands\Domain\CheckCommand;
use YWatchman\LaravelEPP\Responses\Domain\CheckResponse;

$domains = [new Domain(['name' => 'example.nl'])];
$command = new CheckCommand($domains);
$response = new CheckResponse($epp->sendRequest((string) $command));

if ($response->isSucceeded()) {
    // $response->domainExists('example.nl')
}
```

**Create a domain**
```php
use YWatchman\LaravelEPP\Support\Xml\Commands\Domain\CreateCommand;
use YWatchman\LaravelEPP\Responses\Domain\CreateResponse;

$command = new CreateCommand($domain, $contacts, $nameservers);
$response = new CreateResponse($epp->sendRequest((string) $command));
```

**Transfer a domain**
```php
use YWatchman\LaravelEPP\Support\Xml\Commands\Domain\TransferCommand;
use YWatchman\LaravelEPP\Responses\Domain\TransferResponse;

$command = new TransferCommand($domain, $authToken);
$response = new TransferResponse($epp->sendRequest((string) $command));
```

### Contact commands

**Create a contact**
```php
use YWatchman\LaravelEPP\Models\Contact;
use YWatchman\LaravelEPP\Support\Xml\Commands\Contact\CreateCommand;
use YWatchman\LaravelEPP\Responses\Contact\CreateResponse;

$contact = new Contact([
    'handle'    => 'MYHANDLE001',
    'name'      => 'Jane Doe',
    'street'    => 'Main Street',
    'number'    => '1',
    'city'      => 'Amsterdam',
    'postal'    => '1000AA',
    'country'   => 'NL',
    'phone'     => '+31.201234567',
    'email'     => 'jane@example.nl',
    'legalForm' => 'PERSON', // PERSON, OTHER, or SIDN Dutch codes directly
]);

$command = new CreateCommand($contact);
$response = new CreateResponse($epp->sendRequest((string) $command));
```

### Host commands

**Create a nameserver**
```php
use YWatchman\LaravelEPP\Models\Nameserver;
use YWatchman\LaravelEPP\Support\Xml\Commands\Host\CreateCommand;
use YWatchman\LaravelEPP\Responses\Host\CreateResponse;

$nameserver = new Nameserver(['name' => 'ns1.example.nl', 'address' => '1.2.3.4']);
$command = new CreateCommand($nameserver);
$response = new CreateResponse($epp->sendRequest((string) $command));
```

### Checking a response

All response classes extend `Response` and share a common interface:

```php
$response->isSucceeded();         // bool
$response->getCode();             // int  (e.g. 1000, 2303)
$response->getMessage();          // ?string
$response->getServerTransaction(); // string
$response->getClientTransaction(); // ?string
```

## SIDN-specific notes

- Legal form values `PERSON` and `OTHER` are automatically mapped to the Dutch SIDN codes `PERSOON` and `ANDERS`. All other values are passed through as-is.
- The SIDN EPP extension namespace is `https://rxsd.domain-registry.nl/sidn-ext-epp-1.0`.

## Debugging

Set `EPP_DEBUG=true` in your `.env` to dump raw XML frames to stdout during socket I/O.

## Running tests

```bash
./vendor/bin/pest
```