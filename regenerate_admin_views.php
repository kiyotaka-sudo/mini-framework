<?php
require 'bootstrap.php';
use App\Core\Generators\ViewGenerator;
use App\Core\CodeGenerator;

echo "---[ Admin View Regeneration ]---\n";

// Re-generate Admin Views for Artist, Album, Song to fix CSS
$entities = [
    [
        'name' => 'artist',
        'table' => 'artists',
        'fields' => [
            ['name' => 'name', 'type' => 'string'],
            ['name' => 'bio', 'type' => 'text'],
            ['name' => 'image_url', 'type' => 'string']
        ]
    ],
    [
        'name' => 'album',
        'table' => 'albums',
        'fields' => [
            ['name' => 'name', 'type' => 'string'],
            ['name' => 'artist_id', 'type' => 'integer'],
            ['name' => 'release_date', 'type' => 'date'],
            ['name' => 'cover_url', 'type' => 'string']
        ]
    ],
    [
        'name' => 'song',
        'table' => 'songs',
        'fields' => [
            ['name' => 'title', 'type' => 'string'],
            ['name' => 'album_id', 'type' => 'integer'],
            ['name' => 'duration', 'type' => 'integer'],
            ['name' => 'track_number', 'type' => 'integer'],
            ['name' => 'file_url', 'type' => 'string']
        ]
    ]
];

$generator = new ViewGenerator();
foreach ($entities as $entity) {
    echo "Regenerating views for: " . $entity['name'] . "\n";
    $generator->generate(['entities' => [$entity]]);
}

echo "✅ Done! Admin views updated to GitHub Theme.\n";
