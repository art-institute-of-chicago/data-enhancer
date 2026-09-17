<?php

return [
    /*
     * Foundation Service Providers...
     */
    Aic\Hub\Foundation\Providers\DefaultConfigServiceProvider::class,
    Aic\Hub\Foundation\Providers\ResourceServiceProvider::class,

    /*
     * Application Service Providers...
     */
    App\Providers\HorizonServiceProvider::class,
    App\Providers\GuzzleServiceProvider::class,
    App\Providers\TransformerServiceProvider::class,
];
