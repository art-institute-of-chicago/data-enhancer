<?php

namespace App\Transformers\Outbound;

use Carbon\CarbonInterface;
use Aic\Hub\Foundation\AbstractTransformer as BaseTransformer;

class AbstractTransformer extends BaseTransformer
{
    protected function getDateTime(?CarbonInterface $value)
    {
        return is_null($value)
            ? null
            : $value->toIso8601String();
    }
}
