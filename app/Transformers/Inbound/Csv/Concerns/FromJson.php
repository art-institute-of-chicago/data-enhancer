<?php

namespace App\Transformers\Inbound\Csv\Concerns;

use App\Transformers\Outbound\Csv\Concerns\ToJson;

trait FromJson
{
    use ToJson;

    /**
     * Convert JSON to object to match model.
     */
    protected function fromJson($value)
    {
        if (is_null($value)) {
            return;
        }

        return json_decode(
            json: $value,
            flags: JSON_THROW_ON_ERROR
        );
    }

    /**
     * Convert object back to JSON for bulk insert.
     */
    protected function prepBulkInsertForFromJson($transformedDatum)
    {
        $jsonFields = $this->getTaggedFields('json');

        foreach ($jsonFields as $jsonField) {
            if (isset($transformedDatum[$jsonField])) {
                $transformedDatum[$jsonField] = $this->toJson($transformedDatum[$jsonField]);
            }
        }

        return $transformedDatum;
    }
}
