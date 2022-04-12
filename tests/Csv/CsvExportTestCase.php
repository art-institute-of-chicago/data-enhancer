<?php

namespace Tests\Csv;

use Illuminate\Support\Facades\Artisan;

use League\Csv\Reader;
use App\Models\CsvFile;
use Illuminate\Support\Facades\Storage;

use Aic\Hub\Foundation\Testing\FeatureTestCase;

abstract class CsvExportTestCase extends FeatureTestCase
{
    protected $resourceName;

    public function tearDown(): void
    {
        Artisan::call('csv:clear');

        parent::tearDown();
    }

    abstract public function test_it_exports_resource();

    protected function checkCsvExport(
        array $initialState,
        array $expectedState
    ) {
        $datum = ($this->modelClass)::factory()->create($initialState);

        $response = $this->post('/csv/export', [
            'resource' => $this->resourceName,
        ]);

        $response->assertSessionHasNoErrors();

        $response->assertStatus(302);

        $csvReader = $this->getCsvReader();

        $finalState = $csvReader->fetchOne();

        $this->assertEquals(
            $expectedState,
            array_intersect_key(
                $finalState,
                $expectedState
            )
        );
    }

    protected function getCsvReader()
    {
        $csvFile = CsvFile::first();
        $csvPath = Storage::disk('public')->path($csvFile->filename);

        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);

        return $csv;
    }
}
