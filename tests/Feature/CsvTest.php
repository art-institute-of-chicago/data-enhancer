<?php

namespace Tests\Api;

use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

use Tests\FeatureTestCase as BaseTestCase;

class CsvTest extends BaseTestCase
{
    public function test_it_shows_csv_form()
    {
        $response = $this->get('/csv');

        $response->assertSee('Upload CSV');
    }
}
