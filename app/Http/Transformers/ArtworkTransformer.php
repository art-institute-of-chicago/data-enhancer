<?php

namespace App\Http\Transformers;

use Aic\Hub\Foundation\AbstractTransformer;

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
            'support_aat_id' => $item->support_aat_id,
        ];

        return parent::transform($data);
    }
}
