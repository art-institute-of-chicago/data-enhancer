<?php

namespace App\Transformers\Outbound\Csv;

use App\Transformers\Datum;

class ArtworkTransformer extends AbstractTransformer
{
    public function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'dimension_display' => null,
            'width' => null,
            'height' => null,
            'depth' => null,
            'medium_display' => null,
            'support_aat_id' => fn (Datum $datum) => $this->addPrefix($datum->support_aat_id, 'aat/'),
            'linked_art_json' => null,
            'source_updated_at' => fn (Datum $datum) => $this->getDateTime($datum->source_updated_at),
            'updated_at' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
        ];
    }
}
