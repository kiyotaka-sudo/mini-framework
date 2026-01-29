<div class="Subhead">
    <div class="Subhead-heading">Artist Management</div>
    <div class="Subhead-actions">
        <a href="/admin/artist/create" class="btn btn-primary btn-sm">+ New Artist</a>
    </div>
</div>

<div class="Box">
    <div class="Box-header">
        <h3 class="Box-title">List of Artist</h3>
    </div>
    <div class="overflow-auto">
        <table class="width-full">
            <thead>
                <tr class="text-left">
                    <th>ID</th>
                <th>Name</th>
                <th>Bio</th>
                <th>Image Url</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr class="border-top">
                    <td><?= $item['id'] ?></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['bio']) ?></td>
                    <td><?= htmlspecialchars($item['image_url']) ?></td>
                    <td>
                        <a href="/admin/artist/<?= $item['id'] ?>/edit" class="btn btn-sm">Edit</a>
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
    fetch(`/api/artist/${id}`, { method: 'DELETE' })
        .then(() => location.reload());
}
</script>