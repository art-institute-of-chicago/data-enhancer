<?php

namespace App\Jobs;

use App\Library\SourceConsumer;

class ImportSource extends AbstractJob
{
    private $sourceName;

    private $resourceName;

    public function __construct(
        string $sourceName,
        ?string $resourceName,
    ) {
        $this->sourceName = $sourceName;
        $this->resourceName = $resourceName;
    }

    public function tags()
    {
        return [
            'source:' . $this->sourceName,
        ];
    }

    public function handle()
    {
        $resources = !empty($this->resourceName)
            ? $this->getOneResource()
            : $this->getAllResources();

        if ($resources->count() < 1) {
            throw new LogicException(
                "No matching resources found for source {$sourceName}"
            );
        }

        $resources
            ->each(function ($resourceConfig, $resourceName) {
                ImportResource::dispatch(
                    $this->sourceName,
                    $resourceName,
                );
            });
    }

    private function getAllResources()
    {
        $sourceConfig = SourceConsumer::getSourceConfig($this->sourceName);

        return collect($sourceConfig['resources'])
            ->filter(function ($resourceConfig, $resourceName) {
                return $resourceConfig['has_endpoint'];
            });
    }

    private function getOneResource()
    {
        $resourceConfig = SourceConsumer::getResourceConfig($this->sourceName, $this->resourceName);

        return collect([
            $this->resourceName => $resourceConfig,
        ]);
    }
}
