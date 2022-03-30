<?php

namespace Tests\Csv;

use Illuminate\Support\Facades\Artisan;

use League\Csv\Reader;
use App\Models\CsvFile;
use Illuminate\Support\Facades\Storage;

use Aic\Hub\Foundation\Testing\FeatureTestCase;

class CsvExportTestCase extends FeatureTestCase
{
    protected $resourceName;

    public function tearDown(): void
    {
        Artisan::call('csv:clear');

        parent::tearDown();
    }

    public function test_it_exports_resource()
    {
        $primaryKey = ($this->modelClass)::instance()->getKeyName();

        $data = collect($this->data());

        $initialStates = $data->pluck(0);
        $expectedStates = $data->pluck(1);

        $datums = $initialStates
            ->map(fn ($attributes) => ($this->modelClass)::factory()->create($attributes))
            ->sortBy($primaryKey)
            ->values();

        $response = $this->post('/csv/export', [
            'resource' => $this->resourceName,
        ]);

        $csvReader = $this->getCsvReader();

        foreach ($csvReader as $offset => $finalState) {
            $expectedState = $expectedStates[$offset - 1];

            $this->assertEquals(
                $expectedState,
                array_intersect_key(
                    $finalState,
                    $expectedState
                )
            );
        }
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
