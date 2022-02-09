<?php

namespace App\Jobs;

use App\Library\SourceConsumer;
use Illuminate\Support\Collection;

class ImportResource extends AbstractJob
{
    private $sourceName;

    private $resourceName;

    public function __construct(string $sourceName, string $resourceName)
    {
        $this->sourceName = $sourceName;
        $this->resourceName = $resourceName;
    }

    public function handle()
    {
        $totalPages = SourceConsumer::getTotalPages($this->sourceName, $this->resourceName);

        $this->batch()->add(Collection::times($totalPages, function ($currentPage) {
            return new DownloadPage(
                $this->sourceName,
                $this->resourceName,
                $currentPage,
            );
        }));
    }
}
