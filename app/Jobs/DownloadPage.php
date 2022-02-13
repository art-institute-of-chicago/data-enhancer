<?php

namespace App\Jobs;

use App\Library\SourceConsumer;

class DownloadPage extends AbstractJob
{
    private $sourceName;

    private $resourceName;

    private $page;

    private $isFull;

    private $since;

    public function __construct(
        string $sourceName,
        string $resourceName,
        int $page,
        bool $isFull,
        ?string $since
    ) {
        $this->sourceName = $sourceName;
        $this->resourceName = $resourceName;
        $this->page = $page;
        $this->isFull = $isFull;
        $this->since = $since;
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
        $resourceConfig = SourceConsumer::getResourceConfig($this->sourceName, $this->resourceName);
        $transformerClass = $resourceConfig['transformer'];

        $transformer = app()->make($transformerClass);

        $fields = $transformer->getRequiredFields();
        $limit = SourceConsumer::getLimit($this->sourceName, $this->resourceName);

        $results = SourceConsumer::get($this->sourceName, $this->resourceName, [
            'fields' => $fields,
            'limit' => $limit,
            'page' => $this->page,
        ]);

        $this->batch()->add([
            new ImportData(
                $this->sourceName,
                $this->resourceName,
                $results->data,
                $this->isFull,
                $this->since,
                $this->page,
            )
        ]);
    }
}
