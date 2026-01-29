<?php

return new class {
    public function up($db)
    {
        $db->exec("CREATE TABLE IF NOT EXISTS test1s (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nom TEXT,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
    }

    public function down($db)
    {
        $db->exec("DROP TABLE IF EXISTS test1s");
    }
};
