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
            'navLinks' => $this->getNavLinks(),
            'resources' => $resources,
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

    public function exportForm()
    {
        $resourceConfigs = config('aic.output.csv.resources');

        $resources = array_map(function ($resource) {
            return [
                'name' => $resource,
                'selected' => old('resource') === $resource,
            ];
        }, array_keys($resourceConfigs));

        $fieldLists = array_map(function ($resourceConfig) {
            $fieldList = (new $resourceConfig['transformer']())->getFieldNames();

            return array_values(array_diff($fieldList, [
                ($resourceConfig['model'])::instance()->getKeyName(),
            ]));
        }, $resourceConfigs);

        return view('export', [
            'navLinks' => $this->getNavLinks(),
            'resources' => $resources,
            'fieldLists' => json_encode($fieldLists),
        ]);
    }

    public function exportAction(Request $request)
    {
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
