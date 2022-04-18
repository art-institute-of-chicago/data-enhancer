<?php

namespace App\Transformers\Inbound;

use App\Transformers\Inbound\Concerns\HasDates;
use App\Transformers\AbstractTransformer as BaseTransformer;

abstract class AbstractTransformer extends BaseTransformer
{
    use HasDates;

    public function prepDirtyCheck(array $transformedDatum)
    {
        foreach (class_uses_recursive($this) as $trait) {
            if (method_exists($this, $method = 'prepDirtyCheckFor' . class_basename($trait))) {
                $transformedDatum = $this->{$method}($transformedDatum);
            }
        }

        return $transformedDatum;
    }
}
