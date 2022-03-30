<?php

namespace Tests\Feature;

use App\Models\Term;
use App\Library\SourceConsumer;

use Tests\Csv\CsvImportTestCase as BaseTestCase;

class CsvImportTest extends BaseTestCase
{

    public function test_it_imports_csv_for_resource()
    {
        $this->addToAssertionCount(1);
    }

    public function test_it_shows_csv_import_form()
    {
        $response = $this->get('/csv/import');
        $response->assertSee('Import CSV');
    }

    public function test_it_errors_on_missing_fields()
    {
        $response = $this->post('/csv/import');
        $response->assertSessionHasErrors([
            'resource' => 'The resource field is required.',
            'csvFile' => 'The csv file field is required.',
        ]);
    }

    /**
     * Imports two CSV files in sequence. First CSV file is 2.5x the limit,
     * so it's guaranteed to be processed in multiple batches. Second CSV
     * file is 0.75x the limit, so it tests what happens when a CSV file
     * contains multiple rows, but is still small enough to be contained
     * in one batch. Also tests that `updated_at` gets updated correctly.
     * The `aat_id` column is modified between imports.
     */
    public function test_it_imports_big_csv()
    {
        $getCsvContents = fn ($terms) => $terms
            ->map(fn ($term) => implode(',', [$term->id, $term->aat_id]))
            ->prepend('id,aat_id')
            ->implode(PHP_EOL);

        $limit = SourceConsumer::getLimit('csv', 'terms');

        $firstCount = round($limit * 2.5);
        $firstTerms = Term::factory()->count($firstCount)->make();

        $firstCsvContents = $getCsvContents($firstTerms);

        $this->travel(-5)->days();
        $this->importCsv('terms', $firstCsvContents);
        $this->travelBack();

        $this->assertDatabaseCount('terms', $firstCount);

        $secondCount = round($limit * 0.75);
        $secondTerms = $firstTerms
            ->random($secondCount)
            ->each(function ($term) {
                do {
                    $aatId = Term::factory()->make()->aat_id;
                }
                while ($aatId === $term->aat_id);

                $term->aat_id = $aatId;
            });

        $secondCsvContents = $getCsvContents($secondTerms);

        $this->travel(5)->days();
        $this->importCsv('terms', $secondCsvContents);
        $this->travelBack();

        $updatedCount = Term::whereDate('updated_at', '>', now()->toDateString())->count();

        $this->assertEquals($secondCount, $updatedCount);
    }
}
