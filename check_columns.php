<?php
require 'bootstrap.php';
use App\Core\Database;

$db = app()->make(Database::class);
try {
    $res = $db->fetchAll("PRAGMA table_info(produits)");
    echo "Columns for 'produits':\n";
    foreach ($res as $col) {
        echo "- {$col['name']} ({$col['type']})\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
