<?php

namespace App\Transformers\Inbound\Api;

use App\Transformers\Datum;
use App\Transformers\Inbound\AbstractTransformer;

class TermTransformer extends AbstractTransformer
{
    protected $requiredFields = [
        'id' => 'string',
        'title' => 'string',
        'last_updated' => 'string',
    ];

    public function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'source_updated_at' => fn (Datum $datum) => $this->getDateTime($datum->last_updated),
        ];
    }
}
