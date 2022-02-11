<?php

namespace App\Jobs;

use App\Library\SourceConsumer;

class ImportSource extends AbstractJob
{
    private $sourceName;

    public function __construct(string $sourceName)
    {
        $this->sourceName = $sourceName;
    }

    public function tags()
    {
        return [
            'source:' . $this->sourceName,
        ];
    }

    public function handle()
    {
        $sourceConfig = SourceConsumer::getSourceConfig($this->sourceName);

        $jobs = collect($sourceConfig['resources'])
            ->filter(function ($resourceConfig, $resourceName) {
                return $resourceConfig['has_endpoint'];
            })
            ->each(function ($resourceConfig, $resourceName) {
                ImportResource::dispatch(
                    $this->sourceName,
                    $resourceName,
                );
            });
    }
}
