<?php

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class GuzzleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ClientInterface::class, function () {
            return new Client([
                'verify' => false,
            ]);
        });
    }
}
