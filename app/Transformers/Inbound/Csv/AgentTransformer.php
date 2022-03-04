<?php

namespace App\Transformers\Inbound\Csv;

use App\Transformers\Datum;
use App\Transformers\Inbound\AbstractTransformer;

class AgentTransformer extends AbstractTransformer
{
    public function getFields()
    {
        return [
            'id' => null,
            'ulan_id' => fn (Datum $datum) => $this->trimPrefix($datum->ulan_id, 'ulan/'),
            'ulan_certainty' => null,
        ];
    }
}
