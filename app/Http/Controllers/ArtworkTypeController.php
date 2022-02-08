<?php

namespace App\Http\Controllers;

use Aic\Hub\Foundation\AbstractController as BaseController;

class ArtworkTypeController extends BaseController
{
    protected $model = \App\Models\ArtworkType::class;

    protected $transformer = \App\Transformers\Api\ArtworkTypeTransformer::class;
}
