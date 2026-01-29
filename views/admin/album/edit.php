<div class="container-md my-4">
    <div class="Subhead">
        <div class="Subhead-heading">Edit Album</div>
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
                <label>Artist Id</label>
                <input type="number" name="artist_id" value="<?= htmlspecialchars($item['artist_id'] ?? '') ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Release Date</label>
                <input type="date" name="release_date" value="<?= htmlspecialchars($item['release_date'] ?? '') ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Cover Url</label>
                <input type="text" name="cover_url" value="<?= htmlspecialchars($item['cover_url'] ?? '') ?>" class="form-control" required>
            </div>
                
                <div class="form-actions mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/admin/album" class="btn">Cancel</a>
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
    
    const response = await fetch(`/api/album/${itemId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    if (response.ok) {
        window.location.href = '/admin/album';
    }
});
</script>