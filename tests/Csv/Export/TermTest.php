<?php

namespace Tests\Csv\Export;

use Tests\Csv\CsvExportTestCase as BaseTestCase;

class TermTest extends BaseTestCase
{
    protected $resourceName = 'terms';

    protected $modelClass = \App\Models\Term::class;

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
