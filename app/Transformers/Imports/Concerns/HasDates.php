<?php

namespace App\Transformers\Imports\Concerns;

use Carbon\Carbon;

trait HasDates
{
    protected function getDateTime($value)
    {
        return Carbon::parse($value);
    }
}
