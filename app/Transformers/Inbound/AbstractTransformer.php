<?php

namespace App\Transformers\Inbound;

use App\Transformers\Inbound\Concerns\HasDates;
use App\Transformers\AbstractTransformer as BaseTransformer;

abstract class AbstractTransformer extends BaseTransformer
{
    use HasDates;

    protected $primaryKey = 'id';

    public function beforeDirtyCheck(array $transformedDatum): array
    {
        $savedDatum = [];

        foreach (class_uses_recursive($this) as $trait) {
            if (method_exists($this, $method = 'beforeDirtyCheckFor' . class_basename($trait))) {
                [$transformedDatum, $partialSavedDatum] = $this->{$method}($transformedDatum);
                $savedDatum = array_merge($savedDatum, $partialSavedDatum);
            }
        }

        if (isset($transformedDatum[$this->primaryKey])) {
            unset($transformedDatum[$this->primaryKey]);
        }

        return [$transformedDatum, $savedDatum];
    }
}
