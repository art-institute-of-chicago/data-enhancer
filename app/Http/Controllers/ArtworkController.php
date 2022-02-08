<?php

namespace App\Http\Controllers;

use Aic\Hub\Foundation\AbstractController as BaseController;

class ArtworkController extends BaseController
{
    protected $model = \App\Models\Artwork::class;

    protected $transformer = \App\Transformers\Api\ArtworkTransformer::class;
}
