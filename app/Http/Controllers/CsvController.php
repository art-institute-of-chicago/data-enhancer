<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class CsvController extends BaseController
{
    public function index()
    {
        return view('csv');
    }
}
