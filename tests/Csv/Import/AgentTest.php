<?php

namespace Tests\Csv\Import;

use Tests\Csv\CsvImportTestCase;

class AgentTest extends CsvImportTestCase
{
    protected $modelClass = \App\Models\Agent::class;

    protected $resourceName = 'agents';

    protected function data()
    {
        return [
            [
                [
                    'id' => 1,
                    'title' => 'Foobar',
                    'birth_year' => 1950,
                    'death_year' => 1999,
                    'ulan_id' => 12345,
                    'ulan_certainty' => 1,
                    'source_updated_at' => $this->oldUpdatedAt,
                ],
                <<<END
                id,title,birth_year,death_year,ulan_id,ulan_certainty,source_updated_at
                1,Foobaz,1945,2000,ulan/67890,3,{$this->newUpdatedAt}
                END,
                [
                    'id' => 1,
                    'title' => 'Foobar',
                    'birth_year' => 1950,
                    'death_year' => 1999,
                    'ulan_id' => 67890,
                    'ulan_certainty' => 3,
                    'source_updated_at' => $this->oldUpdatedAt,
                ]
            ],
        ];
    }
}
