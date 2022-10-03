<?php

namespace Tests\Csv\Export;

use Tests\Csv\CsvExportTestCase as BaseTestCase;

class SectionTest extends BaseTestCase
{
    protected $resourceName = 'sections';

    protected $modelClass = \App\Models\Section::class;

    public function test_it_exports_resource()
    {
        return $this->checkCsvExport(
            [
                'title' => 'Foobar',
                'accession' => '123.456.789',
                'artwork_id' => 1234,
                'source_id' => 5678,
                'publication_id' => 3456,
                'content' => 'Lorem ipsum.',
                'source_updated_at' => '2020-02-02 02:02:02',
            ],
            [
                'title' => 'Foobar',
                'accession' => '123.456.789',
                'artwork_id' => 1234,
                'source_id' => 5678,
                'publication_id' => 3456,
                'content' => 'Lorem ipsum.',
                'source_updated_at' => '2020-02-02T02:02:02+00:00',
            ]
        );
    }

    public function test_it_exports_nullable_resource()
    {
        return $this->checkCsvExport(
            [
                'title' => null,
                'accession' => null,
                'artwork_id' => null,
                'content' => null,
                'source_updated_at' => null,
            ],
            [
                'title' => '',
                'accession' => null,
                'artwork_id' => '',
                'content' => '',
                'source_updated_at' => '',
            ]
        );
    }
}
