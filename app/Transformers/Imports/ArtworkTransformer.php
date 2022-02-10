<?php

namespace App\Transformers\Imports;

use App\Transformers\Datum;

class ArtworkTransformer extends AbstractTransformer
{
    protected $requiredFields = [
        'id' => 'integer',
        'title' => 'string',
        'dimensions' => 'string|null',
        'medium_display' => 'string|null',
    ];

    public function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'dimension_display' => fn (Datum $datum) => $datum->dimensions,
            'medium_display' => null,
        ];
    }
}
