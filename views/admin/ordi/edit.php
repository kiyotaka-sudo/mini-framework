<div class="container-md my-4">
    <div class="Subhead">
        <div class="Subhead-heading">Edit Ordi</div>
    </div>
    
    <div class="Box">
        <div class="Box-header">
            <h3 class="Box-title">Edit Record #<?= $item['id'] ?></h3>
        </div>
        <div class="Box-body">
            <form id="editForm">
                <div class="form-group">
                <label>Iesy</label>
                <input type="text" name="iesy" value="<?= htmlspecialchars($item['iesy'] ?? '') ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Qwerty</label>
                <input type="text" name="qwerty" value="<?= htmlspecialchars($item['qwerty'] ?? '') ?>" class="form-control" required>
            </div>
                
                <div class="form-actions mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/admin/ordi" class="btn">Cancel</a>
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
    
    const response = await fetch(`/api/ordi/${itemId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    if (response.ok) {
        window.location.href = '/admin/ordi';
    }
});
</script>