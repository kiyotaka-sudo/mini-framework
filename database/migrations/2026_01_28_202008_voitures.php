<?php

use App\Core\Database;

return new class {
    public function up(Database $db): void
    {
        $db->query("
            CREATE TABLE IF NOT EXISTS voitures (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                marques TEXT NOT NULL,
                caution TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }
    
    public function down(Database $db): void
    {
        $db->query("DROP TABLE IF EXISTS voitures");
    }
};