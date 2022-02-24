<?php

namespace App\Transformers\Inbound\Csv;

use App\Transformers\Inbound\AbstractTransformer;

class ArtworkTransformer extends AbstractTransformer
{
    public function getFields()
    {
        return [
            'id' => null,
            'width' => null,
            'height' => null,
            'depth' => null,
            'support_aat_id' => null,
        ];
    }
}
