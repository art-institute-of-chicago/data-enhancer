<?php

namespace App\Transformers\Outbound\Csv;

use App\Transformers\Datum;

class SectionTransformer extends AbstractTransformer
{
    protected function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'accession' => null,
            'artwork_id' => null,
            'source_id' => null,
            'publication_id' => null,
            'content' => null,
            'source_updated_at' => fn (Datum $datum) => $this->getDateTime($datum->source_updated_at),
            'updated_at' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
        ];
    }
}
