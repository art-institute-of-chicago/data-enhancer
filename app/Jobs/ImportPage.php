<?php

namespace App\Jobs;

use LogicException;
use Carbon\Carbon;
use App\Library\SourceConsumer;
use App\Jobs\Concerns\ImportsData;

class ImportPage extends AbstractJob
{
    use ImportsData;

    public function __construct(
        private string $sourceName,
        private string $resourceName,
        private array $data,
        private bool $isFull,
        private ?string $since,
        private int $page
    ) {
        if (!$this->isFull && empty($this->since)) {
            throw new LogicException("Parameter 'since' cannot be empty for partial imports");
        }
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
        $inboundCount = count($this->data);

        if ($inboundCount < 1) {
            return;
        }

        $resourceConfig = SourceConsumer::getResourceConfig($this->sourceName, $this->resourceName);

        $modelClass = $resourceConfig['model'];
        $transformerClass = $resourceConfig['transformer'];

        $sourceUpdatedAtField = ($transformerClass)::getSourceUpdatedAtField();

        [
            $createdCount,
            $updatedCount,
            $importedCount,
        ] = $this->importData(
            $this->data,
            $modelClass,
            $transformerClass,
            function ($transformedData) use ($sourceUpdatedAtField) {
                if (!$this->isFull) {
                    $sinceCarbon = new Carbon($this->since);

                    $transformedData = $transformedData->filter(
                        fn ($datum) => (new Carbon($datum[$sourceUpdatedAtField]))->gte($sinceCarbon)
                    );
                }

                return $transformedData;
            },
            function ($transformedDatum) use ($sourceUpdatedAtField) {
                unset($transformedDatum[$sourceUpdatedAtField]);

                return $transformedDatum;
            },
        );

        $this->debug(sprintf(
            'IMP %s, p. %d: %d, %d, %d, %d',
            $this->resourceName,
            $this->page,
            $createdCount,
            $updatedCount,
            $importedCount,
            $inboundCount,
        ));

        if (!$this->isFull && $inboundCount === $importedCount) {
            $this->batch()->add([
                new DownloadPage(
                    $this->sourceName,
                    $this->resourceName,
                    $this->page + 1,
                    $this->isFull,
                    $this->since,
                )
            ]);
        }
    }
}
