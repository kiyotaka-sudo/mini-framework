<?php
require 'bootstrap.php';
use App\Http\Controllers\ProduitController;
use App\Core\Request;

try {
    $controller = new ProduitController();
    $request = new Request();
    $response = $controller->index($request);
    echo "Response code: " . $response->getStatusCode() . "\n";
    echo "Content: " . substr($response->getContent(), 0, 500) . "...\n";
} catch (Throwable $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
