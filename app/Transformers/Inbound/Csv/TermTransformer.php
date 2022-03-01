<?php

namespace App\Transformers\Inbound\Csv;

use App\Transformers\Inbound\AbstractTransformer;

class TermTransformer extends AbstractTransformer
{
    public function getFields()
    {
        return [
            'id' => null,
            'aat_id' => null,
        ];
    }
}
