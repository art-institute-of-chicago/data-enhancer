<?php

namespace App\Models;

use Aic\Hub\Foundation\AbstractModel as BaseModel;

class Place extends BaseModel
{
    protected $dates = [
        'source_updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'latitude' => 'double',
        'longitude' => 'double',
        'tgn_id' => 'integer',
        'tgn_xml' => 'string',
        'source_updated_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Place ids can be negative.
     */
    public static function validateId($id)
    {
        return is_numeric($id);
    }
}
