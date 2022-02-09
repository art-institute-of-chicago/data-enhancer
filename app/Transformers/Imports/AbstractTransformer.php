<?php

namespace App\Transformers\Imports;

abstract class AbstractTransformer
{
    public static function getRequiredFields($withRules = false)
    {
        return $withRules ? static::$requiredFields : array_keys(static::$requiredFields);
    }
}
