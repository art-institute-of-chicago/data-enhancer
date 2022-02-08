<?php

namespace App\Transformers\Api;

use Aic\Hub\Foundation\AbstractTransformer;

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
        ];

        return parent::transform($data);
    }
}
