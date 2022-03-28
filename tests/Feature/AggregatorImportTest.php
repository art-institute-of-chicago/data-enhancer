<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

use Aic\Hub\Foundation\Testing\FeatureTestCase as BaseTestCase;

class AggregatorImportTest extends BaseTestCase
{
    public function test_it_runs_import_aggregator()
    {
        $since = Carbon::parse('10 min ago')->toIso8601String();

        Artisan::call("import:aggregator --since '{$since}'");

        $this->addToAssertionCount(1);
    }
}
