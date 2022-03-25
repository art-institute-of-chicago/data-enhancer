<?php

namespace Tests\Feature;

use League\Csv\Reader;
use App\Models\CsvFile;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Artisan;

use App\Transformers\Datum;
use Aic\Hub\Foundation\AbstractFactory as BaseFactory;
use Aic\Hub\Foundation\AbstractModel as BaseModel;
use App\Transformers\Outbound\Csv\AbstractTransformer as BaseTransformer;

use Tests\FeatureTestCase as BaseTestCase;

class CsvExportTest extends BaseTestCase
{
    private $modelClass;

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
            'resource' => 'foos',
            'ids' => $invalidId,
        ]);

        $response->assertSessionHasErrors([
            'ids' => 'IDs field contains an invalid ID: ' . $invalidId,
        ]);
    }

    public function test_it_errors_on_invalid_date()
    {
        $response = $this->post('/csv/export', [
            'resource' => 'foos',
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
            'resource' => 'foos',
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
            ->count(12)
            ->create();

        $chosenIds = $datums
            ->random(6)
            ->pluck('id')
            ->sort()
            ->values();

        $inputIds = implode(PHP_EOL, [
            $chosenIds->slice(0, 3)->implode(','),
            $chosenIds->slice(3, 3)->implode(PHP_EOL),
        ]);

        $response = $this->post('/csv/export', [
            'resource' => 'foos',
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

    private function getCsvReader()
    {
        $csvFile = CsvFile::first();
        $csvPath = Storage::disk('public')->path($csvFile->filename);

        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);

        return $csv;
    }
}
