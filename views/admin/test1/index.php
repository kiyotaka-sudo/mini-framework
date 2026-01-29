<?php /** @var string $title */ /** @var array $items */ ?>
<div class="d-flex flex-items-center flex-justify-between mb-4">
    <h1 class="h2"><?= $title ?></h1>
    <a href="/admin/test1/create" class="btn btn-primary">
        <span class="octicon octicon-plus"></span> New Item
    </a>
</div>

<div class="Box">
    <div class="Box-header">
        <h3 class="Box-title">Items List</h3>
    </div>
    <ul>
        <?php foreach ($items as $item): ?>
            <li class="Box-row d-flex flex-items-center flex-justify-between">
                <div>
                    <strong><?= htmlspecialchars($item['nom'] ?? 'Item #' . $item['id']) ?></strong>
                    <div class="text-small text-gray"><?= htmlspecialchars($item['description'] ?? '') ?></div>
                </div>
                <div class="d-flex gap-2">
                    <a href="/admin/test1/<?= $item['id'] ?>/edit" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem(<?= $item['id'] ?>)">Delete</button>
                </div>
            </li>
        <?php endforeach; ?>
        <?php if (empty($items)): ?>
            <li class="Box-row text-center text-gray">No items found.</li>
        <?php endif; ?>
    </ul>
</div>

<script>
async function deleteItem(id) {
    if (!confirm('Are you sure?')) return;
    const res = await fetch('/api/test1/' + id, { method: 'DELETE' });
    if (res.ok) window.location.reload();
}
</script>