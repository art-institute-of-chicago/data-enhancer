<?php

namespace Tests\Api;

use Aic\Hub\Foundation\Testing\EndpointTestCase as BaseTestCase;

class ArtworkTest extends BaseTestCase
{
    protected $endpoint = 'api/v1/artworks';

    protected $model = \App\Models\Artwork::class;

    protected function fields()
    {
        return [
            'id' => 'integer',
            'title' => 'string|null',
            'dimension_display' => 'string|null',
            'width' => 'integer|null',
            'height' => 'integer|null',
            'depth' => 'integer|null',
            'medium_display' => 'string|null',
            'linked_art_json' => 'array|null',
            'nomisma_id' => 'string|null',
            'short_description' => 'string|null',
            'source_updated_at' => 'string|null',
            'updated_at' => 'string',
        ];
    }
}
