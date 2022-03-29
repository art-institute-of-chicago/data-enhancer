<?php

namespace Tests\Csv\Import;

use Tests\Csv\CsvImportTestCase;

class TermTest extends CsvImportTestCase
{
    protected $modelClass = \App\Models\Term::class;

    protected $resourceName = 'terms';

    protected function data()
    {
        return [
            [
                'id' => 'TM-1',
                'title' => 'Foobar',
                'aat_id' => 12345,
                'source_updated_at' => $this->oldUpdatedAt,
            ],
            <<<END
            id,title,aat_id,source_updated_at
            TM-1,Foobaz,aat/67890,{$this->newUpdatedAt}
            END,
            [
                'id' => 'TM-1',
                'title' => 'Foobar',
                'aat_id' => 67890,
                'source_updated_at' => $this->oldUpdatedAt,
            ]
        ];
    }
}
