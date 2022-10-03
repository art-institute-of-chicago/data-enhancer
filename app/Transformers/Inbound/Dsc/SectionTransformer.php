<?php

namespace App\Transformers\Inbound\Dsc;

use App\Transformers\Datum;
use App\Transformers\Inbound\AbstractTransformer;

class SectionTransformer extends AbstractTransformer
{
    protected function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'accession' => null,
            'artwork_id' => 'citi_id',
            'source_id' => null,
            'publication_id' => null,
            'content' => null,
            'source_updated_at' => [
                'value' => fn (Datum $datum) => $this->getDateTime($datum->revision),
                'requires' => [
                    'revision',
                ],
            ],
        ];
    }
}
