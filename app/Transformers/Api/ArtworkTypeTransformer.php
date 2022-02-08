<?php

namespace App\Transformers\Api;

use Aic\Hub\Foundation\AbstractTransformer;

class ArtworkTypeTransformer extends AbstractTransformer
{
    public function transform($item)
    {
        $data = [
            'id' => $item->id,
            'title' => $item->title,
            'aat_id' => $item->aat_id,
        ];

        return parent::transform($data);
    }
}
