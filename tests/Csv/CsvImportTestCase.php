<?php

namespace Tests\Csv;

use Carbon\Carbon;
use Tests\Concerns\ImportsCsv;

use Aic\Hub\Foundation\Testing\FeatureTestCase;

class CsvImportTestCase extends FeatureTestCase
{
    use ImportsCsv;

    protected $oldUpdatedAt;
    protected $newUpdatedAt;

    protected function setUp(): void
    {
        parent::setUp();

        // API-129: Be careful about inserting values with timezones without pre-processing.
        $this->oldUpdatedAt = Carbon::parse('10 minutes ago')->roundSecond()->toISOString();
        $this->newUpdatedAt = Carbon::parse('5 minutes ago')->roundSecond()->toISOString();
    }

    public function test_it_imports_csv_for_resource()
    {
        $data = $this->data();

        $initialState = $data[0];
        $csvContents = $data[1];
        $expectedState = $data[2];

        $initialItem = ($this->modelClass)::factory()->create($initialState);
        $id = $initialItem->getKey();

        $this->importCsv($this->resourceName, $csvContents);

        $finalItem = ($this->modelClass)::find($id);
        $finalState = $finalItem->toArray();

        $this->assertEquals(
            $expectedState,
            array_intersect_key(
                $finalState,
                $expectedState
            )
        );
    }
}
