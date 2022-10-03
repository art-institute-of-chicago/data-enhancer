<?php

namespace Tests\Csv\Export;

use Tests\Csv\CsvExportTestCase as BaseTestCase;

class ArtworkTest extends BaseTestCase
{
    protected $resourceName = 'artworks';

    protected $modelClass = \App\Models\Artwork::class;

    public function test_it_exports_resource()
    {
        return $this->checkCsvExport(
            [
                'title' => 'Foobar',
                'dimension_display' => '5 x 5 x 5 mm',
                'width' => 5,
                'height' => 5,
                'depth' => 5,
                'medium_display' => 'Foobar',
                'linked_art_json' => (object) [
                    'foo' => 'bar',
                ],
                'nomisma_id' => 'http://numismatics.org/foo/id/ab.cd.1.2.34',
                'source_updated_at' => '2020-02-02 02:02:02',
            ],
            [
                'title' => 'Foobar',
                'dimension_display' => '5 x 5 x 5 mm',
                'width' => '5',
                'height' => '5',
                'depth' => '5',
                'medium_display' => 'Foobar',
                'linked_art_json' => '{"foo":"bar"}',
                'nomisma_id' => 'http://numismatics.org/foo/id/ab.cd.1.2.34',
                'source_updated_at' => '2020-02-02T02:02:02+00:00',
            ]
        );
    }

    public function test_it_exports_nullable_resource()
    {
        return $this->checkCsvExport(
            [
                'title' => null,
                'dimension_display' => null,
                'width' => null,
                'height' => null,
                'depth' => null,
                'medium_display' => null,
                'linked_art_json' => null,
                'nomisma_id' => null,
                'source_updated_at' => null,
            ],
            [
                'title' => '',
                'dimension_display' => '',
                'width' => '',
                'height' => '',
                'depth' => '',
                'medium_display' => '',
                'linked_art_json' => 'null',
                'nomisma_id' => '',
                'source_updated_at' => '',
            ]
        );
    }
}
