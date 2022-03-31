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
                    'title' => 'Foobar',
                    'aat_id' => 1234,
                    'source_updated_at' => '2020-02-02 02:02:02',
                ],
                [
                    'title' => 'Foobar',
                    'aat_id' => 'aat/1234',
                    'source_updated_at' => '2020-02-02T02:02:02+00:00',
                ]
            ],
            [
                [
                    'title' => null,
                    'aat_id' => null,
                    'source_updated_at' => null,
                ],
                [
                    'title' => '',
                    'aat_id' => '',
                    'source_updated_at' => '',
                ]
            ],
        ];
    }
}
