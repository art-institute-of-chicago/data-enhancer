<?php

namespace App\Transformers\Outbound\Csv;

use App\Transformers\Datum;

class TermTransformer extends AbstractTransformer
{
    public function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'subtype' => fn (Datum $datum) => $datum->subtype?->display(),
            'aat_id' => fn (Datum $datum) => $this->addPrefix($datum->aat_id, 'aat/'),
            'source_updated_at' => fn (Datum $datum) => $this->getDateTime($datum->source_updated_at),
            'updated_at' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
        ];
    }
}
