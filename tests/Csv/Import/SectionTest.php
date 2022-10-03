<?php

namespace Tests\Csv\Import;

use Tests\Csv\CsvImportTestCase;

class SectionTest extends CsvImportTestCase
{
    protected $modelClass = \App\Models\Section::class;

    protected $resourceName = 'sections';

    public function test_it_imports_resource()
    {
        return $this->checkCsvImport(
            [
                'id' => 1,
                'title' => 'Foobar',
                'accession' => '123.456.789',
                'artwork_id' => 1234,
                'source_id' => 5678,
                'publication_id' => 3456,
                'content' => 'Lorem ipsum.',
                'source_updated_at' => $this->oldUpdatedAt,
            ],
            <<<END
            id,title,accession,artwork_id,source_id,publication_id,content,source_updated_at
            1,Foobaz,321.654.987,4321,8765,6543,Ipsum lorem.,{$this->newUpdatedAt}
            END,
            [
                'id' => 1,
                'title' => 'Foobaz',
                'accession' => '321.654.987',
                'artwork_id' => 4321,
                'source_id' => 8765,
                'publication_id' => 6543,
                'content' => 'Ipsum lorem.',
                'source_updated_at' => $this->newUpdatedAt,
            ]
        );
    }
}
