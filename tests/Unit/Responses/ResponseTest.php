<?php

use YWatchman\LaravelEPP\Responses\Response;

$successXml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1000">
      <msg>Command completed successfully</msg>
    </result>
    <trID>
      <clTRID>epp-abc123</clTRID>
      <svTRID>server-xyz789</svTRID>
    </trID>
  </response>
</epp>
XML;

$failureXml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="2303">
      <msg>Object does not exist</msg>
    </result>
    <trID>
      <svTRID>server-fail001</svTRID>
    </trID>
  </response>
</epp>
XML;

test('success response is parsed correctly', function () use ($successXml) {
    $response = new Response($successXml);

    expect($response->isSucceeded())->toBeTrue()
        ->and($response->getCode())->toBe(1000)
        ->and($response->getMessage())->toBe('Command completed successfully')
        ->and($response->getServerTransaction())->toBe('server-xyz789')
        ->and($response->getClientTransaction())->toBe('epp-abc123');
});

test('failure response is parsed correctly', function () use ($failureXml) {
    $response = new Response($failureXml);

    expect($response->isSucceeded())->toBeFalse()
        ->and($response->getCode())->toBe(2303)
        ->and($response->getMessage())->toBe('Object does not exist')
        ->and($response->getServerTransaction())->toBe('server-fail001');
});

test('clTRID is null when absent from server response', function () use ($failureXml) {
    $response = new Response($failureXml);

    expect($response->getClientTransaction())->toBeNull();
});
