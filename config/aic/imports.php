<?php

return [
    'debug' => (bool) env('IMPORTS_DEBUG', false),
    'limit' => env('IMPORTS_LIMIT') ?: 100,
    'sources' => [
        'aggregator' => [
            'is_api' => true,
            'base_uri' => env('AGGREGATOR_BASE_URI', 'https://api.artic.edu/api/v1'),
            'api_token' => env('AGGREGATOR_API_TOKEN'),
            'resources' => [
                'agents' => [
                    'has_endpoint' => true,
                    'model' => \App\Models\Agent::class,
                    'transformer' => \App\Transformers\Inbound\Api\AgentTransformer::class,
                ],
                'artworks' => [
                    'has_endpoint' => true,
                    'model' => \App\Models\Artwork::class,
                    'transformer' => \App\Transformers\Inbound\Api\ArtworkTransformer::class,
                ],
                'artwork-types' => [
                    'has_endpoint' => true,
                    'model' => \App\Models\ArtworkType::class,
                    'transformer' => \App\Transformers\Inbound\Api\ArtworkTypeTransformer::class,
                ],
                'category-terms' => [
                    'has_endpoint' => true,
                    'model' => \App\Models\Term::class,
                    'transformer' => \App\Transformers\Inbound\Api\TermTransformer::class,
                ],
            ],
        ],

        'csv' => [
            'is_api' => false,
            'resources' => [
                'agents' => [
                    'model' => \App\Models\Agent::class,
                    'transformer' => \App\Transformers\Inbound\Csv\AgentTransformer::class,
                ],
                'artworks' => [
                    'model' => \App\Models\Artwork::class,
                    'transformer' => \App\Transformers\Inbound\Csv\ArtworkTransformer::class,
                ],
                'artwork-types' => [
                    'model' => \App\Models\ArtworkType::class,
                    'transformer' => \App\Transformers\Inbound\Csv\ArtworkTypeTransformer::class,
                ],
                'category-terms' => [
                    'model' => \App\Models\Term::class,
                    'transformer' => \App\Transformers\Inbound\Csv\TermTransformer::class,
                ],
            ],
        ],
    ],
];
