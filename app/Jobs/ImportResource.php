<?php

namespace App\Jobs;

use LogicException;
use Carbon\Carbon;
use App\Library\SourceConsumer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

class ImportResource extends AbstractJob
{
    public function __construct(
        private string $sourceName,
        private string $resourceName,
        private bool $isFull,
        private ?string $since,
        private ?int $maxPages,
    ) {
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

        if ($this->isFull && !is_null($this->maxPages)) {
            $pages = min($pages, $this->maxPages);
        }

        $this->debug(sprintf('IMP %s %s',
            $this->resourceName,
            $this->isFull
                ? 'full'
                : 'since ' . Carbon::parse($this->since)->toIso8601String()
        ));

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
