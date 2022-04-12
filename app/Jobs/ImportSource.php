<?php

namespace App\Jobs;

use LogicException;
use App\Library\SourceConsumer;

class ImportSource extends AbstractJob
{
    public function __construct(
        private string $sourceName,
        private ?string $resourceName,
        private bool $isFull,
        private ?string $since,
        private ?int $maxPages,
    ) {
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
                    $this->isFull,
                    $this->since,
                    $this->maxPages,
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
        $resourceConfig = SourceConsumer::getResourceConfig($this->sourceName, $this->resourceName, true);

        return collect([
            $this->resourceName => $resourceConfig,
        ]);
    }
}
