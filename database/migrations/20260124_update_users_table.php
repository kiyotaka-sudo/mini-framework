<?php

use App\Core\Database;

return function (Database $database): void {
    $driver = $database->getDriver();

    // Ajouter les nouvelles colonnes à la table users
    $columns = [
        'password' => 'VARCHAR(255) DEFAULT NULL',
        'phone' => 'VARCHAR(20) DEFAULT NULL',
        'role' => "VARCHAR(20) DEFAULT 'user'",
        'status' => "VARCHAR(20) DEFAULT 'active'",
        'updated_at' => $driver === 'mysql'
            ? 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            : 'TEXT DEFAULT CURRENT_TIMESTAMP'
    ];

    foreach ($columns as $column => $definition) {
        try {
            $sql = sprintf('ALTER TABLE users ADD COLUMN %s %s', $column, $definition);
            $database->execute($sql);
        } catch (\Exception $e) {
            // Colonne existe déjà, on ignore
        }
    }
};
