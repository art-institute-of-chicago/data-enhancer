<?php

namespace Tests\Fakes;

use App\Transformers\Datum;
use App\Transformers\Outbound\Csv\Concerns\ToJson;
use App\Transformers\Outbound\Csv\AbstractTransformer;

class FakeOutboundCsvTransformer extends AbstractTransformer
{
    use ToJson;

    public function getFields()
    {
        return [
            'id' => null,
            'title' => null,
            'acme_id' => fn (Datum $datum) => $this->addPrefix($datum->acme_id, 'acme/'),
            'some_json' => fn (Datum $datum) => $this->toJson($datum->some_json),
            'updated_at' => fn (Datum $datum) => $this->getDateTime($datum->updated_at),
        ];
    }
}
