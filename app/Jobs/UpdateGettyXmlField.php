<?php

namespace App\Jobs;

use App\Enums\GettyVocab;
use App\Jobs\Concerns\TriesThrice;
use GuzzleHttp\ClientInterface;

class UpdateGettyXmlField extends AbstractJob
{
    use TriesThrice;

    public function __construct(
        private string $modelClass,
        private string $id,
        private string $xmlField,
        private string|int|null $gettyId,
        private GettyVocab $gettyVocab,
    ) {
    }

    public function handle()
    {
        $model = ($this->modelClass)::findOrFail($this->id);

        $model->{$this->xmlField} = !empty($this->gettyId)
            ? $this->getXmlContents()
            : null;

        $model->save();

        $this->debug(sprintf(
            'XML %d -> %d (%s) (%s) (%s)',
            $this->id,
            $this->gettyId,
            $this->xmlField,
            $this->gettyVocab->value,
            $this->modelClass,
        ));
    }

    private function getXmlContents()
    {
        $uri = $this->gettyVocab->getSubjectUri($this->gettyId);
        $client = app()->make(ClientInterface::class);
        $response = $client->request('GET', $uri);

        return $response->getBody()->getContents();
    }
}
