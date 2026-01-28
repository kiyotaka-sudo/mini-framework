<style>
    .admin-container { max-width: 1200px; margin: 2rem auto; padding: 2rem; }
    .admin-header { display: flex; justify-content: space-between; margin-bottom: 2rem; }
    .btn { padding: 0.5rem 1rem; border-radius: 5px; text-decoration: none; }
    .btn-primary { background: #667eea; color: white; }
    .btn-danger { background: #f56565; color: white; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
</style>

<div class="admin-container">
    <div class="admin-header">
        <h1>Voiture Management</h1>
        <a href="/admin/voiture/create" class="btn btn-primary">+ New Voiture</a>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Marques</th>
                <th>Caution</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                    <td><?= htmlspecialchars($item['marques']) ?></td>
                    <td><?= htmlspecialchars($item['caution']) ?></td>
                <td>
                    <a href="/admin/voiture/<?= $item['id'] ?>/edit" class="btn btn-primary">Edit</a>
                    <button onclick="deleteItem(<?= $item['id'] ?>)" class="btn btn-danger">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function deleteItem(id) {
    if (!confirm('Are you sure?')) return;
    fetch(`/api/voiture/${id}`, { method: 'DELETE' })
        .then(() => location.reload());
}
</script>