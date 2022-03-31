<?php

namespace Tests\Csv\Import;

use Tests\Csv\CsvImportTestCase;

class PlaceTest extends CsvImportTestCase
{
    protected $modelClass = \App\Models\Place::class;

    protected $resourceName = 'places';

    protected function data()
    {
        return [
            [
                [
                    'id' => 1,
                    'title' => 'Foobar',
                    'latitude' => 41.8796,
                    'longitude' => 87.6237,
                    'tgn_id' => 12345,
                    'source_updated_at' => $this->oldUpdatedAt,
                ],
                <<<END
                id,title,latitude,longitude,tgn_id,source_updated_at
                1,Foobaz,39.8260,86.1857,tgn/67890,{$this->newUpdatedAt}
                END,
                [
                    'id' => 1,
                    'title' => 'Foobar',
                    'latitude' => 41.8796,
                    'longitude' => 87.6237,
                    'tgn_id' => 67890,
                    'source_updated_at' => $this->oldUpdatedAt,
                ]
            ],
        ];
    }
}
