<?php

namespace Tests\Csv\Import;

use Tests\Csv\CsvImportTestCase;

class TermTest extends CsvImportTestCase
{
    protected $modelClass = \App\Models\Term::class;

    protected $resourceName = 'terms';

    public function test_it_imports_resource()
    {
        return $this->checkCsvImport(
            [
                'id' => 'TM-1',
                'title' => 'Foobar',
                'aat_id' => 12345,
                'aat_xml' => 'foobar',
                'source_updated_at' => $this->oldUpdatedAt,
            ],
            <<<END
            id,title,aat_id,aat_xml,source_updated_at
            TM-1,Foobaz,aat/67890,foobaz,{$this->newUpdatedAt}
            END,
            [
                'id' => 'TM-1',
                'title' => 'Foobar',
                'aat_id' => 67890,
                'aat_xml' => 'foobaz',
                'source_updated_at' => $this->oldUpdatedAt,
            ]
        );
    }
}
