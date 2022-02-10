<?php

namespace App\Transformers\Imports;

use App\Transformers\Datum;

class AgentTransformer extends AbstractTransformer
{
    protected $requiredFields = [
        'id' => 'integer',
        'sort_title' => 'string',
        'birth_date' => 'integer|null',
        'death_date' => 'integer|null',
    ];

    public function getFields()
    {
        return [
            'id' => null,
            'title' => fn (Datum $datum) => $datum->sort_title,
            'birth_year' => fn (Datum $datum) => $datum->birth_date,
            'death_year' => fn (Datum $datum) => $datum->death_date,
        ];
    }
}
