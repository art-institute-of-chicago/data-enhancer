<?php

namespace App\Jobs;

use LogicException;
use App\Library\SourceConsumer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

class ImportResource extends AbstractJob
{
    private $sourceName;

    private $resourceName;

    private $isFull;

    private $since;

    public function __construct(
        string $sourceName,
        string $resourceName,
        bool $isFull,
        ?string $since
    ) {
        $this->sourceName = $sourceName;
        $this->resourceName = $resourceName;
        $this->isFull = $isFull;
        $this->since = $since;
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
        $pages = $this->isFull
            ? SourceConsumer::getTotalPages($this->sourceName, $this->resourceName)
            : 1; // for partial imports, queue one page at a time

        if (!$this->isFull && empty($this->since)) {
            throw new LogicException("Parameter 'since' cannot be empty for partial imports");
        }


        $jobs = Collection::times($pages, function ($currentPage) {
            return new DownloadPage(
                $this->sourceName,
                $this->resourceName,
                $currentPage,
                $this->isFull,
                $this->since,
            );
        });

        Bus::batch($jobs)
            ->name($this->sourceName . '.' . $this->resourceName)
            ->dispatch();
    }
}
