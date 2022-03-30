<?php

namespace Tests\Feature;

use App\Models\Term;
use App\Library\SourceConsumer;

use Tests\Concerns\HasFakeModel;
use Illuminate\Support\Facades\Config;
use Tests\Fakes\FakeInboundCsvTransformer;

use Tests\Csv\CsvImportTestCase as BaseTestCase;

class CsvImportTest extends BaseTestCase
{
    use HasFakeModel;

    public function setUp(): void
    {
        parent::setUp();

        Config::set('aic.imports.sources.csv.resources', [
            'foos' => [
                'model' => $this->modelClass,
                'transformer' => FakeInboundCsvTransformer::class,
            ],
        ]);

        $this->resourceName = 'foos';
    }

    protected function data()
    {
        return [
            [
                'id' => 1,
                'title' => 'Foobar',
                'acme_id' => 12345,
                'some_json' => (object) [
                    'foo' => 'bar',
                ],
            ],
            <<<END
            id,title,acme_id,some_json
            1,Foobaz,acme/67890,"{""foo"":""baz""}"
            END,
            [
                'id' => 1,
                'title' => 'Foobar',
                'acme_id' => 67890,
                'some_json' => (object) [
                    'foo' => 'baz',
                ],
            ]
        ];
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
     * The `acme_id` column is modified between imports.
     */
    public function test_it_imports_big_csv()
    {
        $getCsvContents = fn ($items) => $items
            ->map(fn ($item) => $item->id . ',' . $item->acme_id)
            ->prepend('id,acme_id')
            ->implode(PHP_EOL);

        $limit = SourceConsumer::getLimit('csv', $this->resourceName);

        $firstCount = round($limit * 2.5);
        $firstItems = ($this->modelClass)::factory()->count($firstCount)->make();

        $firstCsvContents = $getCsvContents($firstItems);

        $this->travel(-5)->days();
        $this->importCsv($this->resourceName, $firstCsvContents);
        $this->travelBack();

        $tableName = ($this->modelClass)::getTableName();
        $this->assertDatabaseCount($tableName, $firstCount);

        $secondCount = round($limit * 0.75);
        $secondItems = $firstItems
            ->random($secondCount)
            ->each(function ($item) {
                $item->acme_id = ($this->modelClass)::factory()->make()->acme_id;
            });

        $secondCsvContents = $getCsvContents($secondItems);

        $this->travel(5)->days();
        $this->importCsv($this->resourceName, $secondCsvContents);
        $this->travelBack();

        $updatedCount = ($this->modelClass)::query()
            ->whereDate('updated_at', '>', now()->toDateString())
            ->count();

        $this->assertEquals($secondCount, $updatedCount);
    }
}
