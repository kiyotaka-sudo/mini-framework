<?php
require 'bootstrap.php';
use Database\Models\Playlist;

echo "\n---[ DEMO: Using the Generated Code Immediately ]---\n";

try {
    // 1. We can immediately use the class 'Playlist' which didn't exist seconds ago
    $playlistId = Playlist::make()->create([
        'name' => 'My Awesome Mix',
        'description' => 'A playlist created via the Demo script.'
    ]);
    
    // Fetch it back to prove it exists
    $playlist = Playlist::make()->find($playlistId);

    echo "✅ Success! Created Playlist ID: " . $playlist['id'] . "\n";
    echo "   Name: " . $playlist['name'] . "\n";
    echo "   Desc: " . $playlist['description'] . "\n";  

} catch (Throwable $e) {
    echo "❌ Error using generated code: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString();
}
