<?php

namespace App\Transformers\Outbound\Api;

class PlaceTransformer extends AbstractTransformer
{
    public function transform($item)
    {
        $data = [
            'id' => $item->id,
            'title' => $item->title,
            'latitude' => $item->latitude,
            'longitude' => $item->longitude,
            'tgn_id' => $item->tgn_id,
            'source_updated_at' => $this->getDateTime($item->source_updated_at),
            'updated_at' => $this->getDateTime($item->updated_at),
        ];

        return parent::transform($data);
    }
}
