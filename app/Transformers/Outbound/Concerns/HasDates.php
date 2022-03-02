<?php

namespace App\Transformers\Outbound\Concerns;

use Carbon\CarbonInterface;

trait HasDates
{
    protected function getDateTime(?CarbonInterface $value)
    {
        return is_null($value)
            ? null
            : $value->toIso8601String();
    }
}
