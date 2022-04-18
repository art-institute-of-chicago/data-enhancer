<?php

namespace App\Transformers\Inbound\Csv;

use App\Transformers\Datum;
use App\Transformers\Inbound\AbstractTransformer;

class PlaceTransformer extends AbstractTransformer
{
    protected function getFields()
    {
        return [
            'id' => null,
            'tgn_id' => fn (Datum $datum) => $this->trimPrefix($datum->tgn_id, 'tgn/'),
            'tgn_xml' => null,
        ];
    }
}
