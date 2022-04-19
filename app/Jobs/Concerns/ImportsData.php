<?php

namespace App\Jobs\Concerns;

trait ImportsData
{
    /**
     * Specify `$dataFilterFunc` to decide which datums should be kept.
     */
    protected function importData(
        array $data,
        string $modelClass,
        string $transformerClass,
        array $transformCallArgs = [],
        callable $dataFilterFunc = null,
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

        $createIds = $incomingIds->diff($foundIds);
        $createData = $transformedData
            ->only($createIds)
            ->map(
                function ($transformedDatum) use ($modelClass) {
                    $model = new $modelClass($transformedDatum);

                    return array_intersect_key(
                        $model->getAttributes(),
                        $transformedDatum,
                    );
                }
            )
            ->values();

        $updateData = $foundModels
            ->map(
                function ($model) use (
                    $transformedData,
                    $primaryKey,
                    $transformer
                ) {
                    $transformedDatum = $transformedData
                        ->get($model->{$primaryKey});

                    $filteredDatum = $transformer
                        ->prepDirtyCheck($transformedDatum);

                    $model->fill($filteredDatum);

                    if (count($model->getDirty()) < 1) {
                        return;
                    }

                    return array_intersect_key(
                        $model->getAttributes(),
                        $transformedDatum,
                    );
                }
            )
            ->filter()
            ->values();

        $upsertData = $createData->merge($updateData);

        ($modelClass)::upsert(
            $upsertData->all(),
            $primaryKey,
            array_diff($columns, [$primaryKey])
        );

        return [
            $createData->count(),
            $updateData->count(),
            $transformedData->count(),
        ];
    }
}
