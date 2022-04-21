<?php

namespace App\Transformers\Inbound\Csv;

use App\Enums\GettyVocab;
use App\Transformers\Inbound\Csv\Concerns\CanUpdateGettyXmlFields;

use App\Transformers\Datum;

class PlaceTransformer extends AbstractTransformer
{
    use CanUpdateGettyXmlFields;

    protected function getFields()
    {
        return [
            'id' => null,
            'tgn_id' => [
                'value' => fn (Datum $datum) => $this->trimPrefix($datum->tgn_id, 'tgn/'),
                'on_change' => $this->updateGettyXmlField('tgn_xml', GettyVocab::TGN),
            ],
        ];
    }
}
