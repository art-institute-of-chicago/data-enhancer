<?php

namespace Tests\Feature;

use Carbon\Carbon;
use App\Models\Agent;
use App\Models\Artwork;
use App\Models\ArtworkType;
use App\Models\Term;
use App\Library\SourceConsumer;
use Illuminate\Http\UploadedFile;

use Tests\FeatureTestCase as BaseTestCase;

class CsvImportTest extends BaseTestCase
{
    private $oldUpdatedAt;
    private $newUpdatedAt;

    protected function setUp(): void
    {
        $this->oldUpdatedAt = Carbon::parse('10 minutes ago')->roundSecond()->toISOString();
        $this->newUpdatedAt = Carbon::parse('5 minutes ago')->roundSecond()->toISOString();

        parent::setUp();
    }

    public function test_it_shows_csv_import_form()
    {
        $response = $this->get('/csv/import');
        $response->assertSee('Import CSV');
    }

    public function test_it_shows_csv_export_form()
    {
        $response = $this->get('/csv/export');
        $response->assertSee('Export CSV');
    }

    public function test_it_imports_csv_for_agents()
    {
        $this->it_imports_csv_for_resource(
            Agent::class,
            'agents',
            [
                'id' => 1,
                'title' => 'Foobar',
                'birth_year' => 1950,
                'death_year' => 1999,
                'ulan_id' => 12345,
                'ulan_certainty' => 1,
                'source_updated_at' => $this->oldUpdatedAt,
            ],
            <<<END
            id,title,birth_year,death_year,ulan_id,ulan_certainty,source_updated_at
            1,Foobaz,1945,2000,ulan/67890,3,{$this->newUpdatedAt}
            END,
            [
                'id' => 1,
                'title' => 'Foobar',
                'birth_year' => 1950,
                'death_year' => 1999,
                'ulan_id' => 67890,
                'ulan_certainty' => 3,
                'source_updated_at' => $this->oldUpdatedAt,
            ]
        );
    }

    public function test_it_imports_csv_for_artworks()
    {
        $this->it_imports_csv_for_resource(
            Artwork::class,
            'artworks',
            [
                'id' => 1,
                'title' => 'Foobar',
                'dimension_display' => '5 × 5 × 5 cm',
                'width' => 5,
                'height' => 5,
                'depth' => 5,
                'medium_display' => 'Foobar',
                'support_aat_id' => 12345,
                'source_updated_at' => $this->oldUpdatedAt,
            ],
            <<<END
            id,title,dimension_display,width,height,depth,medium_display,support_aat_id,source_updated_at
            1,Foobaz,"10 × 10 × 10 cm",10,10,10,Foobaz,aat/67890,{$this->newUpdatedAt}
            END,
            [
                'id' => 1,
                'title' => 'Foobar',
                'dimension_display' => '5 × 5 × 5 cm',
                'width' => 10,
                'height' => 10,
                'depth' => 10,
                'medium_display' => 'Foobar',
                'support_aat_id' => 67890,
                'source_updated_at' => $this->oldUpdatedAt,
            ]
        );
    }

    public function test_it_imports_csv_for_artwork_types()
    {
        $this->it_imports_csv_for_resource(
            ArtworkType::class,
            'artwork-types',
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
        );
    }

    public function test_it_imports_csv_for_terms()
    {
        $this->it_imports_csv_for_resource(
            Term::class,
            'terms',
            [
                'id' => 'TM-1',
                'title' => 'Foobar',
                'aat_id' => 12345,
                'source_updated_at' => $this->oldUpdatedAt,
            ],
            <<<END
            id,title,aat_id,source_updated_at
            TM-1,Foobaz,aat/67890,{$this->newUpdatedAt}
            END,
            [
                'id' => 'TM-1',
                'title' => 'Foobar',
                'aat_id' => 67890,
                'source_updated_at' => $this->oldUpdatedAt,
            ]
        );
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
        $this->it_imports_csv('terms', $firstCsvContents);
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
        $this->it_imports_csv('terms', $secondCsvContents);
        $this->travelBack();

        $updatedCount = Term::whereDate('updated_at', '>', now()->toDateString())->count();

        $this->assertEquals($secondCount, $updatedCount);
    }

    private function it_imports_csv_for_resource(
        string $modelClass,
        string $resourceName,
        array $initialState,
        string $csvContents,
        array $expectedState
    ) {
        $initialItem = ($modelClass)::factory()->create($initialState);
        $id = $initialItem->getKey();

        $this->it_imports_csv($resourceName, $csvContents);

        $finalItem = ($modelClass)::find($id);
        $finalState = $finalItem->toArray();

        $this->assertEquals(
            $expectedState,
            array_intersect_key(
                $finalState,
                $expectedState
            )
        );
    }

    private function it_imports_csv(
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
