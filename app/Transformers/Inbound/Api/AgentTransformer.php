<?php

namespace App\Transformers\Inbound\Api;

use App\Transformers\Datum;
use App\Transformers\Inbound\AbstractTransformer;

class AgentTransformer extends AbstractTransformer
{
    protected function getFields()
    {
        return [
            'id' => null,
            'title' => [
                'value' => fn (Datum $datum) => $datum->sort_title,
                'requires' => [
                    'sort_title',
                ],
            ],
            'birth_year' => [
                'value' => fn (Datum $datum) => $datum->birth_date,
                'requires' => [
                    'birth_date',
                ],
            ],
            'death_year' => [
                'value' => fn (Datum $datum) => $datum->death_date,
                'requires' => [
                    'death_date',
                ],
            ],
            'source_updated_at' => [
                'value' => fn (Datum $datum) => $this->getDateTime($datum->last_updated),
                'requires' => [
                    'last_updated',
                ],
            ],
        ];
    }
}
