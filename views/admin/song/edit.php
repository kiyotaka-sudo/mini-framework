<div class="container-md my-4">
    <div class="Subhead">
        <div class="Subhead-heading">Edit Song</div>
    </div>
    
    <div class="Box">
        <div class="Box-header">
            <h3 class="Box-title">Edit Record #<?= $item['id'] ?></h3>
        </div>
        <div class="Box-body">
            <form id="editForm">
                <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($item['title'] ?? '') ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Album Id</label>
                <input type="number" name="album_id" value="<?= htmlspecialchars($item['album_id'] ?? '') ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Duration</label>
                <input type="number" name="duration" value="<?= htmlspecialchars($item['duration'] ?? '') ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Track Number</label>
                <input type="number" name="track_number" value="<?= htmlspecialchars($item['track_number'] ?? '') ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>File Url</label>
                <input type="text" name="file_url" value="<?= htmlspecialchars($item['file_url'] ?? '') ?>" class="form-control" required>
            </div>
                
                <div class="form-actions mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/admin/song" class="btn">Cancel</a>
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
    
    const response = await fetch(`/api/song/${itemId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    if (response.ok) {
        window.location.href = '/admin/song';
    }
});
</script>