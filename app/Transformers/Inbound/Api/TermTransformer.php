<?php

namespace App\Transformers\Inbound\Api;

use App\Enums\TermType;
use App\Transformers\Datum;
use App\Transformers\Inbound\AbstractTransformer;

class TermTransformer extends AbstractTransformer
{
    protected function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'subtype' => fn (Datum $datum) => TermType::fromDisplay($datum->subtype),
            'source_updated_at' => [
                'value' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
                'requires' => [
                    'updated_at',
                ],
            ]
        ];
    }
}
