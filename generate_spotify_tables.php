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
        ]
    ]
];

// Mocking Request
// We need to inject this into the controller
$_SERVER['REQUEST_METHOD'] = 'POST';
$json = json_encode($schema);

// Directly calling the controller method with a mocked request wrapper
// Since we can't easily mock php://input for the Request class in this script without external tools,
// we will instantiate the controller and pass a request that returns our body.

$requestMock = new class($json) extends Request {
    private $mockBody;
    public function __construct($body) { $this->mockBody = $body; }
    public function getBody(): string { return $this->mockBody; }
};

$controller = new BuilderController();
$response = $controller->generate($requestMock);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Body: " . $response->getContent() . "\n";
