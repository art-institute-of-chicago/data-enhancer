<?php

namespace App\Transformers\Outbound\Api;

class ArtworkTransformer extends AbstractTransformer
{
    public function transform($item)
    {
        $data = [
            'id' => $item->id,
            'title' => $item->title,
            'dimension_display' => $item->dimension_display,
            'width' => $item->width,
            'height' => $item->height,
            'depth' => $item->depth,
            'medium_display' => $item->medium_display,
            'linked_art_json' => $item->linked_art_json,
            'nomisma_id' => $item->nomisma_id,
            'short_description' => $item->short_description,
            'source_updated_at' => $this->getDateTime($item->source_updated_at),
            'updated_at' => $this->getDateTime($item->updated_at),
        ];

        return parent::transform($data);
    }
}
