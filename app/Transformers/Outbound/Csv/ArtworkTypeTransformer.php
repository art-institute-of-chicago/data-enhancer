<?php

namespace App\Transformers\Outbound\Csv;

use App\Transformers\Datum;

class ArtworkTypeTransformer extends AbstractTransformer
{
    protected function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'aat_id' => fn (Datum $datum) => $this->addPrefix($datum->aat_id, 'aat/'),
            'aat_xml' => null,
            'source_updated_at' => fn (Datum $datum) => $this->getDateTime($datum->source_updated_at),
            'updated_at' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
        ];
    }
}
