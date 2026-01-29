<div class="Subhead">
    <div class="Subhead-heading">Song Management</div>
    <div class="Subhead-actions">
        <a href="/admin/song/create" class="btn btn-primary btn-sm">+ New Song</a>
    </div>
</div>

<div class="Box">
    <div class="Box-header">
        <h3 class="Box-title">List of Song</h3>
    </div>
    <div class="overflow-auto">
        <table class="width-full">
            <thead>
                <tr class="text-left">
                    <th>ID</th>
                <th>Title</th>
                <th>Album Id</th>
                <th>Duration</th>
                <th>Track Number</th>
                <th>File Url</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr class="border-top">
                    <td><?= $item['id'] ?></td>
                    <td><?= htmlspecialchars($item['title']) ?></td>
                    <td><?= htmlspecialchars($item['album_id']) ?></td>
                    <td><?= htmlspecialchars($item['duration']) ?></td>
                    <td><?= htmlspecialchars($item['track_number']) ?></td>
                    <td><?= htmlspecialchars($item['file_url']) ?></td>
                    <td>
                        <a href="/admin/song/<?= $item['id'] ?>/edit" class="btn btn-sm">Edit</a>
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
    fetch(`/api/song/${id}`, { method: 'DELETE' })
        .then(() => location.reload());
}
</script>