<?php

namespace Tests\Feature;

use Tests\FeatureTestCase as BaseTestCase;

class CsvExportTest extends BaseTestCase
{
    public function test_it_shows_csv_export_form()
    {
        $response = $this->get('/csv/export');
        $response->assertSee('Export CSV');
    }
}
