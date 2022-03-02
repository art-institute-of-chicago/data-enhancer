<?php

namespace App\Jobs;

use LogicException;
use App\Library\SourceConsumer;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImportSource extends AbstractJob implements ShouldBeUnique
{
    private $sourceName;

    private $resourceName;

    private $isFull;

    private $since;

    public function __construct(
        string $sourceName,
        ?string $resourceName,
        bool $isFull,
        ?string $since
    ) {
        $this->sourceName = $sourceName;
        $this->resourceName = $resourceName;
        $this->isFull = $isFull;
        $this->since = $since;
    }

    public function uniqueId()
    {
        return $this->sourceName;
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
