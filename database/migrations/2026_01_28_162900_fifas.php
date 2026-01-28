<?php

use App\Core\Database;

return new class {
    public function up(Database $db): void
    {
        $db->exec("
            CREATE TABLE fifas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
    }
    
    public function down(Database $db): void
    {
        $db->exec("DROP TABLE IF EXISTS fifas");
    }
};