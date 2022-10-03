<?php

namespace Tests\Api;

use Aic\Hub\Foundation\Testing\EndpointTestCase as BaseTestCase;

class SectionTest extends BaseTestCase
{
    protected $endpoint = 'api/v1/sections';

    protected $model = \App\Models\Section::class;

    protected function fields()
    {
        return [
            'id' => 'integer',
            'title' => 'string|null',
            'accession' => 'string|null',
            'artwork_id' => 'integer|null',
            'source_id' => 'integer',
            'publication_id' => 'integer',
            'content' => 'string|null',
            'source_updated_at' => 'string|null',
            'updated_at' => 'string',
        ];
    }
}
