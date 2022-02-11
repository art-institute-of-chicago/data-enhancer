<?php

namespace App\Transformers\Imports;

use App\Transformers\Datum;

class ArtworkTypeTransformer extends AbstractTransformer
{
    protected $requiredFields = [
        'id' => 'integer',
        'title' => 'string',
        'last_updated' => 'string',
    ];

    public function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'source_updated_at' => fn (Datum $datum) => $datum->last_updated,
        ];
    }
}
