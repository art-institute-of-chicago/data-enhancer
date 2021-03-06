<?php

namespace Tests\Api;

use Aic\Hub\Foundation\Testing\EndpointTestCase as BaseTestCase;

class ArtworkTypeTest extends BaseTestCase
{
    protected $endpoint = 'api/v1/artwork-types';

    protected $model = \App\Models\ArtworkType::class;

    protected function fields()
    {
        return [
            'id' => 'integer',
            'title' => 'string|null',
            'aat_id' => 'integer|null',
            'source_updated_at' => 'string|null',
            'updated_at' => 'string',
        ];
    }
}
