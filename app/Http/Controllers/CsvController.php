<?php

namespace App\Http\Controllers;

use App\Jobs\ImportCsv;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class CsvController extends BaseController
{
    public function importForm()
    {
        $resources = array_map(function ($resource) {
            return [
                'name' => $resource,
                'selected' => old('resource') === $resource,
            ];
        }, array_keys(config('aic.imports.sources.csv.resources')));

        return view('import', [
            'resources' => $resources,
            'navLinks' => $this->getNavLinks(),
        ]);
    }

    public function importAction(Request $request)
    {
        $request->validate([
            'resource' => 'required',
            'csvFile' => 'required',
        ]);

        $path = $request->file('csvFile')->store('imports');

        ImportCsv::dispatch(
            $request->resource,
            $path,
        );

        return back()
            ->with('success', 'File has been uploaded, import in progress.');
    }

    private function getNavLinks()
    {
        return array_map(function ($navLink) {
            $navLink['is_active'] = request()->routeIs($navLink['route']);
            $navLink['href'] = route($navLink['route']);

            return $navLink;
        }, [
            [
                'title' => 'Import',
                'route' => 'csv.import.form',
            ],
            [
                'title' => 'Export',
                'route' => 'csv.export.form',
            ],
        ]);
    }
}
