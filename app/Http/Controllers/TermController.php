<?php

namespace App\Http\Controllers;

use Aic\Hub\Foundation\AbstractController as BaseController;

class TermController extends BaseController
{
    protected $model = \App\Models\Term::class;

    protected $transformer = \App\Transformers\Api\TermTransformer::class;
}
