<?php

namespace YWatchman\LaravelEPP\Support;

use Illuminate\Support\Str;
use Pdp\Cache;
use Pdp\CurlHttpClient;
use Pdp\Manager;
use Psr\SimpleCache\InvalidArgumentException;
use YWatchman\LaravelEPP\Models\Domain;

class DomainParser
{
    /**
     * Parse the domain name to a model. Returns null when the domain name can't be parsed.
     *
     * @param string $domainName
     *
     * @return Domain|null
     */
    public static function parse(string $domainName): ?Domain
    {
        $manager = new Manager(new Cache(), new CurlHttpClient());

        try {
            $domainData = $manager
                ->getRules()
                ->resolve($domainName);
        } catch (InvalidArgumentException $exception) {
            return null;
        }

        if (is_null($domainData->getPublicSuffix())) {
            return null;
        }

        $domain = new Domain();
        $domain->tld = $domainData->getPublicSuffix();
        $domain->sld = Str::before($domainData->getRegistrableDomain(), sprintf('.%s', $domain->tld));
        $domain->name = sprintf('%s.%s', $domain->sld, $domain->tld);

        return $domain;
    }
}
