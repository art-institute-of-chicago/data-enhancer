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
                    'ulan_id' => 1234,
                ],
                [
                    'ulan_id' => 'ulan/1234',
                ]
            ],
            [
                [
                    'ulan_id' => null,
                ],
                [
                    'ulan_id' => '',
                ]
            ],
        ];
    }
}
