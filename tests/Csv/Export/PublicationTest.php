<?php

namespace Tests\Csv\Export;

use Tests\Csv\CsvExportTestCase as BaseTestCase;

class PublicationTest extends BaseTestCase
{
    protected $resourceName = 'publications';

    protected $modelClass = \App\Models\Publication::class;

    public function test_it_exports_resource()
    {
        return $this->checkCsvExport(
            [
                'title' => 'Foobar',
                'site' => 'foosite',
                'alias' => 'fooalias',
                'generic_page_id' => 456,
            ],
            [
                'title' => 'Foobar',
                'site' => 'foosite',
                'alias' => 'fooalias',
                'generic_page_id' => 456,
            ]
        );
    }

    public function test_it_exports_nullable_resource()
    {
        return $this->checkCsvExport(
            [
                'title' => null,
                'generic_page_id' => null,
            ],
            [
                'title' => '',
                'generic_page_id' => '',
            ]
        );
    }
}
