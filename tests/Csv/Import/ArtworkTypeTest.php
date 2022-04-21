<?php

namespace Tests\Csv\Import;

use Tests\Csv\CsvImportTestCase;

class ArtworkTypeTest extends CsvImportTestCase
{
    protected $modelClass = \App\Models\ArtworkType::class;

    protected $resourceName = 'artwork-types';

    public function test_it_imports_resource()
    {
        return $this->checkCsvImport(
            [
                'id' => 1,
                'title' => 'Foobar',
                'aat_id' => 12345,
                'aat_xml' => 'foobar',
                'source_updated_at' => $this->oldUpdatedAt,
            ],
            <<<END
            id,title,aat_id,aat_xml,source_updated_at
            1,Foobaz,aat/67890,foobaz,{$this->newUpdatedAt}
            END,
            [
                'id' => 1,
                'title' => 'Foobar',
                'aat_id' => 67890,
                'aat_xml' => 'foobar',
                'source_updated_at' => $this->oldUpdatedAt,
            ]
        );
    }
}
