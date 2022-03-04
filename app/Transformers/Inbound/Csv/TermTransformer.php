<?php

namespace App\Transformers\Inbound\Csv;

use App\Transformers\Datum;
use App\Transformers\Inbound\AbstractTransformer;

class TermTransformer extends AbstractTransformer
{
    public function getFields()
    {
        return [
            'id' => null,
            'aat_id' => fn (Datum $datum) => $this->trimPrefix($datum->aat_id, 'aat/'),
        ];
    }
}
