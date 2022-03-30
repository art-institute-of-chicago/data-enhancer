<?php

namespace Tests\Csv\Export;

use Tests\Csv\CsvExportTestCase as BaseTestCase;

class ArtworkTypeTest extends BaseTestCase
{
    protected $resourceName = 'artwork-types';

    protected $modelClass = \App\Models\ArtworkType::class;

    protected function data()
    {
        return [
            [
                [
                    'aat_id' => 1234,
                ],
                [
                    'aat_id' => 'aat/1234',
                ]
            ],
            [
                [
                    'aat_id' => null,
                ],
                [
                    'aat_id' => '',
                ]
            ],
        ];
    }
}
