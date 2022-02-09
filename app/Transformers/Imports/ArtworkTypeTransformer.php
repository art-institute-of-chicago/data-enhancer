<?php

namespace App\Transformers\Imports;

class ArtworkTypeTransformer extends AbstractTransformer
{
    protected static $requiredFields = [
        'id' => 'integer',
        'title' => 'string',
    ];
}
