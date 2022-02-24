<?php

namespace App\Transformers\Inbound\Csv;

use App\Transformers\Inbound\AbstractTransformer;

class ArtworkTypeTransformer extends AbstractTransformer
{
    public function getFields()
    {
        return [
            'id' => null,
            'aat_id' => null,
        ];
    }
}
