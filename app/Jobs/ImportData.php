<?php

namespace App\Jobs;

use App\Library\SourceConsumer;

class ImportData extends AbstractJob
{
    private $sourceName;

    private $resourceName;

    private $data;

    public function __construct(string $sourceName, string $resourceName, array $data)
    {
        $this->sourceName = $sourceName;
        $this->resourceName = $resourceName;
        $this->data = $data;
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
        if (count($this->data) < 1) {
            return;
        }

        $resourceConfig = SourceConsumer::getResourceConfig($this->sourceName, $this->resourceName);

        $modelClass = $resourceConfig['model'];
        $transformerClass = $resourceConfig['transformer'];

        $primaryKey = $modelClass::instance()->getKeyName();
        $transformer = app()->make($transformerClass);

        $transformedData = collect($this->data)
            ->map(fn ($datum) => $transformer->transform($datum))
            ->keyBy($primaryKey);

        $columns = array_keys($transformedData->first());
        $incomingIds = $transformedData->keys();

        $foundModels = ($modelClass)::select($columns)->findMany($incomingIds);
        $foundIds = $foundModels->pluck($primaryKey);

        $newIds = $incomingIds->diff($foundIds);
        $dirtyIds = $foundModels
            ->filter(function ($model) use ($transformedData, $primaryKey) {
                $transformedDatum = $transformedData->get($model->{$primaryKey});
                $model->fill($transformedDatum);
                return count($model->getDirty()) > 0;
            })
            ->pluck($primaryKey);

        $upsertIds = $newIds->merge($dirtyIds);
        $upsertData = $transformedData->only($upsertIds);

        ($modelClass)::upsert(
            $upsertData->all(),
            $primaryKey,
            array_diff($columns, [$primaryKey])
        );
    }
}
