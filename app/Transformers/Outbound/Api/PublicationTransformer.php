<?php

namespace App\Transformers\Outbound\Api;

class PublicationTransformer extends AbstractTransformer
{
    public function transform($item)
    {
        $data = [
            'id' => $item->id,
            'title' => $item->title,
            'site' => $item->site,
            'alias' => $item->alias,
            'generic_page_id' => $item->generic_page_id,
            'updated_at' => $this->getDateTime($item->updated_at),
        ];

        return parent::transform($data);
    }
}
