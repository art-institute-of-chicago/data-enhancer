<?php

namespace Tests\Api;

use Carbon\Carbon;
use App\Models\Agent;
use App\Models\Artwork;
use App\Models\ArtworkType;
use App\Models\Term;
use Illuminate\Http\UploadedFile;

use Tests\FeatureTestCase as BaseTestCase;

class CsvTest extends BaseTestCase
{
    private $oldUpdatedAt;
    private $newUpdatedAt;

    protected function setUp(): void
    {
        $this->oldUpdatedAt = Carbon::parse('10 minutes ago')->roundSecond()->toISOString();
        $this->newUpdatedAt = Carbon::parse('5 minutes ago')->roundSecond()->toISOString();

        parent::setUp();
    }

    public function test_it_shows_csv_form()
    {
        $response = $this->get('/csv');

        $response->assertSee('Upload CSV');
    }

    public function test_it_imports_csv_for_agents()
    {
        Agent::factory()->create([
            'id' => 1,
            'title' => 'Foobar',
            'birth_year' => 1950,
            'death_year' => 1999,
            'ulan_id' => 12345,
            'ulan_certainty' => 1,
            'source_updated_at' => $this->oldUpdatedAt,
        ]);

        $csvFile = UploadedFile::fake()->createWithContent('agents.csv', <<<END
        id,title,birth_year,death_year,ulan_id,ulan_certainty,source_updated_at
        1,Foobaz,1945,2000,67890,3,{$this->newUpdatedAt}
        END);

        $response = $this->post('/csv/upload', [
            'resource' => 'agents',
            'csvFile' => $csvFile,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $agent = Agent::find(1);

        $this->assertEquals([
            'id' => 1,
            'title' => 'Foobar',
            'birth_year' => 1950,
            'death_year' => 1999,
            'ulan_id' => 67890,
            'ulan_certainty' => 3,
            'source_updated_at' => $this->oldUpdatedAt,
            'created_at' => $agent->created_at->toISOString(),
            'updated_at' => $agent->updated_at->toISOString(),
        ], $agent->toArray());
    }

    public function test_it_imports_csv_for_artworks()
    {
        Artwork::factory()->create([
            'id' => 1,
            'title' => 'Foobar',
            'dimension_display' => '5 × 5 × 5 cm',
            'width' => 5,
            'height' => 5,
            'depth' => 5,
            'medium_display' => 'Foobar',
            'support_aat_id' => 12345,
            'source_updated_at' => $this->oldUpdatedAt,
        ]);

        $csvFile = UploadedFile::fake()->createWithContent('artworks.csv', <<<END
        id,title,dimension_display,width,height,depth,medium_display,support_aat_id,source_updated_at
        1,Foobaz,"10 × 10 × 10 cm",10,10,10,Foobaz,67890,{$this->newUpdatedAt}
        END);

        $response = $this->post('/csv/upload', [
            'resource' => 'artworks',
            'csvFile' => $csvFile,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $artwork = Artwork::find(1);

        $this->assertEquals([
            'id' => 1,
            'title' => 'Foobar',
            'dimension_display' => '5 × 5 × 5 cm',
            'width' => 10,
            'height' => 10,
            'depth' => 10,
            'medium_display' => 'Foobar',
            'support_aat_id' => 67890,
            'source_updated_at' => $this->oldUpdatedAt,
            'created_at' => $artwork->created_at->toISOString(),
            'updated_at' => $artwork->updated_at->toISOString(),
        ], $artwork->toArray());
    }

    public function test_it_imports_csv_for_artwork_types()
    {
        ArtworkType::factory()->create([
            'id' => 1,
            'title' => 'Foobar',
            'aat_id' => 12345,
            'source_updated_at' => $this->oldUpdatedAt,
        ]);

        $csvFile = UploadedFile::fake()->createWithContent('artwork-types.csv', <<<END
        id,title,aat_id,source_updated_at
        1,Foobaz,67890,{$this->newUpdatedAt}
        END);

        $response = $this->post('/csv/upload', [
            'resource' => 'artwork-types',
            'csvFile' => $csvFile,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $artworkType = ArtworkType::find(1);

        $this->assertEquals([
            'id' => 1,
            'title' => 'Foobar',
            'aat_id' => 67890,
            'source_updated_at' => $this->oldUpdatedAt,
            'created_at' => $artworkType->created_at->toISOString(),
            'updated_at' => $artworkType->updated_at->toISOString(),
        ], $artworkType->toArray());
    }

    public function test_it_imports_csv_for_terms()
    {
        Term::factory()->create([
            'id' => 'TM-1',
            'title' => 'Foobar',
            'aat_id' => 12345,
            'source_updated_at' => $this->oldUpdatedAt,
        ]);

        $csvFile = UploadedFile::fake()->createWithContent('terms.csv', <<<END
        id,title,aat_id,source_updated_at
        TM-1,Foobaz,67890,{$this->newUpdatedAt}
        END);

        $response = $this->post('/csv/upload', [
            'resource' => 'terms',
            'csvFile' => $csvFile,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $term = Term::find('TM-1');

        $this->assertEquals([
            'id' => 'TM-1',
            'title' => 'Foobar',
            'aat_id' => 67890,
            'source_updated_at' => $this->oldUpdatedAt,
            'created_at' => $term->created_at->toISOString(),
            'updated_at' => $term->updated_at->toISOString(),
        ], $term->toArray());
    }
}
