<?php

use YWatchman\LaravelEPP\Support\Xml\Commands\Session\HelloCommand;

test('hello command produces valid epp xml', function () {
    $xml = (string) new HelloCommand;

    expect($xml)->toContain('<hello')
        ->and($xml)->toContain('xmlns="urn:ietf:params:xml:ns:epp-1.0"');
});

test('hello command does not include sidn namespace on root epp element', function () {
    $xml = (string) new HelloCommand;

    expect($xml)->not->toContain('xmlns:sidn-ext-epp');
});

test('hello command does not include clTRID', function () {
    $xml = (string) new HelloCommand;

    // HelloCommand adds <hello> not <command>, so clTRID should not be appended
    expect($xml)->not->toContain('<clTRID');
});
