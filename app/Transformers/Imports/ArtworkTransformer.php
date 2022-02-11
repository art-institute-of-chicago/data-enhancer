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
        'last_updated' => 'string',
    ];

    public function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'dimension_display' => fn (Datum $datum) => $datum->dimensions,
            'medium_display' => null,
            'source_updated_at' => fn (Datum $datum) => $datum->last_updated,
        ];
    }
}
