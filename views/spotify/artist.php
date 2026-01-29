
<div style="height: 300px; background-image: url('<?= htmlspecialchars($artist['image_url'] ?? '') ?>'); background-size: cover; background-position: center; position: relative;">
    <div style="position:absolute; bottom:0; left:0; width:100%; height:100%; background:linear-gradient(transparent, rgba(0,0,0,0.8));"></div>
    <div style="position:absolute; bottom:24px; left:24px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="#3d91f4"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.6 0 12 0zm-2 17l-5-5 1.4-1.4 3.6 3.6 7.6-7.6L19 8l-9 9z"></path></svg>
            Verified Artist
        </div>
        <h1 style="font-size: 96px; font-weight:900; margin:0;"><?= htmlspecialchars($artist['name']) ?></h1>
        <div style="font-weight:bold; margin-top:16px;"><?= number_format(rand(1000000, 50000000)) ?> monthly listeners</div>
    </div>
</div>

<div style="padding: 24px;">
    <!-- Controls -->
    <div style="margin-bottom: 32px; display:flex; align-items:center; gap:24px;">
        <button class="btn-primary" style="background:#1db954; width:56px; height:56px; border-radius:50%; font-size:24px; display:flex; align-items:center; justify-content:center;">▶</button>
        <button class="btn-primary" style="background:transparent; border:1px solid #b3b3b3; color:white;">Follow</button>
        <button class="circle-btn" style="width:32px; height:32px;">...</button>
    </div>

    <h2 class="section-title">Discography</h2>
    <div class="media-grid">
        <?php foreach ($albums as $album): ?>
        <a href="/album/<?= $album['id'] ?>" class="card">
            <div class="card-image-wrapper">
                <img src="<?= htmlspecialchars($album['cover_url'] ?? '') ?>" alt="<?= htmlspecialchars($album['name']) ?>" class="card-image" onerror="this.src='https://via.placeholder.com/150/111/fff?text=Album'">
            </div>
            <div class="card-title"><?= htmlspecialchars($album['name']) ?></div>
            <div class="card-desc"><?= date('Y', strtotime($album['release_date'])) ?> • Album</div>
        </a>
        <?php endforeach; ?>
    </div>
    
    <div style="margin-top: 40px;">
        <h2 class="section-title">About</h2>
        <div style="background: #282828; padding: 24px; border-radius: 8px; max-width: 800px;">
            <!-- Placeholder Bio Image -->
            <div style="width:100%; height: 300px; background-image: url('<?= htmlspecialchars($artist['image_url'] ?? '') ?>'); background-size: cover; background-position: center; margin-bottom: 16px; border-radius: 8px;"></div>
            <p style="line-height: 1.6; color: #fff;"><?= htmlspecialchars($artist['bio']) ?></p>
        </div>
    </div>
</div>
