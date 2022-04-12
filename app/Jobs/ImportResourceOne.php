<?php

namespace App\Jobs;

use App\Library\SourceConsumer;
use App\Jobs\Concerns\ImportsData;

class ImportResourceOne extends AbstractJob
{
    use ImportsData;

    public function __construct(
        private string $sourceName,
        private string $resourceName,
        private string|int $resourceId,
    ) {
    }

    public function tags()
    {
        return [
            'source:' . $this->sourceName,
            'resource:' . $this->resourceName,
            'id:' . $this->resourceId,
        ];
    }

    public function handle()
    {
        $resourceConfig = SourceConsumer::getResourceConfig($this->sourceName, $this->resourceName, true);

        $modelClass = $resourceConfig['model'];
        $transformerClass = $resourceConfig['transformer'];

        $transformer = app()->make($transformerClass);

        $fields = $transformer->getRequiredFields();
        $limit = SourceConsumer::getLimit($this->sourceName, $this->resourceName);

        $results = SourceConsumer::getOne(
            $this->sourceName,
            $this->resourceName,
            $this->resourceId,
            [
                'fields' => $fields,
            ],
        );

        $sourceUpdatedAtField = ($transformerClass)::getSourceUpdatedAtField();

        [
            $createdCount,
            $updatedCount,
            $importedCount,
        ] = $this->importData(
            [
                $results->data,
            ],
            $modelClass,
            $transformerClass,
            fieldFilterFunc: function ($transformedDatum) use ($sourceUpdatedAtField) {
                unset($transformedDatum[$sourceUpdatedAtField]);

                return $transformedDatum;
            },
        );

        $this->debug(sprintf('IMP %s # %s',
            $this->resourceName,
            $this->resourceId,
        ));
    }
}
