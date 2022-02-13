<?php

namespace App\Jobs;

use LogicException;
use Carbon\Carbon;
use App\Library\SourceConsumer;

class ImportData extends AbstractJob
{
    private $sourceName;

    private $resourceName;

    private $data;

    private $isFull;

    private $since;

    private $page;

    public function __construct(
        string $sourceName,
        string $resourceName,
        array $data,
        bool $isFull,
        ?string $since,
        int $page
    ) {
        $this->sourceName = $sourceName;
        $this->resourceName = $resourceName;
        $this->data = $data;
        $this->isFull = $isFull;
        $this->since = $since;
        $this->page = $page;

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
        $rawDataCount = count($this->data);

        if ($rawDataCount < 1) {
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

        $sourceUpdatedAtField = ($transformerClass)::getSourceUpdatedAtField();

        if (!$this->isFull) {
            $sinceCarbon = new Carbon($this->since);

            $transformedData = $transformedData->filter(
                fn ($datum) => (new Carbon($datum[$sourceUpdatedAtField]))->gte($sinceCarbon)
            );

            if ($transformedData->count() < 1) {
                return;
            }
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
                    $sourceUpdatedAtField
                ) {
                    $transformedDatum = $transformedData
                        ->get($model->{$primaryKey});

                    unset($transformedDatum[$sourceUpdatedAtField]);

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

        if (!$this->isFull && $rawDataCount === $transformedData->count()) {
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
