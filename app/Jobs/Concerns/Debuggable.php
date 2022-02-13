<?php

namespace App\Jobs\Concerns;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

trait Debuggable
{
    /**
     * @link https://stackoverflow.com/a/59849075
     */
    protected function debug($value)
    {
        if (config('aic.imports.debug') && App::runningInConsole()) {
            Log::channel('stderr')->info($value);
        }
    }
}
