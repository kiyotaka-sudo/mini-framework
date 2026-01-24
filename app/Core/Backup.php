<?php

namespace App\Core;

use PDO;

class Backup
{
    protected Database $database;
    protected string $backupPath;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->backupPath = dirname(__DIR__, 2) . '/storage/backups';

        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    public static function make(): self
    {
        return new self(app()->make(Database::class));
    }

    /**
     * Exporte toutes les données d'une table en JSON
     */
    public function exportTable(string $table): array
    {
        $data = $this->database->fetchAll(sprintf('SELECT * FROM %s', $table));
        return [
            'table' => $table,
            'exported_at' => date('Y-m-d H:i:s'),
            'count' => count($data),
            'data' => $data
        ];
    }

    /**
     * Sauvegarde une table dans un fichier JSON
     */
    public function backupTable(string $table): string
    {
        $data = $this->exportTable($table);
        $filename = sprintf('%s/%s_%s.json', $this->backupPath, $table, date('Y-m-d_H-i-s'));

        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $filename;
    }

    /**
     * Sauvegarde toutes les tables de la base de données
     */
    public function backupAll(): array
    {
        $tables = $this->getTables();
        $backups = [];

        foreach ($tables as $table) {
            $backups[$table] = $this->backupTable($table);
        }

        return $backups;
    }

    /**
     * Restaure les données depuis un fichier de backup
     */
    public function restore(string $filename): array
    {
        if (!file_exists($filename)) {
            return [
                'success' => false,
                'message' => 'Fichier de backup introuvable'
            ];
        }

        $content = file_get_contents($filename);
        $backup = json_decode($content, true);

        if (!$backup || !isset($backup['table'], $backup['data'])) {
            return [
                'success' => false,
                'message' => 'Format de backup invalide'
            ];
        }

        $table = $backup['table'];
        $data = $backup['data'];
        $restored = 0;

        foreach ($data as $row) {
            $columns = array_keys($row);
            $placeholders = implode(', ', array_map(fn($c) => ':' . $c, $columns));

            $sql = sprintf(
                'INSERT OR REPLACE INTO %s (%s) VALUES (%s)',
                $table,
                implode(', ', $columns),
                $placeholders
            );

            $this->database->execute($sql, $row);
            $restored++;
        }

        return [
            'success' => true,
            'message' => 'Données restaurées avec succès',
            'table' => $table,
            'restored' => $restored
        ];
    }

    /**
     * Liste tous les fichiers de backup disponibles
     */
    public function listBackups(): array
    {
        $files = glob($this->backupPath . '/*.json');
        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'filename' => basename($file),
                'path' => $file,
                'size' => filesize($file),
                'created_at' => date('Y-m-d H:i:s', filemtime($file))
            ];
        }

        usort($backups, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));

        return $backups;
    }

    /**
     * Supprime un fichier de backup
     */
    public function deleteBackup(string $filename): bool
    {
        $path = $this->backupPath . '/' . basename($filename);

        if (file_exists($path)) {
            return unlink($path);
        }

        return false;
    }

    /**
     * Récupère la liste des tables de la base de données
     */
    protected function getTables(): array
    {
        $driver = $this->database->getDriver();

        if ($driver === 'sqlite') {
            $result = $this->database->fetchAll(
                "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'"
            );
            return array_column($result, 'name');
        }

        // MySQL
        $result = $this->database->fetchAll('SHOW TABLES');
        return array_map(fn($row) => array_values($row)[0], $result);
    }
}
