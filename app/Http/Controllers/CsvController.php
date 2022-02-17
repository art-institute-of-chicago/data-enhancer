<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class CsvController extends BaseController
{
    public function index()
    {
        return view('csv');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'resource' => 'required',
            'csvFile' => 'required|mimes:csv',
        ]);
    }
}
