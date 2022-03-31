<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\Concerns\HasDates;

use Tests\Concerns\HasFakeModel;
use Illuminate\Support\Facades\Config;
use Tests\Fakes\FakeOutboundCsvTransformer;

use Tests\Csv\CsvExportTestCase as BaseTestCase;

class CsvExportTest extends BaseTestCase
{
    use HasFakeModel;
    use HasDates;

    protected $resourceName = 'foos';

    public function setUp(): void
    {
        parent::setUp();

        Config::set('aic.output.csv', [
            'resources' => [
                'foos' => [
                    'model' => $this->modelClass,
                    'transformer' => FakeOutboundCsvTransformer::class,
                ],
            ],
        ]);
    }

    public function data()
    {
        return [
            [
                [
                    'id' => 1,
                    'title' => 'Foobar',
                    'acme_id' => 1234,
                    'some_json' => (object) [
                        'foo' => 'bar',
                    ],
                    'updated_at' => now()->toDateTimeString(),
                ],
                [
                    'id' => '1',
                    'title' => 'Foobar',
                    'acme_id' => 'acme/1234',
                    'some_json' => '{"foo":"bar"}',
                    'updated_at' => now()->toIso8601String(),
                ]
            ],
            [
                [
                    'title' => null,
                    'acme_id' => null,
                    'some_json' => null,
                ],
                [
                    'title' => '',
                    'acme_id' => '',
                    'some_json' => 'null',
                ]
            ],
        ];
    }

    public function test_it_shows_csv_export_form()
    {
        $response = $this->get('/csv/export');
        $response->assertSee('Export CSV');
    }

    public function test_it_errors_on_missing_fields()
    {
        $response = $this->post('/csv/export');
        $response->assertSessionHasErrors([
            'resource' => 'The resource field is required.',
        ]);
    }

    public function test_it_errors_on_invalid_id()
    {
        $invalidId = ($this->modelClass)::factory()->getInvalidId();

        $response = $this->post('/csv/export', [
            'resource' => $this->resourceName,
            'ids' => $invalidId,
        ]);

        $response->assertSessionHasErrors([
            'ids' => 'IDs field contains an invalid ID: ' . $invalidId,
        ]);
    }

    public function test_it_errors_on_invalid_date()
    {
        $response = $this->post('/csv/export', [
            'resource' => $this->resourceName,
            'since' => 'foobar',
        ]);

        $response->assertSessionHasErrors([
            'since' => 'Cannot parse date from since field',
        ]);
    }

    public function test_it_exports_many_sorted_items()
    {
        $datums = ($this->modelClass)::factory()
            ->count(3)
            ->create()
            ->sortBy('id')
            ->values();

        $response = $this->post('/csv/export', [
            'resource' => $this->resourceName,
        ]);

        $csvReader = $this->getCsvReader();

        foreach ($csvReader as $offset => $record) {
            $datum = $datums[$offset - 1];

            $this->assertEquals($record, [
                'id' => (string) $datum->id,
                'title' => $datum->title,
                'acme_id' => 'acme/' . $datum->acme_id,
                'some_json' => '{"hello":"world"}',
                'updated_at' => $datum->updated_at->toIso8601String(),
            ]);
        }
    }

    public function test_it_exports_only_specific_ids()
    {
        $datums = ($this->modelClass)::factory()
            ->count(8)
            ->create();

        $chosenIds = $datums
            ->random(4)
            ->pluck('id')
            ->sort()
            ->values();

        $inputIds = implode(PHP_EOL, [
            $chosenIds->slice(0, 2)->implode(','),
            $chosenIds->slice(2, 2)->implode(PHP_EOL),
        ]);

        $response = $this->post('/csv/export', [
            'resource' => $this->resourceName,
            'ids' => $inputIds,
        ]);

        $csvReader = $this->getCsvReader();

        $exportedIds = array_map(
            fn ($record) => $record['id'],
            iterator_to_array($csvReader)
        );

        $this->assertEqualsCanonicalizing(
            $exportedIds,
            $chosenIds->all()
        );
    }

    public function test_it_exports_only_items_updated_since_date()
    {
        $this->travel(-5)->days();
        ($this->modelClass)::factory()->count(2)->create();
        $this->travelBack();

        $this->travel(-3)->days();
        ($this->modelClass)::factory()->count(2)->create();
        $this->travelBack();

        $sinceInput = '4 days ago';
        $sinceCarbon = Carbon::parse($sinceInput);

        $response = $this->post('/csv/export', [
            'resource' => $this->resourceName,
            'since' => $sinceInput,
        ]);

        $csvReader = $this->getCsvReader();

        $this->assertEquals(2, count(iterator_to_array($csvReader)));

        foreach ($csvReader as $record) {
            $this->assertTrue(
                Carbon::parse($record['updated_at'])->gt($sinceCarbon)
            );
        }
    }

    public function test_it_exports_only_items_where_field_is_blank()
    {
        ($this->modelClass)::factory()->count(2)->create();

        ($this->modelClass)::factory()->count(2)->create([
            'title' => null,
        ]);

        ($this->modelClass)::factory()->count(2)->create([
            'acme_id' => null,
        ]);

        ($this->modelClass)::factory()->count(2)->create([
            'title' => null,
            'acme_id' => null,
        ]);

        $response = $this->post('/csv/export', [
            'resource' => $this->resourceName,
            'blankFields' => [
                'title',
                'acme_id',
            ],
        ]);

        $csvReader = $this->getCsvReader();

        $this->assertEquals(6, count(iterator_to_array($csvReader)));

        foreach ($csvReader as $record) {
            $this->assertTrue(
                $record['title'] === '' || $record['acme_id'] === ''
            );
        }
    }

    public function test_it_exports_only_specific_fields()
    {
        ($this->modelClass)::factory()->create();

        $response = $this->post('/csv/export', [
            'resource' => $this->resourceName,
            'exportFields' => [
                'id',
                'title',
            ],
        ]);

        $csvReader = $this->getCsvReader();

        foreach ($csvReader as $record) {
            $this->assertTrue(empty($record['acme_id']));
            $this->assertTrue(empty($record['some_json']));
            $this->assertTrue(empty($record['updated_at']));
        }
    }
}
