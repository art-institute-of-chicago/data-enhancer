<?php

namespace Tests\Api;

use Aic\Hub\Foundation\Testing\EndpointTestCase as BaseTestCase;

class AgentTest extends BaseTestCase
{
    protected $endpoint = 'api/v1/agents';

    protected $model = \App\Models\Agent::class;

    protected function fields()
    {
        return [
            'id' => 'integer',
            'title' => 'string',
            'birth_year' => 'integer|null',
            'death_year' => 'integer|null',
            'ulan_id' => 'integer|null',
            'ulan_certainty' => function ($value) {
                return is_null($value) || (is_integer($value) && $value <= 3 && $value >= 0);
            },
            'source_updated_at' => 'string|null',
            'updated_at' => 'string',
        ];
    }
}
