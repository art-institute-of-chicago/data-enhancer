<?php

namespace App\Transformers\Concerns;

trait CanPrefixValues
{
    protected function addPrefix($value, $prefix)
    {
        if (is_null($value)) {
            return $value;
        }

        return $prefix . $value;
    }

    protected function trimPrefix($value, $prefix)
    {
        if (!is_string($value)) {
            return $value;
        }

        if (substr($value, 0, strlen($prefix)) === $prefix) {
            $value = substr($value, strlen($prefix));
        }

        return $value;
    }
}
