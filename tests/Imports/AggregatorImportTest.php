<?php

namespace Tests\Api;

use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

use Tests\CreatesApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class AggregatorImportTest extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function test_it_runs_import_aggregator()
    {
        $since = Carbon::parse('10 min ago')->toIso8601String();

        Artisan::call("import:aggregator --since '{$since}'");
    }
}
