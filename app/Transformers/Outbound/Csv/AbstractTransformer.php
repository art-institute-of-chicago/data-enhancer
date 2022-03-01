<?php

namespace App\Transformers\Outbound\Csv;

use App\Transformers\Outbound\Concerns\HasDates;
use App\Transformers\AbstractTransformer as BaseTransformer;

abstract class AbstractTransformer extends BaseTransformer
{
    use HasDates;

    public function getFieldNames(): array
    {
        return array_keys($this->getFields());
    }
}
