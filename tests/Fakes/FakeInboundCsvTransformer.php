<?php

namespace Tests\Fakes;

use App\Transformers\Datum;
use App\Transformers\Inbound\Csv\Concerns\FromJson;
use App\Transformers\Inbound\AbstractTransformer;

class FakeInboundCsvTransformer extends AbstractTransformer
{
    use FromJson;

    public function getFields()
    {
        return [
            'id' => null,
            'acme_id' => fn (Datum $datum) => $this->trimPrefix($datum->acme_id, 'acme/'),
            'some_json' => fn (Datum $datum) => $this->fromJson($datum->some_json),
        ];
    }
}
