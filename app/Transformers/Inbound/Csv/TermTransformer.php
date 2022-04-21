<?php

namespace App\Transformers\Inbound\Csv;

use App\Enums\GettyVocab;
use App\Transformers\Inbound\Csv\Concerns\CanUpdateGettyXmlFields;

use App\Transformers\Datum;

class TermTransformer extends AbstractTransformer
{
    use CanUpdateGettyXmlFields;

    protected function getFields()
    {
        return [
            'id' => null,
            'aat_id' => [
                'value' => fn (Datum $datum) => $this->trimPrefix($datum->aat_id, 'aat/'),
                'on_change' => $this->updateGettyXmlField('aat_xml', GettyVocab::AAT),
            ],
            'aat_xml' => null,
        ];
    }
}
