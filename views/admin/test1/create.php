<?php /** @var string $title */ ?>
<div class="mb-4">
    <a href="/admin/test1" class="btn btn-invisible pl-0">← Back to list</a>
</div>

<h1 class="h2 mb-4"><?= $title ?></h1>

<div class="Box p-4 col-6">
    <form id="createForm">
        <div class="form-group">
            <div class="form-group-header">
                <label>Name</label>
            </div>
            <div class="form-group-body">
                <input type="text" name="nom" class="form-control" required>
            </div>
        </div>
        
        <div class="form-group">
            <div class="form-group-header">
                <label>Description</label>
            </div>
            <div class="form-group-body">
                <textarea name="description" class="form-control"></textarea>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </form>
</div>

<script>
document.getElementById('createForm').addEventListener('submit', async e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(e.target));
    
    const res = await fetch('/api/test1', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    if (res.ok) window.location.href = '/admin/test1';
});
</script>