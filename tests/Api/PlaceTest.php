<?php

namespace Tests\Api;

use Aic\Hub\Foundation\Testing\EndpointTestCase as BaseTestCase;

class PlaceTest extends BaseTestCase
{
    protected $endpoint = 'api/v1/places';

    protected $model = \App\Models\Place::class;

    protected function fields()
    {
        return [
            'id' => 'integer',
            'title' => 'string|null',
            'latitude' => 'double|null',
            'longitude' => 'double|null',
            'tgn_id' => 'integer|null',
            'source_updated_at' => 'string|null',
            'updated_at' => 'string',
        ];
    }
}
