<?php

namespace Tests\Csv\Import;

use Tests\Csv\CsvImportTestCase;

class ArtworkTest extends CsvImportTestCase
{
    protected $modelClass = \App\Models\Artwork::class;

    protected $resourceName = 'artworks';

    protected function data()
    {
        return [
            [
                [
                    'id' => 1,
                    'title' => 'Foobar',
                    'dimension_display' => '5 × 5 × 5 cm',
                    'width' => 5,
                    'height' => 5,
                    'depth' => 5,
                    'medium_display' => 'Foobar',
                    'support_aat_id' => 12345,
                    'linked_art_json' => (object) [
                        'foo' => 'bar',
                    ],
                    'source_updated_at' => $this->oldUpdatedAt,
                ],
                <<<END
                id,title,dimension_display,width,height,depth,medium_display,support_aat_id,linked_art_json,source_updated_at
                1,Foobaz,"10 × 10 × 10 cm",10,10,10,Foobaz,aat/67890,"{""foo"":""baz""}",{$this->newUpdatedAt}
                END,
                [
                    'id' => 1,
                    'title' => 'Foobar',
                    'dimension_display' => '5 × 5 × 5 cm',
                    'width' => 10,
                    'height' => 10,
                    'depth' => 10,
                    'medium_display' => 'Foobar',
                    'support_aat_id' => 67890,
                    'linked_art_json' => (object) [
                        'foo' => 'baz',
                    ],
                    'source_updated_at' => $this->oldUpdatedAt,
                ],
            ]
        ];
    }
}
