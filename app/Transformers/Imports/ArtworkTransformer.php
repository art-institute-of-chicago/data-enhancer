<?php

namespace App\Transformers\Imports;

class ArtworkTransformer extends AbstractTransformer
{
    protected static $requiredFields = [
        'id' => 'integer',
        'title' => 'string',
        'dimensions' => 'string|null',
        'medium_display' => 'string|null',
    ];
}
