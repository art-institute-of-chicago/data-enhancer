<?php

namespace App\Http\Controllers;

use Aic\Hub\Foundation\AbstractController as BaseController;

class AgentController extends BaseController
{
    protected $model = \App\Models\Agent::class;

    protected $transformer = \App\Transformers\Api\AgentTransformer::class;
}
