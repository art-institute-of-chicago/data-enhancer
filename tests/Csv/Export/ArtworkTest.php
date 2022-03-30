<?php

namespace Tests\Csv\Export;

use Tests\Csv\CsvExportTestCase as BaseTestCase;

class ArtworkTest extends BaseTestCase
{
    protected $resourceName = 'artworks';

    protected $modelClass = \App\Models\Artwork::class;

    protected function data()
    {
        return [
            [
                [
                    'support_aat_id' => 1234,
                ],
                [
                    'support_aat_id' => 'aat/1234',
                ]
            ],
            [
                [
                    'support_aat_id' => null,
                ],
                [
                    'support_aat_id' => '',
                ]
            ],
        ];
    }
}
