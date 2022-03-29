<?php

namespace Tests\Csv\Import;

use Tests\Csv\CsvImportTestCase;

class ArtworkTypeTest extends CsvImportTestCase
{
    protected $modelClass = \App\Models\ArtworkType::class;

    protected $resourceName = 'artwork-types';

    protected function data()
    {
        return [
            [
                'id' => 1,
                'title' => 'Foobar',
                'aat_id' => 12345,
                'source_updated_at' => $this->oldUpdatedAt,
            ],
            <<<END
            id,title,aat_id,source_updated_at
            1,Foobaz,aat/67890,{$this->newUpdatedAt}
            END,
            [
                'id' => 1,
                'title' => 'Foobar',
                'aat_id' => 67890,
                'source_updated_at' => $this->oldUpdatedAt,
            ]
        ];
    }
}
