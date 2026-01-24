<?php

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use Database\Models\Task;

class TaskController
{
    /**
     * Liste toutes les tâches avec recherche et pagination
     */
    public function index(Request $request): Response
    {
        $filters = [
            'search' => $request->query('search'),
            'status' => $request->query('status'),
            'priority' => $request->query('priority'),
            'user_id' => $request->query('user_id'),
        ];

        $page = (int) ($request->query('page') ?? 1);
        $perPage = (int) ($request->query('per_page') ?? 10);

        $result = Task::make()->search($filters, $page, $perPage);

        return json([
            'success' => true,
            'data' => $result['data'],
            'pagination' => [
                'total' => $result['total'],
                'page' => $result['page'],
                'per_page' => $result['per_page'],
                'total_pages' => $result['total_pages']
            ]
        ]);
    }

    /**
     * Affiche une tâche
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->route('id');
        $task = Task::make()->find($id);

        if (!$task) {
            return json([
                'success' => false,
                'message' => 'Tache introuvable'
            ], 404);
        }

        return json([
            'success' => true,
            'data' => $task
        ]);
    }

    /**
     * Crée une nouvelle tâche
     */
    public function store(Request $request): Response
    {
        $data = $request->all();

        if (empty($data['title'])) {
            return json([
                'success' => false,
                'message' => 'Le titre est requis'
            ], 400);
        }

        // Utiliser l'utilisateur connecté ou un ID fourni
        $userId = $data['user_id'] ?? (Auth::check() ? Auth::id() : 1);

        $task = Task::make()->create([
            'user_id' => $userId,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'priority' => $data['priority'] ?? 'medium',
            'status' => $data['status'] ?? 'pending',
            'due_date' => $data['due_date'] ?? null
        ]);

        return json([
            'success' => true,
            'message' => 'Tache creee avec succes',
            'data' => $task
        ], 201);
    }

    /**
     * Met à jour une tâche
     */
    public function update(Request $request): Response
    {
        $id = (int) $request->route('id');
        $data = $request->all();

        $model = Task::make();
        $task = $model->find($id);

        if (!$task) {
            return json([
                'success' => false,
                'message' => 'Tache introuvable'
            ], 404);
        }

        $model->update($id, $data);

        return json([
            'success' => true,
            'message' => 'Tache mise a jour',
            'data' => $model->find($id)
        ]);
    }

    /**
     * Supprime une tâche (soft delete)
     */
    public function destroy(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Task::make();
        $task = $model->find($id);

        if (!$task) {
            return json([
                'success' => false,
                'message' => 'Tache introuvable'
            ], 404);
        }

        // Soft delete par défaut
        $model->softDelete($id);

        return json([
            'success' => true,
            'message' => 'Tache deplacee dans la corbeille'
        ]);
    }

    /**
     * Suppression définitive
     */
    public function forceDelete(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Task::make();

        $deleted = $model->delete($id);

        if ($deleted) {
            return json([
                'success' => true,
                'message' => 'Tache supprimee definitivement'
            ]);
        }

        return json([
            'success' => false,
            'message' => 'Tache introuvable'
        ], 404);
    }

    /**
     * Restaure une tâche de la corbeille
     */
    public function restore(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Task::make();

        $model->restore($id);

        return json([
            'success' => true,
            'message' => 'Tache restauree'
        ]);
    }

    /**
     * Liste les tâches dans la corbeille
     */
    public function trash(Request $request): Response
    {
        $tasks = Task::make()->trash();

        return json([
            'success' => true,
            'data' => $tasks,
            'count' => count($tasks)
        ]);
    }

    /**
     * Statistiques des tâches
     */
    public function stats(Request $request): Response
    {
        $userId = $request->query('user_id');
        $counts = Task::make()->countByStatus($userId ? (int) $userId : null);

        return json([
            'success' => true,
            'data' => $counts,
            'total' => array_sum($counts)
        ]);
    }

    /**
     * Change le statut d'une tâche
     */
    public function changeStatus(Request $request): Response
    {
        $id = (int) $request->route('id');
        $status = $request->input('status');

        if (!in_array($status, ['pending', 'in_progress', 'completed'])) {
            return json([
                'success' => false,
                'message' => 'Statut invalide'
            ], 400);
        }

        $model = Task::make();
        $task = $model->find($id);

        if (!$task) {
            return json([
                'success' => false,
                'message' => 'Tache introuvable'
            ], 404);
        }

        $model->setStatus($id, $status);

        return json([
            'success' => true,
            'message' => 'Statut mis a jour',
            'data' => $model->find($id)
        ]);
    }
}
