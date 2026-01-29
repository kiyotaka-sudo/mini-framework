<?php
require 'bootstrap.php';
use App\Http\Controllers\BuilderController;
use App\Core\Request;

// 1. Simulate the "Visual Builder" POST request to create 'playlists'
$schema = [
    'entities' => [
        [
            'name' => 'playlist',
            'table' => 'playlists',
            'fields' => [
                ['name' => 'name', 'type' => 'string', 'nullable' => false],
                ['name' => 'description', 'type' => 'text', 'nullable' => true],
            ]
        ]
    ]
];

echo "---[ DEMO: Simulating Graphic Table Creation ]---\n";
echo "User uses the Builder UI to create 'Playlist' table...\n";

$_SERVER['REQUEST_METHOD'] = 'POST';
$json = json_encode($schema);

$requestMock = new class($json) extends Request {
    private $mockBody;
    public function __construct($body) { 
        parent::__construct();
        $this->mockBody = $body; 
    }
    public function getBody(): string { return $this->mockBody; }
};

try {
    $controller = new BuilderController();
    $controller->generate($requestMock);
    echo "✅ Success! The Framework generated:\n";
    echo "   - database/migrations/xxxx_create_playlists_table.php\n";
    echo "   - app/Models/Playlist.php (or Database/Models/Playlist.php)\n";
} catch (Throwable $e) {
    echo "❌ Error in Builder: " . $e->getMessage() . "\n";
    exit(1);
}
