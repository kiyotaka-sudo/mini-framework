<div class="container-md my-4">
    <div class="Subhead">
        <div class="Subhead-heading">Edit Playlist</div>
    </div>
    
    <div class="Box">
        <div class="Box-header">
            <h3 class="Box-title">Edit Record #<?= $item['id'] ?></h3>
        </div>
        <div class="Box-body">
            <form id="editForm">
                <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($item['name'] ?? '') ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="textarea" name="description" value="<?= htmlspecialchars($item['description'] ?? '') ?>" class="form-control" required>
            </div>
                
                <div class="form-actions mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/admin/playlist" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const itemId = <?= $item['id'] ?>;
document.getElementById('editForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    const response = await fetch(`/api/playlist/${itemId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    if (response.ok) {
        window.location.href = '/admin/playlist';
    }
});
</script>