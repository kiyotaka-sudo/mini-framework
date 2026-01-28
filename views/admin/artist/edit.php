<style>
    .form-container { max-width: 600px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 10px; }
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
    .form-control { width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 5px; }
    .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 5px; cursor: pointer; }
    .btn-primary { background: #667eea; color: white; }
</style>

<div class="form-container">
    <h1>Edit Artist</h1>
    
    <form id="editForm">
        <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($item['name'] ?? '') ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Bio</label>
                <input type="textarea" name="bio" value="<?= htmlspecialchars($item['bio'] ?? '') ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Image Url</label>
                <input type="text" name="image_url" value="<?= htmlspecialchars($item['image_url'] ?? '') ?>" class="form-control" required>
            </div>
        
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="/admin/artist" class="btn">Cancel</a>
    </form>
</div>

<script>
const itemId = <?= $item['id'] ?>;
document.getElementById('editForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    const response = await fetch(`/api/artist/${itemId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    if (response.ok) {
        window.location.href = '/admin/artist';
    }
});
</script>