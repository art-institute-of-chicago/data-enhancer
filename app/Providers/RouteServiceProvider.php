<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Aic\Hub\Foundation\Providers\RouteServiceProvider as BaseServiceProvider;

class RouteServiceProvider extends BaseServiceProvider
{
    public function map()
    {
        parent::map();

        $this->mapCsvRoutes();
    }

    protected function mapCsvRoutes()
    {
        Route::prefix('csv')
            ->namespace($this->namespace)
            ->group(base_path('routes/csv.php'));
    }
}
