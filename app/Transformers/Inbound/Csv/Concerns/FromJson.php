<?php

namespace App\Transformers\Inbound\Csv\Concerns;

use App\Transformers\Outbound\Csv\Concerns\ToJson;

trait FromJson
{
    use ToJson;

    /**
     * Validate, then convert back to JSON for bulk insert.
     */
    protected function fromJson($value)
    {
        if (is_null($value)) {
            return;
        }

        $value = json_decode(
            json: $value,
            flags: JSON_THROW_ON_ERROR
        );

        return $this->toJson($value);
    }
}
