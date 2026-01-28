<?php

use App\Core\Database;

return new class {
    public function up(Database $db): void
    {
        $db->query("
            CREATE TABLE IF NOT EXISTS albums (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                artist_id INTEGER NOT NULL,
                release_date DATE,
                cover_url TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }
    
    public function down(Database $db): void
    {
        $db->query("DROP TABLE IF EXISTS albums");
    }
};