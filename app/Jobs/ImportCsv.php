<?php

namespace App\Jobs;

use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batch;
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

    private $jobsToRun = [];

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

        $includeFields = $csv->getHeader();

        $limit = SourceConsumer::getLimit($this->sourceName, $this->resourceName);
        $batch = [];

        foreach ($csv->getRecords() as $record) {
            $batch[] = $record;

            if (count($batch) === $limit) {
                $this->importBatch($batch, $modelClass, $transformerClass, $includeFields);
                $batch = [];
            }

            $this->totalCount += 1;
        }

        if (count($batch) > 0) {
            $this->importBatch($batch, $modelClass, $transformerClass, $includeFields);
        }

        Storage::delete($this->csvPath);

        $this->runWatcherJobs();

        $this->alertSlack();
    }

    private function importBatch($batch, $modelClass, $transformerClass, $includeFields)
    {
        [
            $createdCount,
            $updatedCount,
            $importedCount,
            $jobsToRun,
        ] = $this->importData(
            $batch,
            $modelClass,
            $transformerClass,
            transformCallArgs: [
                'includeFields' => $includeFields,
            ],
            dispatchJobs: false,
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
        $this->jobsToRun = array_merge($this->jobsToRun, $jobsToRun);
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

    /**
     * We might not need this because `SlackAlert` is a queued job, too.
     */
    private function runWatcherJobs()
    {
        if (empty($this->jobsToRun)) {
            return;
        }

        SlackAlert::message(sprintf(
            'Performing post-processing on imported data... [%s]',
            app('env'),
        ));

        Bus::batch($this->jobsToRun)
            ->onQueue('high')
            ->finally(function (Batch $batch) {
                SlackAlert::message(sprintf(
                    'Post-processing complete! [%s]',
                    app('env'),
                ));
            })
            ->dispatch();
    }
}
