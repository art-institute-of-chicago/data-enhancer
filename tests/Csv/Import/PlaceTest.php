<?php

namespace Tests\Csv\Import;

use Tests\Csv\CsvImportTestCase;

class PlaceTest extends CsvImportTestCase
{
    protected $modelClass = \App\Models\Place::class;

    protected $resourceName = 'places';

    public function test_it_imports_resource()
    {
        return $this->checkCsvImport(
            [
                'id' => 1,
                'title' => 'Foobar',
                'latitude' => 41.8796,
                'longitude' => 87.6237,
                'tgn_id' => 12345,
                'tgn_xml' => 'foobar',
                'source_updated_at' => $this->oldUpdatedAt,
            ],
            <<<END
            id,title,latitude,longitude,tgn_id,tgn_xml,source_updated_at
            1,Foobaz,39.8260,86.1857,tgn/67890,foobaz,{$this->newUpdatedAt}
            END,
            [
                'id' => 1,
                'title' => 'Foobar',
                'latitude' => 41.8796,
                'longitude' => 87.6237,
                'tgn_id' => 67890,
                'tgn_xml' => 'foobar',
                'source_updated_at' => $this->oldUpdatedAt,
            ]
        );
    }
}
