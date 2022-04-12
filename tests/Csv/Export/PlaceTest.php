<?php

namespace Tests\Csv\Export;

use Tests\Csv\CsvExportTestCase as BaseTestCase;

class PlaceTest extends BaseTestCase
{
    protected $resourceName = 'places';

    protected $modelClass = \App\Models\Place::class;

    public function test_it_exports_resource()
    {
        return $this->checkCsvExport(
            [
                'title' => 'Foobar',
                'tgn_id' => 1234,
                'source_updated_at' => '2020-02-02 02:02:02',
            ],
            [
                'title' => 'Foobar',
                'tgn_id' => 'tgn/1234',
                'source_updated_at' => '2020-02-02T02:02:02+00:00',
            ]
        );
    }

    public function test_it_exports_nullable_resource()
    {
        return $this->checkCsvExport(
            [
                'title' => null,
                'tgn_id' => null,
                'source_updated_at' => null,
            ],
            [
                'title' => '',
                'tgn_id' => '',
                'source_updated_at' => '',
            ]
        );
    }
}
