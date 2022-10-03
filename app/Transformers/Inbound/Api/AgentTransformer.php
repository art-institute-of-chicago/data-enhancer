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
            'title' => 'sort_title',
            'birth_year' => 'birth_date',
            'death_year' => 'death_date',
            'source_updated_at' => [
                'value' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
                'requires' => [
                    'updated_at',
                ],
            ],
        ];
    }
}
