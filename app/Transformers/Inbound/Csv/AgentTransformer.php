<?php

namespace App\Transformers\Inbound\Csv;

use App\Enums\GettyVocab;
use App\Transformers\Inbound\Csv\Concerns\CanUpdateGettyXmlFields;

use App\Transformers\Datum;

class AgentTransformer extends AbstractTransformer
{
    use CanUpdateGettyXmlFields;

    protected function getFields()
    {
        return [
            'id' => null,
            'ulan_id' => [
                'value' => fn (Datum $datum) => $this->trimPrefix($datum->ulan_id, 'ulan/'),
                'on_change' => $this->updateGettyXmlField('ulan_xml', GettyVocab::ULAN),
            ],
            'ulan_certainty' => null,
        ];
    }
}
