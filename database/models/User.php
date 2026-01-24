<?php

namespace Database\Models;

class User extends Model
{
    protected string $table = 'users';
    protected array $fillable = ['name', 'email', 'password', 'phone', 'role', 'status'];

    /**
     * Trouve un utilisateur par email
     */
    public function findByEmail(string $email): ?array
    {
        return $this->database->fetch(
            sprintf('SELECT * FROM %s WHERE email = :email LIMIT 1', $this->getTable()),
            ['email' => $email]
        ) ?: null;
    }

    /**
     * Crée un utilisateur avec mot de passe hashé
     */
    public function register(array $data): array
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $this->create($data);
    }

    /**
     * Vérifie les identifiants de connexion
     */
    public function attempt(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);

        if (!$user) {
            return null;
        }

        if (!isset($user['password']) || !password_verify($password, $user['password'])) {
            return null;
        }

        if (isset($user['status']) && $user['status'] !== 'active') {
            return null;
        }

        return $user;
    }

    /**
     * Recherche des utilisateurs avec filtres
     */
    public function search(array $filters = [], int $page = 1, int $perPage = 10): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['search'])) {
            $where[] = '(name LIKE :search OR email LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['role'])) {
            $where[] = 'role = :role';
            $params['role'] = $filters['role'];
        }

        if (!empty($filters['status'])) {
            $where[] = 'status = :status';
            $params['status'] = $filters['status'];
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // Compter le total
        $countSql = sprintf('SELECT COUNT(*) as total FROM %s %s', $this->getTable(), $whereClause);
        $countResult = $this->database->fetch($countSql, $params);
        $total = $countResult['total'] ?? 0;

        // Pagination
        $offset = ($page - 1) * $perPage;
        $sql = sprintf(
            'SELECT id, name, email, phone, role, status, created_at FROM %s %s ORDER BY id DESC LIMIT %d OFFSET %d',
            $this->getTable(),
            $whereClause,
            $perPage,
            $offset
        );

        $data = $this->database->fetchAll($sql, $params);

        return [
            'data' => $data,
            'total' => (int) $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    /**
     * Change le statut d'un utilisateur
     */
    public function setStatus(int $id, string $status): int
    {
        return $this->update($id, ['status' => $status]);
    }

    /**
     * Change le rôle d'un utilisateur
     */
    public function setRole(int $id, string $role): int
    {
        return $this->update($id, ['role' => $role]);
    }

    /**
     * Met à jour le mot de passe
     */
    public function updatePassword(int $id, string $password): int
    {
        return $this->update($id, ['password' => password_hash($password, PASSWORD_DEFAULT)]);
    }
}
