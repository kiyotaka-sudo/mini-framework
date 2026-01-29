<div class="container-md my-4">
    <div class="Subhead">
        <div class="Subhead-heading">Create Playlist</div>
    </div>
    
    <div class="Box">
        <div class="Box-header">
            <h3 class="Box-title">New Record</h3>
        </div>
        <div class="Box-body">
            <form id="createForm">
                <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="textarea" name="description" value="" class="form-control" required>
            </div>
                
                <div class="form-actions mt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="/admin/playlist" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('createForm').addEventListener('submit', async (e) => {
    e.preventDefault();
   const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    const response = await fetch('/api/playlist', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    if (response.ok) {
        window.location.href = '/admin/playlist';
    }
});
</script>