<?php

use App\Core\Database;

return function (Database $database): void {
    $driver = $database->getDriver();
    $idColumn = $driver === 'mysql'
        ? 'id INT AUTO_INCREMENT PRIMARY KEY'
        : 'id INTEGER PRIMARY KEY AUTOINCREMENT';

    $timestampColumn = $driver === 'mysql'
        ? 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        : 'TEXT DEFAULT CURRENT_TIMESTAMP';

    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS users (
    {$idColumn},
    name VARCHAR(191) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE,
    created_at {$timestampColumn}
)
SQL;

    $database->execute($sql);
};
