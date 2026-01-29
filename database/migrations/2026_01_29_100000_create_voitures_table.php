<?php

return new class {
    public function up($db)
    {
        $db->exec("CREATE TABLE IF NOT EXISTS voitures (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            marques TEXT NOT NULL,
            caution TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
    }

    public function down($db)
    {
        $db->exec("DROP TABLE IF EXISTS voitures");
    }
};
