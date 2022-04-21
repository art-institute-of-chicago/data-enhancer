<?php

namespace App\Transformers\Inbound\Csv;

use App\Transformers\Inbound\Csv\Concerns\FromJson;

use App\Transformers\Datum;

class ArtworkTransformer extends AbstractTransformer
{
    use FromJson;

    protected function getFields()
    {
        return [
            'id' => null,
            'width' => null,
            'height' => null,
            'depth' => null,
            'linked_art_json' => fn (Datum $datum) => $this->fromJson($datum->linked_art_json),
        ];
    }
}
