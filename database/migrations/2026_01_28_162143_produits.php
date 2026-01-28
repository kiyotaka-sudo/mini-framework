<?php

use App\Core\Database;

return new class {
    public function up(Database $db): void
    {
        $db->query("
            CREATE TABLE IF NOT EXISTS produits (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nom TEXT NOT NULL,
                prix REAL NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }
    
    public function down(Database $db): void
    {
        $db->query("DROP TABLE IF EXISTS produits");
    }
};