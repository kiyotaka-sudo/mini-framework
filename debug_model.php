<?php
require 'bootstrap.php';
use Database\Models\Model;

try {
    $ref = new ReflectionClass(Model::class);
    echo "Class: " . Model::class . "\n";
    echo "File: " . $ref->getFileName() . "\n";
    $ctor = $ref->getConstructor();
    if ($ctor) {
        echo "Constructor visibility: " . ($ctor->isPublic() ? "public" : ($ctor->isProtected() ? "protected" : "private")) . "\n";
    } else {
        echo "No constructor found.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
