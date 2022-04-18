<?php

namespace App\Transformers\Inbound;

use App\Transformers\Inbound\Concerns\HasDates;
use App\Transformers\AbstractTransformer as BaseTransformer;

abstract class AbstractTransformer extends BaseTransformer
{
    use HasDates;

    public function prepDirtyCheck(array $transformedDatum): array
    {
        foreach (class_uses_recursive($this) as $trait) {
            if (method_exists($this, $method = 'prepDirtyCheckFor' . class_basename($trait))) {
                $transformedDatum = $this->{$method}($transformedDatum);
            }
        }

        return $transformedDatum;
    }

    public function prepBulkInsert(array $transformedDatum): array
    {
        foreach (class_uses_recursive($this) as $trait) {
            if (method_exists($this, $method = 'prepBulkInsertFor' . class_basename($trait))) {
                $transformedDatum = $this->{$method}($transformedDatum);
            }
        }

        return $transformedDatum;
    }
}
