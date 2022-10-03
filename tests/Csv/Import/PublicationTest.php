<?php

namespace Tests\Csv\Import;

use Tests\Csv\CsvImportTestCase;

class PublicationTest extends CsvImportTestCase
{
    protected $modelClass = \App\Models\Publication::class;

    protected $resourceName = 'publications';

    public function test_it_imports_resource()
    {
        return $this->checkCsvImport(
            [
                'id' => 1,
                'title' => 'Foobar',
                'site' => 'foosite',
                'alias' => 'fooalias',
                'generic_page_id' => 456,
            ],
            <<<END
            id,title,site,alias,generic_page_id,source_updated_at
            1,Foobaz,barsite,baralias,789,{$this->newUpdatedAt}
            END,
            [
                'id' => 1,
                'title' => 'Foobaz',
                'site' => 'barsite',
                'alias' => 'baralias',
                'generic_page_id' => 789,
            ]
        );
    }
}
