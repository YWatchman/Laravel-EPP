<?php

namespace YWatchman\LaravelEPP\Responses\Registries\Sidn\Domain;

use YWatchman\LaravelEPP\Responses\Domain\CreateResponse as CommonCreateResponse;
use YWatchman\LaravelEPP\Support\Extensions\Sidn\SidnEppExtension;

class CreateResponse extends CommonCreateResponse
{
    /** @var SidnEppExtension[] */
    protected $sidnMessages = [];

    /**
     * CreateResponse constructor.
     *
     * @param string $rawXml
     */
    public function __construct(string $rawXml)
    {
        parent::__construct($rawXml);

        if (!$this->isSucceeded()) {
            $this
                ->response
                ->filter('response > extension > ext > response > msg')
                ->each(function ($extension) {
                    $this->sidnMessages[] = new SidnEppExtension(
                        $extension
                    );
                });
        }
    }

    /**
     * @return SidnEppExtension[]
     */
    public function getSidnMessages(): array
    {
        return $this->sidnMessages;
    }
}
