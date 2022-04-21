<?php

namespace App\Transformers\Inbound\Csv\Concerns;

use App\Enums\GettyVocab;
use App\Jobs\UpdateGettyXmlField;

trait CanUpdateGettyXmlFields
{
    protected function updateGettyXmlField(
        string $xmlField,
        GettyVocab $gettyVocab,
    ): callable {
        return function (
            string $modelClass,
            mixed $id,
            ?string $oldValue,
            ?string $newValue,
        ) use (
            $xmlField,
            $gettyVocab,
        ) {
            return new UpdateGettyXmlField(
                modelClass: $modelClass,
                id: $id,
                xmlField: $xmlField,
                gettyId: $newValue,
                gettyVocab: $gettyVocab,
            );
        };
    }
}
