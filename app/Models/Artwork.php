<?php

namespace App\Models;

use Aic\Hub\Foundation\AbstractModel as BaseModel;

class Artwork extends BaseModel
{
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'dimension_display' => 'string',
        'width' => 'integer',
        'height' => 'integer',
        'depth' => 'integer',
        'medium_display' => 'string',
        'support_aat_id' => 'integer',
    ];
}
