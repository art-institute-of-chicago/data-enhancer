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
        'terms' => [
            'model' => \App\Models\Term::class,
            'transformer' => \App\Transformers\Outbound\Csv\TermTransformer::class,
        ],
    ],
];
