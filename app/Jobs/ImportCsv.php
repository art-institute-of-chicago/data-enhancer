<?php

namespace App\Jobs;

use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;
use App\Library\SourceConsumer;
use App\Jobs\Concerns\ImportsData;
use Spatie\SlackAlerts\Facades\SlackAlert;

class ImportCsv extends AbstractJob
{
    use ImportsData;

    private $sourceName = 'csv';

    private $createdCount = 0;

    private $updatedCount = 0;

    private $ignoredCount = 0;

    private $totalCount = 0;

    public function __construct(
        private string $resourceName,
        private string $csvPath,
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
        $this->debug(sprintf(
            'CSV %s, %s',
            $this->resourceName,
            $this->csvPath
        ));

        $csv = Reader::createFromPath(Storage::path($this->csvPath), 'r');
        $csv->setHeaderOffset(0);

        $resourceConfig = SourceConsumer::getResourceConfig($this->sourceName, $this->resourceName);

        $modelClass = $resourceConfig['model'];
        $transformerClass = $resourceConfig['transformer'];

        $limit = SourceConsumer::getLimit($this->sourceName, $this->resourceName);
        $batch = [];

        foreach ($csv->getRecords() as $record) {
            $batch[] = $record;

            if (count($batch) === $limit) {
                $this->importBatch($batch, $modelClass, $transformerClass);
                $batch = [];
            }

            $this->totalCount += 1;
        }

        if (count($batch) > 0) {
            $this->importBatch($batch, $modelClass, $transformerClass);
        }

        Storage::delete($this->csvPath);

        $this->alertSlack();
    }

    private function importBatch($batch, $modelClass, $transformerClass)
    {
        [
            $createdCount,
            $updatedCount,
            $importedCount,
        ] = $this->importData(
            $batch,
            $modelClass,
            $transformerClass,
        );

        $this->debug(sprintf(
            'IMP %s, %s: %d, %d, %d, %d',
            $this->resourceName,
            $this->csvPath,
            $createdCount,
            $updatedCount,
            $importedCount,
            count($batch),
        ));

        $this->createdCount += $createdCount;
        $this->updatedCount += $updatedCount;
        $this->ignoredCount += count($batch) - $createdCount - $updatedCount;
    }

    private function alertSlack()
    {
        if (app()->environment('testing')) {
            return;
        }

        SlackAlert::message(sprintf(
            'Enhancer: imported CSV with %d %s (C: %s, U: %s, I: %s) [%s]',
            $this->totalCount,
            $this->resourceName,
            $this->createdCount,
            $this->updatedCount,
            $this->ignoredCount,
            app('env'),
        ));
    }
}
