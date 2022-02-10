<?php

namespace App\Providers;

use App\Transformers\Imports\AgentTransformer;
use App\Transformers\Imports\ArtworkTransformer;
use App\Transformers\Imports\ArtworkTypeTransformer;
use App\Transformers\Imports\TermTransformer;
use Illuminate\Support\ServiceProvider;

class TransformerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AgentTransformer::class, function () {
            return new AgentTransformer();
        });

        $this->app->singleton(ArtworkTransformer::class, function () {
            return new ArtworkTransformer();
        });

        $this->app->singleton(ArtworkTypeTransformer::class, function () {
            return new ArtworkTypeTransformer();
        });

        $this->app->singleton(TermTransformer::class, function () {
            return new TermTransformer();
        });
    }
}
