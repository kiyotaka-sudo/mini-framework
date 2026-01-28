<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($appName ?? 'DEBA') ?></title>
    <link rel="stylesheet" href="/css/github-theme.css">
</head>
<body data-theme="light">
<header>
    <div class="container" style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <a href="/" class="nav-link" style="font-size: 18px; font-weight: 600;">DEBA</a>
            <nav style="display: flex; gap: 16px;">
                <a href="/admin" class="nav-link">Dashboard</a>
                <a href="/builder" class="nav-link">Builder</a>
                <a href="/page-builder" class="nav-link">Page Builder</a>
            </nav>
        </div>
        <button class="btn" type="button" data-theme-toggle>Theme</button>
    </div>
</header>
<main style="padding-top: 32px;">
    <div class="container">
        <?= $slot ?? '' ?>
    </div>
</main>
<script>
    (function () {
        var saved = localStorage.getItem('deba-theme');
        var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        var theme = saved || (prefersDark ? 'dark' : 'light');
        document.body.setAttribute('data-theme', theme);

        var toggle = document.querySelector('[data-theme-toggle]');
        if (!toggle) {
            return;
        }
        toggle.addEventListener('click', function () {
            var next = document.body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.body.setAttribute('data-theme', next);
            localStorage.setItem('deba-theme', next);
        });
    })();
</script>
</body>
</html>
