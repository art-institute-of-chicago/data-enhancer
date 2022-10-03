<?php

namespace App\Transformers\Outbound\Csv;

use App\Transformers\Datum;

class PublicationTransformer extends AbstractTransformer
{
    protected function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'site' => null,
            'alias' => null,
            'generic_page_id' => null,
            'updated_at' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
        ];
    }
}
