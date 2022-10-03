<?php

namespace App\Jobs\Concerns;

use App\Jobs\AbstractJob;

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

        $jobsToRun = [];

        $createIds = $incomingIds->diff($foundIds);

        $createData = $transformedData
            ->only($createIds)
            ->values()
            ->map(
                function ($transformedDatum) use (
                    $modelClass
                ) {
                    return [
                        new $modelClass($transformedDatum),
                        $transformedDatum,
                    ];
                }
            );

        $updateData = $foundModels
            ->map(
                function ($model) use (
                    $transformedData,
                    $transformer,
                ) {
                    $transformedDatum = $transformedData
                        ->get($model->getKey());

                    [$filteredDatum, $savedDatum] = $transformer
                        ->beforeDirtyCheck($transformedDatum);

                    $model->fill($filteredDatum);

                    if (count($model->getDirty()) < 1) {
                        return;
                    }

                    $model->fill($savedDatum);

                    return [
                        $model,
                        $transformedDatum,
                    ];
                }
            )
            ->filter()
            ->values();

        $upsertData = $createData
            ->merge($updateData)
            ->map(
                function ($item) use (
                    $transformer,
                    &$jobsToRun,
                ) {
                    [$model, $transformedDatum] = $item;

                    $dirtyAttributes = array_keys($model->getDirty());
                    $attributes = $model->getAttributes();

                    foreach ($dirtyAttributes as $attribute) {
                        foreach ($transformer->getWatchers($attribute) as $watcher) {
                            $watcherReturn = $watcher(
                                modelClass: get_class($model),
                                id: $model->getKey(),
                                oldValue: $model->getRawOriginal($attribute),
                                newValue: $attributes[$attribute],
                            );

                            if ($watcherReturn instanceof AbstractJob) {
                                $jobsToRun[] = $watcherReturn;
                            }
                        }
                    }

                    return array_intersect_key(
                        $attributes,
                        $transformedDatum,
                    );
                }
            );

        ($modelClass)::upsert(
            $upsertData->all(),
            $primaryKey,
            array_diff($columns, [$primaryKey])
        );

        if ($batch = $this->batch()) {
            $batch->options['queue'] = 'high';
            $batch->add($jobsToRun);
        } else {
            foreach ($jobsToRun as $job) {
                dispatch($job->onQueue('high'));
            }
        }

        return [
            $createData->count(),
            $updateData->count(),
            $transformedData->count(),
        ];
    }
}
