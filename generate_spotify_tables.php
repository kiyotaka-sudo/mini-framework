<?php
require 'bootstrap.php';
use App\Http\Controllers\BuilderController;
use App\Core\Request;

// Simulate the POST request that the Builder UI would send
$schema = [
    'entities' => [
        [
            'name' => 'artist',
            'table' => 'artists',
            'fields' => [
                ['name' => 'name', 'type' => 'string', 'nullable' => false],
                ['name' => 'bio', 'type' => 'text', 'nullable' => true],
                ['name' => 'image_url', 'type' => 'string', 'nullable' => true],
            ]
        ],
        [
            'name' => 'album',
            'table' => 'albums',
            'fields' => [
                ['name' => 'name', 'type' => 'string', 'nullable' => false],
                ['name' => 'artist_id', 'type' => 'integer', 'nullable' => false],
                ['name' => 'release_date', 'type' => 'date', 'nullable' => true],
                ['name' => 'cover_url', 'type' => 'string', 'nullable' => true],
            ]
        ],
        [
            'name' => 'song',
            'table' => 'songs',
            'fields' => [
                ['name' => 'title', 'type' => 'string', 'nullable' => false],
                ['name' => 'album_id', 'type' => 'integer', 'nullable' => false],
                ['name' => 'duration', 'type' => 'integer', 'nullable' => true], // seconds
                ['name' => 'track_number', 'type' => 'integer', 'nullable' => true],
                ['name' => 'file_url', 'type' => 'string', 'nullable' => true],
            ]
        ]
    ]
];

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

echo "Generating Spotify Schema...\n";

try {
    $controller = new BuilderController();
    $response = $controller->generate($requestMock);
    
    echo "Response Type: " . get_class($response) . "\n";
    
    // Check available methods on Response
    // print_r(get_class_methods($response));
    
    // Assuming getContent() or similar exists, or public properties
    if (method_exists($response, 'getContent')) {
        echo "Body: " . $response->getContent() . "\n";
    } else {
        echo "Dumping Response:\n";
        var_dump($response);
    }

} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
