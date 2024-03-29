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
            'dimension_display' => 'dimensions',
            'medium_display' => null,
            'source_updated_at' => [
                'value' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
                'requires' => [
                    'updated_at',
                ],
            ]
        ];
    }
}
