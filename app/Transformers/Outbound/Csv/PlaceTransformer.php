<?php

namespace App\Transformers\Outbound\Csv;

use App\Transformers\Datum;

class PlaceTransformer extends AbstractTransformer
{
    public function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'latitude' => null,
            'longitude' => null,
            'tgn_id' => fn (Datum $datum) => $this->addPrefix($datum->tgn_id, 'tgn/'),
            'tgn_xml' => null,
            'source_updated_at' => fn (Datum $datum) => $this->getDateTime($datum->source_updated_at),
            'updated_at' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
        ];
    }
}
