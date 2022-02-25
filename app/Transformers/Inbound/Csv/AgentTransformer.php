<?php

namespace App\Transformers\Inbound\Csv;

use App\Transformers\Inbound\AbstractTransformer;

class AgentTransformer extends AbstractTransformer
{
    public function getFields()
    {
        return [
            'id' => null,
            'ulan_id' => null,
            'ulan_certainty' => null,
        ];
    }
}
