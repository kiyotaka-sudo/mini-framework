<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use Database\Models\User;

class UserController
{
    /**
     * Liste tous les utilisateurs avec recherche et pagination
     */
    public function index(Request $request): Response
    {
        $filters = [
            'search' => $request->query('search'),
            'role' => $request->query('role'),
            'status' => $request->query('status'),
        ];

        $page = (int) ($request->query('page') ?? 1);
        $perPage = (int) ($request->query('per_page') ?? 10);

        // Si pas de filtres et pas de pagination, retourner tous les utilisateurs
        if (empty($filters['search']) && empty($filters['role']) && empty($filters['status']) && $page === 1) {
            $users = User::make()->all();
            return json([
                'success' => true,
                'data' => $users,
                'count' => count($users)
            ]);
        }

        $result = User::make()->search($filters, $page, $perPage);

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
     * Affiche un utilisateur spécifique
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->route('id');
        $user = User::make()->find($id);

        if (!$user) {
            return json([
                'success' => false,
                'message' => 'Utilisateur introuvable'
            ], 404);
        }

        // Ne pas retourner le mot de passe
        unset($user['password']);

        return json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Crée un nouvel utilisateur (enregistrement)
     */
    public function store(Request $request): Response
    {
        $data = $request->all();

        if (empty($data['name']) || empty($data['email'])) {
            return json([
                'success' => false,
                'message' => 'Les champs name et email sont requis'
            ], 400);
        }

        $model = User::make();

        // Vérifier si l'email existe déjà
        $existing = $model->findByEmail($data['email']);
        if ($existing) {
            return json([
                'success' => false,
                'message' => 'Un utilisateur avec cet email existe deja'
            ], 409);
        }

        // Si mot de passe fourni, utiliser register pour hasher
        if (!empty($data['password'])) {
            $user = $model->register([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'phone' => $data['phone'] ?? null,
                'role' => $data['role'] ?? 'user',
                'status' => $data['status'] ?? 'active'
            ]);
        } else {
            $user = $model->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'role' => $data['role'] ?? 'user',
                'status' => $data['status'] ?? 'active'
            ]);
        }

        unset($user['password']);

        return json([
            'success' => true,
            'message' => 'Utilisateur cree avec succes',
            'data' => $user
        ], 201);
    }

    /**
     * Met à jour un utilisateur (sauvegarde)
     */
    public function update(Request $request): Response
    {
        $id = (int) $request->route('id');
        $data = $request->all();

        $model = User::make();
        $user = $model->find($id);

        if (!$user) {
            return json([
                'success' => false,
                'message' => 'Utilisateur introuvable'
            ], 404);
        }

        // Vérifier si le nouvel email existe déjà pour un autre utilisateur
        if (!empty($data['email']) && $data['email'] !== $user['email']) {
            $existing = $model->findByEmail($data['email']);
            if ($existing && $existing['id'] !== $id) {
                return json([
                    'success' => false,
                    'message' => 'Un autre utilisateur utilise deja cet email'
                ], 409);
            }
        }

        // Si changement de mot de passe
        if (!empty($data['password'])) {
            $model->updatePassword($id, $data['password']);
            unset($data['password']);
        }

        // Mettre à jour les autres champs
        if (!empty($data)) {
            $model->update($id, $data);
        }

        $updatedUser = $model->find($id);
        unset($updatedUser['password']);

        return json([
            'success' => true,
            'message' => 'Utilisateur mis a jour avec succes',
            'data' => $updatedUser
        ]);
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = User::make();
        $user = $model->find($id);

        if (!$user) {
            return json([
                'success' => false,
                'message' => 'Utilisateur introuvable'
            ], 404);
        }

        $deleted = $model->delete($id);

        return json([
            'success' => true,
            'message' => 'Utilisateur supprime avec succes',
            'deleted' => $deleted > 0
        ]);
    }

    /**
     * Change le statut d'un utilisateur
     */
    public function changeStatus(Request $request): Response
    {
        $id = (int) $request->route('id');
        $status = $request->input('status');

        if (!in_array($status, ['active', 'inactive', 'banned'])) {
            return json([
                'success' => false,
                'message' => 'Statut invalide'
            ], 400);
        }

        $model = User::make();
        $user = $model->find($id);

        if (!$user) {
            return json([
                'success' => false,
                'message' => 'Utilisateur introuvable'
            ], 404);
        }

        $model->setStatus($id, $status);

        return json([
            'success' => true,
            'message' => 'Statut mis a jour'
        ]);
    }

    /**
     * Change le rôle d'un utilisateur
     */
    public function changeRole(Request $request): Response
    {
        $id = (int) $request->route('id');
        $role = $request->input('role');

        if (!in_array($role, ['user', 'admin', 'moderator'])) {
            return json([
                'success' => false,
                'message' => 'Role invalide'
            ], 400);
        }

        $model = User::make();
        $user = $model->find($id);

        if (!$user) {
            return json([
                'success' => false,
                'message' => 'Utilisateur introuvable'
            ], 404);
        }

        $model->setRole($id, $role);

        return json([
            'success' => true,
            'message' => 'Role mis a jour'
        ]);
    }
}
