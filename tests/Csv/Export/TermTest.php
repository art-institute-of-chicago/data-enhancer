<?php

namespace Tests\Csv\Export;

use Tests\Csv\CsvExportTestCase as BaseTestCase;

class TermTest extends BaseTestCase
{
    protected $resourceName = 'terms';

    protected $modelClass = \App\Models\Term::class;

    public function test_it_exports_resource()
    {
        return $this->checkCsvExport(
            [
                'title' => 'Foobar',
                'subtype' => 'TT-1',
                'aat_id' => 1234,
                'aat_xml' => 'foobar',
                'source_updated_at' => '2020-02-02 02:02:02',
            ],
            [
                'title' => 'Foobar',
                'subtype' => 'classification',
                'aat_id' => 'aat/1234',
                'aat_xml' => 'foobar',
                'source_updated_at' => '2020-02-02T02:02:02+00:00',
            ]
        );
    }

    public function test_it_exports_nullable_resource()
    {
        return $this->checkCsvExport(
            [
                'title' => null,
                'subtype' => null,
                'aat_id' => null,
                'aat_xml' => null,
                'source_updated_at' => null,
            ],
            [
                'title' => '',
                'subtype' => null,
                'aat_id' => '',
                'aat_xml' => '',
                'source_updated_at' => '',
            ]
        );
    }
}
