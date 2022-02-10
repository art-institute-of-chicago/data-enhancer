<?php

namespace App\Jobs;

use App\Library\SourceConsumer;

class DownloadPage extends AbstractJob
{
    private $sourceName;

    private $resourceName;

    private $page;

    public function __construct(string $sourceName, string $resourceName, int $page)
    {
        $this->sourceName = $sourceName;
        $this->resourceName = $resourceName;
        $this->page = $page;
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
            'page' => $this->page,
        ]);

        // TODO: Add ImportData job to batch here
    }
}
