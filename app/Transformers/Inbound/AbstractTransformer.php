<?php

namespace App\Transformers\Inbound;

use App\Transformers\Inbound\Concerns\HasDates;
use App\Transformers\AbstractTransformer as BaseTransformer;

abstract class AbstractTransformer extends BaseTransformer
{
    use HasDates;
}
