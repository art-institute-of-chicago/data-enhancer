<?php

namespace App\Transformers\Outbound\Api;

use App\Transformers\Outbound\AbstractTransformer;

class AgentTransformer extends AbstractTransformer
{
    public function transform($item)
    {
        $data = [
            'id' => $item->id,
            'title' => $item->title,
            'birth_year' => $item->birth_year,
            'death_year' => $item->death_year,
            'ulan_id' => $item->ulan_id,
            'ulan_certainty' => $item->ulan_certainty,
            'source_updated_at' => $this->getDateTime($item->source_updated_at),
            'updated_at' => $this->getDateTime($item->updated_at),
        ];

        return parent::transform($data);
    }
}
