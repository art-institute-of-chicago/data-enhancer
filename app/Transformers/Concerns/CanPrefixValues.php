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

    protected function trimPrefix($value, array|string $prefixes)
    {
        if (!is_string($value)) {
            return $value;
        }

        if (!is_array($prefixes)) {
            $prefixes = [$prefixes];
        }

        foreach ($prefixes as $prefix) {
            if (substr($value, 0, strlen($prefix)) === $prefix) {
                $value = substr($value, strlen($prefix));
            }
        }

        return $value;
    }
}
