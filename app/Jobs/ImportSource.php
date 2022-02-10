<?php

namespace App\Jobs;

use App\Library\SourceConsumer;
use Illuminate\Support\Facades\Bus;

class ImportSource extends AbstractJob
{
    private $sourceName;

    public function __construct(string $sourceName)
    {
        $this->sourceName = $sourceName;
    }

    public function handle()
    {
        $sourceConfig = SourceConsumer::getSourceConfig($this->sourceName);

        $jobs = collect($sourceConfig['resources'])
            ->filter(function ($resourceConfig, $resourceName) {
                return $resourceConfig['has_endpoint'];
            })
            ->map(function ($resourceConfig, $resourceName) {
                return new ImportResource(
                    $this->sourceName,
                    $resourceName,
                );
            })
            ->values()
            ->all();

        Bus::batch($jobs)
            ->name($this->sourceName)
            ->dispatch();
    }
}
