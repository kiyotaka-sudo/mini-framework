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
CREATE TABLE IF NOT EXISTS tasks (
    {$idColumn},
    user_id INTEGER NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    priority VARCHAR(20) DEFAULT 'medium',
    status VARCHAR(20) DEFAULT 'pending',
    due_date DATE DEFAULT NULL,
    created_at {$timestampColumn},
    updated_at {$timestampColumn},
    deleted_at {$timestampColumn} DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)
SQL;

    $database->execute($sql);
};
