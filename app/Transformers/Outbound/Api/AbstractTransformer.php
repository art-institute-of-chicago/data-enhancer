<?php

namespace App\Transformers\Outbound\Api;

use App\Transformers\Outbound\Concerns\HasDates;
use Aic\Hub\Foundation\AbstractTransformer as BaseTransformer;

abstract class AbstractTransformer extends BaseTransformer
{
    use HasDates;
}
