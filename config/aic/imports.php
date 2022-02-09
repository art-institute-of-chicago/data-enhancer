<?php

return [
    'aggregator' => [
        'base_uri' => env('AGGREGATOR_BASE_URI', 'https://api.artic.edu/api/v1'),
        'api_token' => env('AGGREGATOR_API_TOKEN'),
        'resources' => [
            'agents' => [
                'has_endpoint' => true,
                'model' => \App\Models\Agent::class,
                'transformer' => \App\Transformers\Imports\AgentTransformer::class,
            ],
            'artworks' => [
                'has_endpoint' => true,
                'model' => \App\Models\Artwork::class,
                'transformer' => \App\Transformers\Imports\ArtworkTransformer::class,
            ],
            'artwork-types' => [
                'has_endpoint' => true,
                'model' => \App\Models\ArtworkType::class,
                'transformer' => \App\Transformers\Imports\ArtworkTypeTransformer::class,
            ],
            'category-terms' => [
                'has_endpoint' => true,
                'model' => \App\Models\Term::class,
                'transformer' => \App\Transformers\Imports\TermTransformer::class,
            ],
        ],
    ],
];
