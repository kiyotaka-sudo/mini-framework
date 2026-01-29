<div class="container-md my-4">
    <div class="Subhead">
        <div class="Subhead-heading">Create Ordi</div>
    </div>
    
    <div class="Box">
        <div class="Box-header">
            <h3 class="Box-title">New Record</h3>
        </div>
        <div class="Box-body">
            <form id="createForm">
                <div class="form-group">
                <label>Iesy</label>
                <input type="text" name="iesy" value="" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Qwerty</label>
                <input type="text" name="qwerty" value="" class="form-control" required>
            </div>
                
                <div class="form-actions mt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="/admin/ordi" class="btn">Cancel</a>
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
    
    const response = await fetch('/api/ordi', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    if (response.ok) {
        window.location.href = '/admin/ordi';
    }
});
</script>