<?php

namespace App\Transformers\Inbound\Api;

use App\Transformers\Datum;
use App\Transformers\Inbound\AbstractTransformer;

class PlaceTransformer extends AbstractTransformer
{
    protected function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'latitude' => null,
            'longitude' => null,
            'source_updated_at' => [
                'value' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
                'requires' => [
                    'updated_at',
                ],
            ]
        ];
    }
}
