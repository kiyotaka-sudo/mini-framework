<div class="Subhead">
    <div class="Subhead-heading">Playlist Management</div>
    <div class="Subhead-actions">
        <a href="/admin/playlist/create" class="btn btn-primary btn-sm">+ New Playlist</a>
    </div>
</div>

<div class="Box">
    <div class="Box-header">
        <h3 class="Box-title">List of Playlist</h3>
    </div>
    <div class="overflow-auto">
        <table class="width-full">
            <thead>
                <tr class="text-left">
                    <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr class="border-top">
                    <td><?= $item['id'] ?></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['description']) ?></td>
                    <td>
                        <a href="/admin/playlist/<?= $item['id'] ?>/edit" class="btn btn-sm">Edit</a>
                        <button onclick="deleteItem(<?= $item['id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function deleteItem(id) {
    if (!confirm('Are you sure?')) return;
    fetch(`/api/playlist/${id}`, { method: 'DELETE' })
        .then(() => location.reload());
}
</script>