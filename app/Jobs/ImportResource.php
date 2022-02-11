<?php

namespace App\Jobs;

use App\Library\SourceConsumer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

class ImportResource extends AbstractJob
{
    private $sourceName;

    private $resourceName;

    public function __construct(string $sourceName, string $resourceName)
    {
        $this->sourceName = $sourceName;
        $this->resourceName = $resourceName;
    }

    public function tags()
    {
        return [
            'source:' . $this->sourceName,
            'resource:' . $this->resourceName,
        ];
    }

    public function handle()
    {
        $totalPages = SourceConsumer::getTotalPages($this->sourceName, $this->resourceName);

        if (config('aic.imports.debug')) {
            $totalPages = min($totalPages, 2);
        }

        $jobs = Collection::times($totalPages, function ($currentPage) {
            return new DownloadPage(
                $this->sourceName,
                $this->resourceName,
                $currentPage,
            );
        });

        Bus::batch($jobs)
            ->name($this->sourceName . '.' . $this->resourceName)
            ->dispatch();
    }
}
