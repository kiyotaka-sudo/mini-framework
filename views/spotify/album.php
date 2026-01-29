
<div style="display:flex; align-items:end; gap:24px; margin-bottom: 24px;">
    <img src="<?= htmlspecialchars($album['cover_url'] ?? '') ?>" style="width:232px; height:232px; box-shadow:0 4px 60px rgba(0,0,0,.5);" onerror="this.src='https://via.placeholder.com/232/111/fff?text=Album'">
    <div>
        <div style="font-size: 14px; font-weight:bold; text-transform:uppercase;">Album</div>
        <h1 style="font-size: 80px; font-weight:900; margin: 8px 0;"><?= htmlspecialchars($album['name']) ?></h1>
        <div style="display:flex; align-items:center; gap:8px; font-weight:bold;">
            <img src="<?= htmlspecialchars($artist['image_url'] ?? '') ?>" style="width:24px; height:24px; border-radius:50%;">
            <a href="/artist/<?= $artist['id'] ?>" style="color:white; text-decoration:none;"><?= htmlspecialchars($artist['name']) ?></a>
            <span style="font-weight:normal;">• <?= date('Y', strtotime($album['release_date'])) ?> • <?= count($songs) ?> songs</span>
        </div>
    </div>
</div>

<div style="background: rgba(0,0,0,0.2); padding: 24px;">
    <!-- Controls -->
    <div style="margin-bottom: 32px; display:flex; align-items:center; gap:24px;">
        <button class="btn-primary" style="background:#1db954; width:56px; height:56px; border-radius:50%; font-size:24px; display:flex; align-items:center; justify-content:center;">▶</button>
        <button class="circle-btn" style="width:32px; height:32px; font-size:24px;">♥</button>
        <button class="circle-btn" style="width:32px; height:32px;">...</button>
    </div>

    <!-- Table Header -->
    <div style="display:grid; grid-template-columns: 50px 1fr 100px; padding: 0 16px; margin-bottom: 16px; color:#b3b3b3; border-bottom: 1px solid #282828; padding-bottom:8px;">
        <div>#</div>
        <div>Title</div>
        <div style="text-align:right;">🕒</div>
    </div>

    <!-- Songs List -->
    <?php foreach ($songs as $s): ?>
    <div class="song-row" style="display:grid; grid-template-columns: 50px 1fr 100px; padding: 12px 16px; border-radius:4px; transition:background 0.2s;">
        <div style="color:#b3b3b3; display:flex; align-items:center;"><?= $s['track_number'] ?></div>
        <div style="display:flex; flex-direction:column;">
            <div style="font-weight:bold; color:white;"><?= htmlspecialchars($s['title']) ?></div>
            <div style="font-size:14px; color:#b3b3b3;"><?= htmlspecialchars($artist['name']) ?></div>
        </div>
        <div style="text-align:right; color:#b3b3b3; display:flex; align-items:center; justify-content:end;">
            <?= floor($s['duration'] / 60) ?>:<?= str_pad($s['duration'] % 60, 2, '0', STR_PAD_LEFT) ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<style>
.song-row:hover {
    background-color: rgba(255,255,255,0.1);
}
</style>
