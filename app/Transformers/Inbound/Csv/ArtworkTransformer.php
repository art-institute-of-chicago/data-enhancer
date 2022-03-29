<?php

namespace App\Transformers\Inbound\Csv;

use App\Transformers\Datum;
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
            'support_aat_id' => fn (Datum $datum) => $this->trimPrefix($datum->support_aat_id, 'aat/'),
            'linked_art_json' => null,
        ];
    }
}
