<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TransformerServiceProvider extends ServiceProvider
{
    private $transformerClasses = [
        \App\Transformers\Inbound\Api\AgentTransformer::class,
        \App\Transformers\Inbound\Api\ArtworkTransformer::class,
        \App\Transformers\Inbound\Api\ArtworkTypeTransformer::class,
        \App\Transformers\Inbound\Api\PlaceTransformer::class,
        \App\Transformers\Inbound\Api\TermTransformer::class,
        \App\Transformers\Inbound\Csv\AgentTransformer::class,
        \App\Transformers\Inbound\Csv\ArtworkTransformer::class,
        \App\Transformers\Inbound\Csv\ArtworkTypeTransformer::class,
        \App\Transformers\Inbound\Csv\PlaceTransformer::class,
        \App\Transformers\Inbound\Csv\TermTransformer::class,
    ];

    public function register()
    {
        foreach ($this->transformerClasses as $transformerClass) {
            $this->app->singleton($transformerClass, function () use ($transformerClass) {
                return new $transformerClass();
            });
        }
    }
}
