<?php
require 'bootstrap.php';
use Database\Models\Artist;
use Database\Models\Album;
use Database\Models\Song;

echo "Seeding Spotify data...\n";

$artistModel = Artist::make();
$albumModel = Album::make();
$songModel = Song::make();

// Artists
$artists = [
    ['name' => 'The Weeknd', 'bio' => 'Abel Makkonen Tesfaye is a Canadian singer-songwriter.', 'image_url' => 'https://i.scdn.co/image/ab6761610000e5ebc834a369805d76d63420cc51'],
    ['name' => 'Dua Lipa', 'bio' => 'Dua Lipa is an English and Albanian singer and songwriter.', 'image_url' => 'https://i.scdn.co/image/ab6761610000e5eb1493027b4af4556488ee24c4'],
    ['name' => 'Drake', 'bio' => 'Aubrey Drake Graham is a Canadian rapper, singer, and songwriter.', 'image_url' => 'https://i.scdn.co/image/ab6761610000e5eb4293385d324da85581797c31']
];

foreach ($artists as $a) {
    echo "Creating artist: {$a['name']}\n";
    $artist = $artistModel->create($a);
    
    // Create an album for each artist
    $albumName = "{$a['name']} Essentials";
    echo "Creating album: {$albumName}\n";
    $album = $albumModel->create([
        'name' => $albumName,
        'artist_id' => $artist['id'],
        'release_date' => date('Y-m-d'),
        'cover_url' => $a['image_url']
    ]);
    
    // Create 3 songs for each album
    for ($i = 1; $i <= 3; $i++) {
        $songTitle = "{$a['name']} Song #$i";
        echo "Creating song: {$songTitle}\n";
        $songModel->create([
            'title' => $songTitle,
            'album_id' => $album['id'],
            'duration' => rand(180, 240),
            'track_number' => $i,
            'file_url' => '#'
        ]);
    }
}

echo "Seeding complete!\n";
