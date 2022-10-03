<?php

namespace App\Transformers\Outbound\Api;

class SectionTransformer extends AbstractTransformer
{
    public function transform($item)
    {
        $data = [
            'id' => $item->id,
            'title' => $item->title,
            'accession' => $item->accession,
            'artwork_id' => $item->artwork_id,
            'source_id' => $item->source_id,
            'publication_id' => $item->publication_id,
            'content' => $item->content,
            'source_updated_at' => $this->getDateTime($item->source_updated_at),
            'updated_at' => $this->getDateTime($item->updated_at),
        ];

        return parent::transform($data);
    }
}
