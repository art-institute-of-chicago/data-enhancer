<?php

namespace App\Http\Transformers;

use Aic\Hub\Foundation\AbstractTransformer;

class TermTransformer extends AbstractTransformer
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
