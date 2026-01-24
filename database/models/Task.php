<?php

namespace Database\Models;

class Task extends Model
{
    protected string $table = 'tasks';
    protected array $fillable = ['user_id', 'title', 'description', 'priority', 'status', 'due_date'];

    /**
     * Récupère toutes les tâches d'un utilisateur
     */
    public function findByUser(int $userId): array
    {
        return $this->database->fetchAll(
            sprintf('SELECT * FROM %s WHERE user_id = :user_id AND deleted_at IS NULL ORDER BY created_at DESC', $this->getTable()),
            ['user_id' => $userId]
        );
    }

    /**
     * Recherche des tâches avec filtres et pagination
     */
    public function search(array $filters = [], int $page = 1, int $perPage = 10): array
    {
        $where = ['deleted_at IS NULL'];
        $params = [];

        if (!empty($filters['user_id'])) {
            $where[] = 'user_id = :user_id';
            $params['user_id'] = $filters['user_id'];
        }

        if (!empty($filters['search'])) {
            $where[] = '(title LIKE :search OR description LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['status'])) {
            $where[] = 'status = :status';
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['priority'])) {
            $where[] = 'priority = :priority';
            $params['priority'] = $filters['priority'];
        }

        $whereClause = 'WHERE ' . implode(' AND ', $where);

        // Compter le total
        $countSql = sprintf('SELECT COUNT(*) as total FROM %s %s', $this->getTable(), $whereClause);
        $countResult = $this->database->fetch($countSql, $params);
        $total = $countResult['total'] ?? 0;

        // Pagination
        $offset = ($page - 1) * $perPage;
        $sql = sprintf(
            'SELECT t.*, u.name as user_name FROM %s t LEFT JOIN users u ON t.user_id = u.id %s ORDER BY t.created_at DESC LIMIT %d OFFSET %d',
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
     * Soft delete - marque comme supprimé sans effacer
     */
    public function softDelete(int $id): int
    {
        $sql = sprintf('UPDATE %s SET deleted_at = :deleted_at WHERE id = :id', $this->getTable());
        return $this->database->execute($sql, [
            'id' => $id,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Restaure une tâche supprimée
     */
    public function restore(int $id): int
    {
        $sql = sprintf('UPDATE %s SET deleted_at = NULL WHERE id = :id', $this->getTable());
        return $this->database->execute($sql, ['id' => $id]);
    }

    /**
     * Récupère les tâches supprimées (corbeille)
     */
    public function trash(): array
    {
        return $this->database->fetchAll(
            sprintf('SELECT t.*, u.name as user_name FROM %s t LEFT JOIN users u ON t.user_id = u.id WHERE t.deleted_at IS NOT NULL ORDER BY t.deleted_at DESC', $this->getTable())
        );
    }

    /**
     * Change le statut d'une tâche
     */
    public function setStatus(int $id, string $status): int
    {
        return $this->update($id, ['status' => $status]);
    }

    /**
     * Compte les tâches par statut pour un utilisateur
     */
    public function countByStatus(?int $userId = null): array
    {
        $where = 'WHERE deleted_at IS NULL';
        $params = [];

        if ($userId) {
            $where .= ' AND user_id = :user_id';
            $params['user_id'] = $userId;
        }

        $sql = sprintf(
            'SELECT status, COUNT(*) as count FROM %s %s GROUP BY status',
            $this->getTable(),
            $where
        );

        $results = $this->database->fetchAll($sql, $params);
        $counts = ['pending' => 0, 'in_progress' => 0, 'completed' => 0];

        foreach ($results as $row) {
            $counts[$row['status']] = (int) $row['count'];
        }

        return $counts;
    }
}
