<?php

namespace App\Models;

use Aic\Hub\Foundation\AbstractModel as BaseModel;

class Section extends BaseModel
{
    protected $dates = [
        'source_updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'accession' => 'string',
        'artwork_id' => 'integer',
        'source_id' => 'integer',
        'publication_id' => 'integer',
        'content' => 'string',
        'source_updated_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
