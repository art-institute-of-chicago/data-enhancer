<?php

namespace App\Providers;

use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Allow access to Horizon without having a logged in user.
     */
    protected function authorization()
    {
        Horizon::auth(function ($request) {
            return true;
        });
    }
}
