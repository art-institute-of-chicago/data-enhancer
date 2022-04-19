<?php

namespace Tests\Csv\Export;

use Tests\Csv\CsvExportTestCase as BaseTestCase;

class ArtworkTypeTest extends BaseTestCase
{
    protected $resourceName = 'artwork-types';

    protected $modelClass = \App\Models\ArtworkType::class;

    public function test_it_exports_resource()
    {
        return $this->checkCsvExport(
            [
                'title' => 'Foobar',
                'aat_id' => 1234,
                'aat_xml' => 'foobar',
                'source_updated_at' => '2020-02-02 02:02:02',
            ],
            [
                'title' => 'Foobar',
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
                'aat_id' => null,
                'aat_xml' => null,
                'source_updated_at' => null,
            ],
            [
                'title' => '',
                'aat_id' => '',
                'aat_xml' => '',
                'source_updated_at' => '',
            ]
        );
    }
}
