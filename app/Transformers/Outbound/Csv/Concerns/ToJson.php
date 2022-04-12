<?php

namespace App\Transformers\Outbound\Csv\Concerns;

trait ToJson
{
    protected function toJson(mixed $value): string
    {
        return json_encode(
            value: $value,
            flags: JSON_THROW_ON_ERROR
        );
    }
}
