<?php

namespace Tests\Feature;

use Tests\Concerns\HasCsvReader;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Artisan;

use Carbon\Carbon;

use App\Transformers\Datum;
use Aic\Hub\Foundation\AbstractFactory as BaseFactory;
use Aic\Hub\Foundation\AbstractModel as BaseModel;
use App\Transformers\Outbound\Csv\AbstractTransformer as BaseTransformer;

use Aic\Hub\Foundation\Testing\FeatureTestCase as BaseTestCase;

class CsvExportTest extends BaseTestCase
{
    use HasCsvReader;

    private $modelClass;

    private $resource = 'foos';

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('foos', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->text('title')->nullable();
            $table->integer('acme_id')->nullable();
            $table->timestamps();
        });

        $modelClass = new class() extends BaseModel {
            protected $table = 'foos';

            protected $casts = [
                'id' => 'integer',
                'title' => 'string',
                'acme_id' => 'integer',
                'updated_at' => 'datetime',
            ];

            public static $factoryClass;

            protected static function newFactory()
            {
                return (static::$factoryClass)::new();
            }
        };

        $factoryClass = new class() extends BaseFactory {
            public function definition()
            {
                return [
                    'id' => $this->getValidId(),
                    'title' => $this->getTitle(),
                    'acme_id' => $this->getNumericId(),
                ];
            }

            public function nullable()
            {
                return $this->state(fn (array $attributes) => [
                    'title' => null,
                    'acme_id' => null,
                ]);
            }

            public static $modelClass;

            public function modelName()
            {
                return static::$modelClass;
            }
        };

        // https://stackoverflow.com/a/49038436
        ($modelClass)::$factoryClass = $factoryClass;
        ($factoryClass)::$modelClass = $modelClass;

        $transformerClass = new class() extends BaseTransformer {
            public function getFields()
            {
                return [
                    'id' => null,
                    'title' => null,
                    'acme_id' => fn (Datum $datum) => $this->addPrefix($datum->acme_id, 'acme/'),
                    'updated_at' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
                ];
            }
        };

        Config::set('aic.output.csv', [
            'resources' => [
                'foos' => [
                    'model' => $modelClass,
                    'transformer' => $transformerClass,
                ],
            ],
        ]);

        $this->modelClass = $modelClass;
    }

    public function tearDown(): void
    {
        Artisan::call('csv:clear');

        parent::tearDown();
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
            'resource' => $this->resource,
            'ids' => $invalidId,
        ]);

        $response->assertSessionHasErrors([
            'ids' => 'IDs field contains an invalid ID: ' . $invalidId,
        ]);
    }

    public function test_it_errors_on_invalid_date()
    {
        $response = $this->post('/csv/export', [
            'resource' => $this->resource,
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
            'resource' => $this->resource,
        ]);

        $csvReader = $this->getCsvReader();

        foreach ($csvReader as $offset => $record) {
            $datum = $datums[$offset - 1];

            $this->assertEquals($record, [
                'id' => (string) $datum->id,
                'title' => $datum->title,
                'acme_id' => 'acme/' . $datum->acme_id,
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
            'resource' => $this->resource,
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
            'resource' => $this->resource,
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
            'resource' => $this->resource,
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
            'resource' => $this->resource,
            'exportFields' => [
                'id',
                'title',
            ],
        ]);

        $csvReader = $this->getCsvReader();

        foreach ($csvReader as $record) {
            $this->assertTrue(empty($record['acme_id']));
            $this->assertTrue(empty($record['updated_at']));
        }
    }
}
