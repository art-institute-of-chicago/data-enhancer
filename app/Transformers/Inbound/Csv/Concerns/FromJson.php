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

        $value = $this->decodeJson($value);

        return $this->toJson($value);
    }

    protected function prepDirtyCheckForFromJson($transformedDatum)
    {
        foreach (($this->jsonFields ?? []) as $jsonField) {
            if (isset($transformedDatum[$jsonField])) {
                $transformedDatum[$jsonField] = $this->decodeJson($transformedDatum[$jsonField]);
            }
        }

        return $transformedDatum;
    }

    private function decodeJson($value)
    {
        if (is_null($value)) {
            return;
        }

        return json_decode(
            json: $value,
            flags: JSON_THROW_ON_ERROR
        );
    }
}
