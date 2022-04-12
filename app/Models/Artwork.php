<?php

namespace App\Models;

use Aic\Hub\Foundation\AbstractModel as BaseModel;

class Artwork extends BaseModel
{
    protected $dates = [
        'source_updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'dimension_display' => 'string',
        'width' => 'integer',
        'height' => 'integer',
        'depth' => 'integer',
        'medium_display' => 'string',
        'support_aat_id' => 'integer',
        'linked_art_json' => 'object',
        'source_updated_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
