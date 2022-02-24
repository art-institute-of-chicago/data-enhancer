<?php

namespace App\Http\Controllers;

use App\Jobs\ImportCsv;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class CsvController extends BaseController
{
    public function index()
    {
        $resources = array_map(function ($resource) {
            return [
                'name' => $resource,
                'selected' => old('resource') === $resource,
            ];
        }, [
            'agents',
            'artworks',
            'artwork-types',
            'terms',
        ]);

        return view('csv', [
            'resources' => $resources,
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'resource' => 'required',
            'csvFile' => 'required',
        ]);

        $file = $request->file('csvFile');

        ImportCsv::dispatch(
            $request->resource,
            $file->path(),
        );

        return back()
            ->with('success', 'File has been uploaded, import in progress.')
            ->with('file', $file->getClientOriginalName());
    }
}
