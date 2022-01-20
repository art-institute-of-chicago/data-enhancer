<?php

namespace App\Http\Controllers;

use App\Bar;
use App\Foo;

use Illuminate\Http\Request;

use Aic\Hub\Foundation\AbstractController as BaseController;

class BarController extends BaseController
{
    protected $model = \App\Bar::class;

    protected $transformer = \App\Http\Transformers\BarTransformer::class;

    // foos/{id}/bars
    public function indexForFoo(Request $request, $id)
    {
        return $this->collect($request, function ($limit, $id) {
            return Foo::findOrFail($id)->bars;
        });
    }

    // foos/{foo_id}/bars/{id}
    public function showForFoo(Request $request, $foo_id, $id)
    {
        return $this->select($request, function ($id) use ($foo_id) {
            return Bar::find($id)->where('foo_id', $foo_id)->firstOrFail();
        });
    }
}
