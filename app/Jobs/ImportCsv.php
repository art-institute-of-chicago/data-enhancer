<?php

namespace App\Jobs;

use League\Csv\Reader;
use App\Library\SourceConsumer;
use App\Jobs\Concerns\ImportsData;

class ImportCsv extends AbstractJob
{
    use ImportsData;

    private $sourceName = 'csv';

    private $resourceName;

    private $csvPath;

    public function __construct(
        string $resourceName,
        string $csvPath
    ) {
        $this->resourceName = $resourceName;
        $this->csvPath = $csvPath;
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

        $csv = Reader::createFromPath($this->csvPath, 'r');
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
        }

        if (count($batch) > 0) {
            $this->importBatch($batch, $modelClass, $transformerClass);
        }
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
    }
}
