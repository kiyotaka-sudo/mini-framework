<?php

use App\Core\Database;

return new class {
    public function up(Database $db): void
    {
        $db->query("
            CREATE TABLE IF NOT EXISTS songs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                album_id INTEGER NOT NULL,
                duration INTEGER,
                track_number INTEGER,
                file_url TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }
    
    public function down(Database $db): void
    {
        $db->query("DROP TABLE IF EXISTS songs");
    }
};