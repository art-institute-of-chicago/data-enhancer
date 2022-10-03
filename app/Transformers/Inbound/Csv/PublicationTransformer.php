<?php

namespace App\Transformers\Inbound\Csv;

use App\Transformers\Datum;

class PlaceTransformer extends AbstractTransformer
{
    protected function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'site' => null,
            'alias' => null,
            'generic_page_id' => null,
        ];
    }
}
