<?php

namespace App\Transformers\Inbound\Api;

use App\Transformers\Datum;
use App\Transformers\Inbound\AbstractTransformer;

class ArtworkTransformer extends AbstractTransformer
{
    protected function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'dimension_display' => [
                'value' => fn (Datum $datum) => $datum->dimensions,
                'requires' => [
                    'dimensions',
                ],
            ],
            'medium_display' => null,
            'source_updated_at' => [
                'value' => fn (Datum $datum) => $this->getDateTime($datum->last_updated),
                'requires' => [
                    'last_updated',
                ],
            ]
        ];
    }
}
