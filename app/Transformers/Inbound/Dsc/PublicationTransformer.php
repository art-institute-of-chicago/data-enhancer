<?php

namespace App\Transformers\Inbound\Dsc;

use App\Transformers\Datum;
use App\Transformers\Inbound\AbstractTransformer;

class PublicationTransformer extends AbstractTransformer
{
    protected static $sourceUpdatedAtField = null;

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
