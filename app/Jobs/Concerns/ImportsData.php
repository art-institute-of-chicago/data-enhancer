<?php

namespace App\Jobs\Concerns;

trait ImportsData
{
    /**
     * Specify `$dataFilterFunc` to decide which datums should be kept.
     * Specify `$fieldFilterFunc` to decide which fields should be kept.
     */
    protected function importData(
        array $data,
        string $modelClass,
        string $transformerClass,
        array $transformCallArgs = [],
        callable $dataFilterFunc = null,
        callable $fieldFilterFunc = null,
    ) {
        $primaryKey = $modelClass::instance()->getKeyName();
        $transformer = app()->make($transformerClass);

        $transformedData = collect($data)
            ->map(fn ($datum) => $transformer->transform($datum, ...$transformCallArgs))
            ->keyBy($primaryKey);

        if ($dataFilterFunc) {
            $transformedData = $dataFilterFunc($transformedData);
        }

        if ($transformedData->count() < 1) {
            return [0, 0, 0];
        }

        $columns = array_keys($transformedData->first());
        $incomingIds = $transformedData->keys();

        $foundModels = ($modelClass)::select($columns)->findMany($incomingIds);
        $foundIds = $foundModels->pluck($primaryKey);

        $newIds = $incomingIds->diff($foundIds);
        $dirtyIds = $foundModels
            ->filter(
                function ($model) use (
                    $transformedData,
                    $primaryKey,
                    $fieldFilterFunc
                ) {
                    $transformedDatum = $transformedData
                        ->get($model->{$primaryKey});

                    if ($fieldFilterFunc) {
                        $transformedDatum = $fieldFilterFunc($transformedDatum);
                    }

                    $model->fill($transformedDatum);

                    return count($model->getDirty()) > 0;
                }
            )
            ->pluck($primaryKey);

        $upsertIds = $newIds->merge($dirtyIds);
        $upsertData = $transformedData->only($upsertIds);

        ($modelClass)::upsert(
            $upsertData->all(),
            $primaryKey,
            array_diff($columns, [$primaryKey])
        );

        return [
            $newIds->count(),
            $dirtyIds->count(),
            $transformedData->count(),
        ];
    }
}
