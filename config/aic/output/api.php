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
        'places' => [
            'has_endpoint' => true,
            'model' => \App\Models\Place::class,
            'transformer' => \App\Transformers\Outbound\Api\PlaceTransformer::class,
        ],
        'publications' => [
            'has_endpoint' => true,
            'model' => \App\Models\Publication::class,
            'transformer' => \App\Transformers\Outbound\Api\PublicationTransformer::class,
        ],
        'sections' => [
            'has_endpoint' => true,
            'model' => \App\Models\Section::class,
            'transformer' => \App\Transformers\Outbound\Api\SectionTransformer::class,
        ],
        'terms' => [
            'has_endpoint' => true,
            'model' => \App\Models\Term::class,
            'transformer' => \App\Transformers\Outbound\Api\TermTransformer::class,
        ],
    ],
];
