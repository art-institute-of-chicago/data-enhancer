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

    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    public function getWebUrl()
    {
        if (!$this->publication) {
            return;
        }

        return sprintf(
            '%s/section/%s',
            $this->publication->getWebUrl(),
            $this->source_id
        );
    }
}
