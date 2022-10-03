<?php

return [
    'resources' => [
        'agents' => [
            'model' => \App\Models\Agent::class,
            'transformer' => \App\Transformers\Outbound\Csv\AgentTransformer::class,
        ],
        'artworks' => [
            'model' => \App\Models\Artwork::class,
            'transformer' => \App\Transformers\Outbound\Csv\ArtworkTransformer::class,
        ],
        'artwork-types' => [
            'model' => \App\Models\ArtworkType::class,
            'transformer' => \App\Transformers\Outbound\Csv\ArtworkTypeTransformer::class,
        ],
        'places' => [
            'model' => \App\Models\Place::class,
            'transformer' => \App\Transformers\Outbound\Csv\PlaceTransformer::class,
        ],
        'publications' => [
            'model' => \App\Models\Publication::class,
            'transformer' => \App\Transformers\Outbound\Csv\PublicationTransformer::class,
        ],
        'sections' => [
            'model' => \App\Models\Section::class,
            'transformer' => \App\Transformers\Outbound\Csv\SectionTransformer::class,
        ],
        'terms' => [
            'model' => \App\Models\Term::class,
            'transformer' => \App\Transformers\Outbound\Csv\TermTransformer::class,
        ],
    ],
];
