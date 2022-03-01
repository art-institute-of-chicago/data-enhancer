<?php

return [
    'v1' => [
        'agents' => [
            'has_endpoint' => true,
            'model' => \App\Models\Agent::class,
            'transformer' => \App\Transformers\Outbound\Api\AgentTransformer::class,
        ],
        'artworks' => [
            'has_endpoint' => true,
            'model' => \App\Models\Artwork::class,
            'transformer' => \App\Transformers\Outbound\Api\ArtworkTransformer::class,
        ],
        'artwork-types' => [
            'has_endpoint' => true,
            'model' => \App\Models\ArtworkType::class,
            'transformer' => \App\Transformers\Outbound\Api\ArtworkTypeTransformer::class,
        ],
        'terms' => [
            'has_endpoint' => true,
            'model' => \App\Models\Term::class,
            'transformer' => \App\Transformers\Outbound\Api\TermTransformer::class,
        ],
    ],
];
