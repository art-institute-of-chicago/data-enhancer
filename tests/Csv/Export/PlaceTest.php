<?php

namespace Tests\Csv\Export;

use Tests\Csv\CsvExportTestCase as BaseTestCase;

class PlaceTest extends BaseTestCase
{
    protected $resourceName = 'places';

    protected $modelClass = \App\Models\Place::class;

    protected function data()
    {
        return [
            [
                [
                    'tgn_id' => 1234,
                ],
                [
                    'tgn_id' => 'tgn/1234',
                ]
            ],
            [
                [
                    'tgn_id' => null,
                ],
                [
                    'tgn_id' => '',
                ]
            ],
        ];
    }
}
