<?php

namespace App\Http\Controllers;

use App\Core\Backup;
use App\Core\Request;
use App\Core\Response;

class BackupController
{
    /**
     * Liste tous les backups disponibles
     */
    public function index(Request $request): Response
    {
        $backups = Backup::make()->listBackups();

        return json([
            'success' => true,
            'data' => $backups,
            'count' => count($backups)
        ]);
    }

    /**
     * Crée un backup d'une table spécifique
     */
    public function store(Request $request): Response
    {
        $table = $request->input('table');

        if (empty($table)) {
            return json([
                'success' => false,
                'message' => 'Le nom de la table est requis'
            ], 400);
        }

        try {
            $backup = Backup::make();
            $filename = $backup->backupTable($table);

            return json([
                'success' => true,
                'message' => 'Backup créé avec succès',
                'filename' => basename($filename),
                'path' => $filename
            ], 201);
        } catch (\Exception $e) {
            return json([
                'success' => false,
                'message' => 'Erreur lors de la création du backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crée un backup complet de toutes les tables
     */
    public function backupAll(Request $request): Response
    {
        try {
            $backup = Backup::make();
            $files = $backup->backupAll();

            return json([
                'success' => true,
                'message' => 'Backup complet créé avec succès',
                'files' => array_map('basename', $files),
                'count' => count($files)
            ], 201);
        } catch (\Exception $e) {
            return json([
                'success' => false,
                'message' => 'Erreur lors du backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporte les données d'une table (sans sauvegarder dans un fichier)
     */
    public function export(Request $request): Response
    {
        $table = $request->route('table');

        if (empty($table)) {
            return json([
                'success' => false,
                'message' => 'Le nom de la table est requis'
            ], 400);
        }

        try {
            $data = Backup::make()->exportTable($table);

            return json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return json([
                'success' => false,
                'message' => 'Erreur lors de l\'export: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restaure les données depuis un backup
     */
    public function restore(Request $request): Response
    {
        $filename = $request->input('filename');

        if (empty($filename)) {
            return json([
                'success' => false,
                'message' => 'Le nom du fichier de backup est requis'
            ], 400);
        }

        $backup = Backup::make();
        $backupPath = dirname(__DIR__, 3) . '/storage/backups/' . basename($filename);

        $result = $backup->restore($backupPath);

        $statusCode = $result['success'] ? 200 : 400;
        return json($result, $statusCode);
    }

    /**
     * Supprime un fichier de backup
     */
    public function destroy(Request $request): Response
    {
        $filename = $request->route('filename');

        if (empty($filename)) {
            return json([
                'success' => false,
                'message' => 'Le nom du fichier est requis'
            ], 400);
        }

        $deleted = Backup::make()->deleteBackup($filename);

        if ($deleted) {
            return json([
                'success' => true,
                'message' => 'Backup supprimé avec succès'
            ]);
        }

        return json([
            'success' => false,
            'message' => 'Fichier de backup introuvable'
        ], 404);
    }
}
