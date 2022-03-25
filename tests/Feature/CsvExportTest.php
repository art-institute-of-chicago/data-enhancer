<?php

namespace Tests\Feature;

use App\Models\Agent;
use Tests\FeatureTestCase as BaseTestCase;

class CsvExportTest extends BaseTestCase
{
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
        $invalidId = Agent::factory()->getInvalidId();

        $response = $this->post('/csv/export', [
            'resource' => 'agents',
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
}
