<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Aic\Hub\Foundation\Middleware\TrustProxies;
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
            ->middleware([
                'web',
                'basic_auth',
                TrustProxies::class,
            ])
            ->namespace($this->namespace)
            ->group(base_path('routes/csv.php'));
    }
}
