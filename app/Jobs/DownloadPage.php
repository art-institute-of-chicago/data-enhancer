<?php

namespace App\Jobs;

use App\Library\SourceConsumer;

class DownloadPage extends AbstractJob
{
    public $tries = 3;

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

    public function backoff()
    {
        return [
            random_int(1, 2),
            random_int(3, 7),
            random_int(8, 12),
        ];
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

        $this->debug(sprintf('D/L %s, p. %d',
            $this->resourceName,
            $this->page
        ));

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
