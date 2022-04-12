<?php

namespace Tests\Api;

use Aic\Hub\Foundation\Testing\EndpointTestCase as BaseTestCase;

class TermTest extends BaseTestCase
{
    protected $endpoint = 'api/v1/terms';

    protected $model = \App\Models\Term::class;

    protected function fields()
    {
        return [
            'id' => 'string',
            'title' => 'string|null',
            'subtype' => 'string|null',
            'aat_id' => 'integer|null',
            'source_updated_at' => 'string|null',
            'updated_at' => 'string',
        ];
    }
}
