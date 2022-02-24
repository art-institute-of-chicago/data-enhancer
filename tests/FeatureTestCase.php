<?php

namespace Tests;

use Tests\CreatesApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class FeatureTestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;
}
