<?php

namespace Tests\Csv;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

use Aic\Hub\Foundation\Testing\FeatureTestCase;

abstract class CsvImportTestCase extends FeatureTestCase
{
    protected $resourceName;

    protected $oldUpdatedAt;

    protected $newUpdatedAt;

    protected function setUp(): void
    {
        parent::setUp();

        // API-129: Be careful about inserting values with timezones without pre-processing.
        $this->oldUpdatedAt = Carbon::parse('10 minutes ago')->roundSecond()->toISOString();
        $this->newUpdatedAt = Carbon::parse('5 minutes ago')->roundSecond()->toISOString();
    }

    abstract public function test_it_imports_resource();

    protected function checkCsvImport(
        array $initialState,
        string $csvContents,
        array $expectedState,
        bool $expectUpdatedAtToChange = true,
    ) {
        $initialItem = ($this->modelClass)::factory()->create($initialState);
        $id = $initialItem->getKey();

        $this->travelTo(now()->addMinutes(30));

        $this->importCsv($this->resourceName, $csvContents);

        $this->travelBack();

        $finalItem = ($this->modelClass)::find($id);
        $finalState = $finalItem->toArray();

        if ($expectUpdatedAtToChange) {
            $this->assertTrue($finalItem->updated_at->gt($initialItem->updated_at));
        } else {
            $this->assertTrue($finalItem->updated_at->eq($initialItem->updated_at));
        }

        $this->assertEquals(
            $expectedState,
            array_intersect_key(
                $finalState,
                $expectedState
            )
        );
    }

    protected function importCsv(
        string $resourceName,
        string $csvContents
    ) {
        $csvFile = UploadedFile::fake()->createWithContent('test.csv', $csvContents);

        $response = $this->post('/csv/import', [
            'resource' => $resourceName,
            'csvFile' => $csvFile,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        return $response;
    }
}
