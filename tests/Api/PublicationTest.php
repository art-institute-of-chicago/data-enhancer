<?php

namespace Tests\Api;

use Aic\Hub\Foundation\Testing\EndpointTestCase as BaseTestCase;

class PublicationTest extends BaseTestCase
{
    protected $endpoint = 'api/v1/publications';

    protected $model = \App\Models\Publication::class;

    protected function fields()
    {
        return [
            'id' => 'integer',
            'title' => 'string|null',
            'site' => 'string',
            'alias' => 'string',
            'generic_page_id' => 'integer|null',
            'updated_at' => 'string',
        ];
    }
}
