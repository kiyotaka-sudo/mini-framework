<style>
    .form-container { max-width: 600px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 10px; }
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
    .form-control { width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 5px; }
    .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 5px; cursor: pointer; }
    .btn-primary { background: #667eea; color: white; }
</style>

<div class="form-container">
    <h1>Create Fifa</h1>
    
    <form id="createForm">
        <div class="form-group">
                <label>Asis</label>
                <input type="text" name="asis" value="" class="form-control" required>
            </div>
        
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="/admin/fifa" class="btn">Cancel</a>
    </form>
</div>

<script>
document.getElementById('createForm').addEventListener('submit', async (e) => {
    e.preventDefault();
   const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    const response = await fetch('/api/fifa', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    if (response.ok) {
        window.location.href = '/admin/fifa';
    }
});
</script>