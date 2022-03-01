<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use App\Jobs\ImportCsv;
use App\Jobs\ExportCsv;
use App\Models\CsvFile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class CsvController extends BaseController
{
    public function listFiles()
    {
        $csvFiles = CsvFile::query()->byLastMod()->get();

        return view('files', [
            'navLinks' => $this->getNavLinks(),
            'csvFiles' => $csvFiles,
        ]);
    }

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

    /**
     * Validate the free-text input fields, but assume that
     * select and checkbox fields contain only valid values.
     */
    public function exportAction(Request $request)
    {
        $request->validate([
            'resource' => 'required',
        ]);

        $ids = null;

        if (!empty($request->ids)) {
            $ids = collect(preg_split('/\r\n|\r|\n|,/', $request->ids))
                ->map('trim')
                ->filter()
                ->values();

            $modelClass = config('aic.output.csv.resources.' . $request->resource . '.model');
            $model = ($modelClass)::instance();

            $ids->each(function ($id) use ($model, $request) {
                if (!$model->validateId($id)) {
                    $request->validate(['ids' => function ($attribute, $value, $fail) {
                        $fail('IDs field contains an invalid ID');
                    }]);
                }
            });

            $ids = $ids->all();
        }

        if (!empty($request->since)) {
            $request->validate([
                'since' => function ($attribute, $value, $fail) {
                    try {
                        Carbon::parse($value);
                    } catch (Throwable $e) {
                        $fail('Cannot parse date from since field');
                    }
                }
            ]);
        }

        ExportCsv::dispatch(
            $request->resource,
            $ids,
            $request->since,
            $request->blankFields,
            $request->exportFields,
        );

        return back()
            ->with('success', 'Generating CSV file. This may take a few minutes. Check the Files section.');
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
            [
                'title' => 'Files',
                'route' => 'csv.list',
            ],
        ]);
    }
}
