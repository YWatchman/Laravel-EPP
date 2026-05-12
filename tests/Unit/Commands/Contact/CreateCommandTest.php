<?php

use YWatchman\LaravelEPP\Models\Contact;
use YWatchman\LaravelEPP\Support\Xml\Commands\Contact\CreateCommand;

function makeContactCommand(): CreateCommand
{
    $contact = new Contact([
        'handle' => 'TEST001',
        'name' => 'Test User',
        'street' => 'Main Street',
        'number' => '1',
        'city' => 'Amsterdam',
        'postal' => '1000AA',
        'country' => 'NL',
        'phone' => '+31.201234567',
        'email' => 'test@example.nl',
        'legalForm' => 'PERSOON',
    ]);

    return new CreateCommand($contact);
}

test('contact create command includes clTRID', function () {
    $xml = (string) makeContactCommand();

    expect($xml)->toContain('<clTRID');
});

test('contact create command declares sidn namespace on extension element not root', function () {
    $xml = (string) makeContactCommand();

    $doc = new DOMDocument;
    $doc->loadXML($xml);

    // Root <epp> must not carry the sidn namespace declaration
    $epp = $doc->documentElement;
    expect($epp->hasAttribute('xmlns:sidn-ext-epp'))->toBeFalse()
        ->and($xml)->toContain('sidn-ext-epp:ext')
        ->and($xml)->toContain('xmlns:sidn-ext-epp="https://rxsd.domain-registry.nl/sidn-ext-epp-1.0"');

    // The namespace declaration must appear somewhere inside (on the ext element)
});

test('contact create command includes legalForm in sidn extension', function () {
    $xml = (string) makeContactCommand();

    expect($xml)->toContain('sidn-ext-epp:legalForm')
        ->and($xml)->toContain('PERSOON');
});

test('contact create command produces valid xml', function () {
    $xml = (string) makeContactCommand();

    $doc = new DOMDocument;
    $result = $doc->loadXML($xml);

    expect($result)->toBeTrue();
});
