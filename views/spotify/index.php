
<h2 class="section-title">Good evening</h2>

<div class="media-grid">
    <?php foreach ($albums as $album): ?>
    <a href="/album/<?= $album['id'] ?>" class="card">
        <div class="card-image-wrapper">
            <img src="<?= htmlspecialchars($album['cover_url'] ?? '') ?>" alt="<?= htmlspecialchars($album['name']) ?>" class="card-image" onerror="this.src='https://via.placeholder.com/150/111/fff?text=Album'">
        </div>
        <div class="card-title"><?= htmlspecialchars($album['name']) ?></div>
        <div class="card-desc">Album</div>
    </a>
    <?php endforeach; ?>
</div>

<h2 class="section-title">Popular Artists</h2>
<div class="media-grid">
    <?php foreach ($artists as $artist): ?>
    <a href="/artist/<?= $artist['id'] ?>" class="card">
        <div class="card-image-wrapper" style="border-radius: 50%;">
            <img src="<?= htmlspecialchars($artist['image_url'] ?? '') ?>" alt="<?= htmlspecialchars($artist['name']) ?>" class="card-image" onerror="this.src='https://via.placeholder.com/150/111/fff?text=Artist'">
        </div>
        <div class="card-title"><?= htmlspecialchars($artist['name']) ?></div>
        <div class="card-desc">Artist</div>
    </a>
    <?php endforeach; ?>
</div>
