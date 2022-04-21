<?php

namespace App\Jobs;

use App\Library\SourceConsumer;
use App\Jobs\Concerns\TriesThrice;

class DownloadPage extends AbstractJob
{
    use TriesThrice;

    public function __construct(
        private string $sourceName,
        private string $resourceName,
        private int $page,
        private bool $isFull,
        private ?string $since,
    ) {
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
        $resourceConfig = SourceConsumer::getResourceConfig($this->sourceName, $this->resourceName, true);
        $transformerClass = $resourceConfig['transformer'];

        $transformer = app()->make($transformerClass);

        $fields = $transformer->getRequiredFields();
        $limit = SourceConsumer::getLimit($this->sourceName, $this->resourceName);

        $results = SourceConsumer::getMany($this->sourceName, $this->resourceName, [
            'fields' => $fields,
            'limit' => $limit,
            'page' => $this->page,
        ]);

        $this->debug(sprintf('D/L %s, p. %d',
            $this->resourceName,
            $this->page
        ));

        $batch = $this->batch();
        $batch->options['queue'] = 'high';
        $batch->add([
            new ImportPage(
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
