<?php

namespace App\Transformers\Outbound\Csv;

use App\Transformers\Datum;
use App\Transformers\Outbound\Csv\Concerns\ToJson;

class ArtworkTransformer extends AbstractTransformer
{
    use ToJson;

    protected function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'dimension_display' => null,
            'width' => null,
            'height' => null,
            'depth' => null,
            'medium_display' => null,
            'linked_art_json' => fn (Datum $datum) => $this->toJson($datum->linked_art_json),
            'nomisma_id' => null,
            'source_updated_at' => fn (Datum $datum) => $this->getDateTime($datum->source_updated_at),
            'updated_at' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
        ];
    }
}
