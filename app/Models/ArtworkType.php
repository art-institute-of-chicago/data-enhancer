<?php

namespace App\Models;

use Aic\Hub\Foundation\AbstractModel as BaseModel;

class ArtworkType extends BaseModel
{
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'aat_id' => 'integer',
        'source_updated_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}