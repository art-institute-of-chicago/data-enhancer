<?php

namespace Tests\Csv\Import;

use Tests\Csv\CsvImportTestCase;

class ArtworkTest extends CsvImportTestCase
{
    protected $modelClass = \App\Models\Artwork::class;

    protected $resourceName = 'artworks';

    public function test_it_imports_resource()
    {
        return $this->checkCsvImport(
            [
                'id' => 1,
                'title' => 'Foobar',
                'dimension_display' => '5 × 5 × 5 cm',
                'width' => 5,
                'height' => 5,
                'depth' => 5,
                'medium_display' => 'Foobar',
                'linked_art_json' => (object) [
                    'foo' => 'bar',
                ],
                'source_updated_at' => $this->oldUpdatedAt,
            ],
            <<<END
            id,title,dimension_display,width,height,depth,medium_display,linked_art_json,source_updated_at
            1,Foobaz,"10 × 10 × 10 cm",10,10,10,Foobaz,"{""foo"":""baz""}",{$this->newUpdatedAt}
            END,
            [
                'id' => 1,
                'title' => 'Foobar',
                'dimension_display' => '5 × 5 × 5 cm',
                'width' => 10,
                'height' => 10,
                'depth' => 10,
                'medium_display' => 'Foobar',
                'linked_art_json' => (object) [
                    'foo' => 'baz',
                ],
                'source_updated_at' => $this->oldUpdatedAt,
            ]
        );
    }
}
