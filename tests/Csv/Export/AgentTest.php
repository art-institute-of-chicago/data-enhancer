<?php

namespace Tests\Csv\Export;

use Tests\Csv\CsvExportTestCase as BaseTestCase;

class AgentTest extends BaseTestCase
{
    protected $resourceName = 'agents';

    protected $modelClass = \App\Models\Agent::class;

    protected function data()
    {
        return [
            [
                [
                    'title' => 'Foobar',
                    'birth_year' => 1234,
                    'death_year' => 1234,
                    'ulan_id' => 1234,
                    'ulan_certainty' => 1,
                    'source_updated_at' => '2020-02-02 02:02:02',
                ],
                [
                    'title' => 'Foobar',
                    'birth_year' => '1234',
                    'death_year' => '1234',
                    'ulan_id' => 'ulan/1234',
                    'ulan_certainty' => '1',
                    'source_updated_at' => '2020-02-02T02:02:02+00:00',
                ]
            ],
            [
                [
                    'title' => null,
                    'birth_year' => null,
                    'death_year' => null,
                    'ulan_id' => null,
                    'ulan_certainty' => null,
                    'source_updated_at' => null,
                ],
                [
                    'title' => '',
                    'birth_year' => '',
                    'death_year' => '',
                    'ulan_id' => '',
                    'ulan_certainty' => '',
                    'source_updated_at' => '',
                ],
            ],
        ];
    }
}
