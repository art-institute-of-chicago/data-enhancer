<?php

namespace App\Models;

use App\Enums\TermType;
use Aic\Hub\Foundation\AbstractModel as BaseModel;

class Term extends BaseModel
{
    protected $dates = [
        'source_updated_at',
    ];

    protected $casts = [
        'id' => 'string',
        'title' => 'string',
        'subtype' => TermType::class,
        'aat_id' => 'integer',
        'aat_xml' => 'string',
        'source_updated_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function validateId($id)
    {
        return is_string($id)
            && in_array(substr($id, 0, 2), ['TM', 'PC'])
            && is_numeric(substr($id, 3));
    }
}
