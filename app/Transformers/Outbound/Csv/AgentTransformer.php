<?php

namespace App\Transformers\Outbound\Csv;

use App\Transformers\Datum;

class AgentTransformer extends AbstractTransformer
{
    public function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'birth_year' => null,
            'death_year' => null,
            'ulan_id' => fn (Datum $datum) => $this->addPrefix($datum->ulan_id, 'ulan/'),
            'ulan_certainty' => null,
            'source_updated_at' => fn (Datum $datum) => $this->getDateTime($datum->source_updated_at),
            'updated_at' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
        ];
    }
}
