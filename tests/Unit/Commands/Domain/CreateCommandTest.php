<?php

use YWatchman\LaravelEPP\Models\Contact;
use YWatchman\LaravelEPP\Models\Domain;
use YWatchman\LaravelEPP\Models\Nameserver;
use YWatchman\LaravelEPP\Support\Xml\Commands\Domain\CreateCommand;

function makeDomainCommand(?string $transactionId = null): CreateCommand
{
    $domain = new Domain(['sld' => 'example', 'tld' => 'nl']);
    $contact = new Contact(['handle' => 'TEST001']);
    $ns = new Nameserver(['name' => 'ns1.example.nl', 'address' => '1.2.3.4-v4']);

    return new CreateCommand($domain, $contact, $contact, $contact, [$ns], [], $transactionId);
}

test('domain create command contains clTRID', function () {
    $xml = (string) makeDomainCommand();

    expect($xml)->toContain('<clTRID');
});

test('domain create command uses provided transaction id', function () {
    $xml = (string) makeDomainCommand('my-custom-txid');

    expect($xml)->toContain('my-custom-txid');
});

test('domain create command auto-generates clTRID when none provided', function () {
    $xml1 = (string) makeDomainCommand();
    $xml2 = (string) makeDomainCommand();

    // Both have clTRID, and they are different (auto-generated unique IDs)
    expect($xml1)->toContain('<clTRID')
        ->and($xml2)->toContain('<clTRID')
        ->and($xml1)->not->toBe($xml2);
});

test('domain create command does not include sidn namespace on root epp element', function () {
    $xml = (string) makeDomainCommand();

    $doc = new DOMDocument;
    $doc->loadXML($xml);
    $epp = $doc->documentElement;

    expect($epp->hasAttribute('xmlns:sidn-ext-epp'))->toBeFalse();
});

test('domain create command includes domain create node', function () {
    $xml = (string) makeDomainCommand();

    expect($xml)->toContain('domain:create')
        ->and($xml)->toContain('example.nl');
});
