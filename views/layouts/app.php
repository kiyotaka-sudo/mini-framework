<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($appName ?? 'DEBA') ?></title>
    <style>
        :root {
            color-scheme: light;
            --bg: #eef2ff;
            --bg-alt: #f8fafc;
            --surface: #ffffff;
            --surface-alt: #f1f5f9;
            --text: #0f172a;
            --text-muted: #475569;
            --primary: #4f46e5;
            --primary-soft: rgba(79, 70, 229, 0.12);
            --accent: #10b981;
            --shadow: 0 25px 60px rgba(15, 23, 42, 0.18);
            --ring: rgba(79, 70, 229, 0.35);
        }

        [data-theme="dark"] {
            color-scheme: dark;
            --bg: #0b1120;
            --bg-alt: #111827;
            --surface: #0f172a;
            --surface-alt: #111c33;
            --text: #f8fafc;
            --text-muted: #cbd5f5;
            --primary: #818cf8;
            --primary-soft: rgba(129, 140, 248, 0.16);
            --accent: #22d3ee;
            --shadow: 0 30px 70px rgba(0, 0, 0, 0.55);
            --ring: rgba(129, 140, 248, 0.55);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", "Inter", system-ui, Arial, sans-serif;
            color: var(--text);
            background: radial-gradient(circle at top, var(--bg), var(--bg-alt));
            overflow-x: hidden;
        }

        .bg-orbs {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: -1;
        }

        .bg-orbs span {
            position: absolute;
            display: block;
            border-radius: 999px;
            background: linear-gradient(120deg, var(--primary), var(--accent));
            opacity: 0.18;
            filter: blur(0px);
            animation: float 18s ease-in-out infinite;
        }

        .bg-orbs span:nth-child(1) {
            width: 380px;
            height: 380px;
            top: -120px;
            left: -80px;
            animation-delay: 0s;
        }

        .bg-orbs span:nth-child(2) {
            width: 520px;
            height: 520px;
            bottom: -200px;
            right: -140px;
            animation-delay: 4s;
        }

        .bg-orbs span:nth-child(3) {
            width: 220px;
            height: 220px;
            top: 30%;
            right: 12%;
            animation-delay: 8s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate3d(0, 0, 0) scale(1);
            }
            50% {
                transform: translate3d(0, -30px, 0) scale(1.06);
            }
        }

        header.topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem clamp(1.5rem, 5vw, 4.5rem);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .brand-badge {
            background: var(--primary);
            color: #fff;
            padding: 0.5rem 0.9rem;
            border-radius: 999px;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.35);
        }

        .theme-toggle {
            border: 1px solid transparent;
            background: var(--surface);
            color: var(--text);
            padding: 0.55rem 1rem;
            border-radius: 999px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border 0.2s ease;
        }

        .theme-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 35px rgba(15, 23, 42, 0.2);
            border-color: var(--ring);
        }

        main {
            padding: 0 clamp(1.5rem, 5vw, 4.5rem) 4rem;
        }

        .card {
            background: var(--surface);
            border-radius: 24px;
            box-shadow: var(--shadow);
            padding: clamp(1.8rem, 4vw, 3rem);
            border: 1px solid rgba(148, 163, 184, 0.15);
        }

        .grid {
            display: grid;
            gap: 1.5rem;
        }

        .grid.two {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }

        .grid.three {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.3rem 0.85rem;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary);
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.03em;
        }

        .cta {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1.7rem;
            border-radius: 999px;
            background: linear-gradient(120deg, var(--primary), var(--accent));
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 14px 30px rgba(79, 70, 229, 0.35);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .cta:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.5);
        }

        .muted {
            color: var(--text-muted);
        }

        .fade-in {
            animation: fadeUp 0.9s ease both;
        }

        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(24px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .glow {
            position: relative;
        }

        .glow::after {
            content: "";
            position: absolute;
            inset: -10px;
            border-radius: 28px;
            background: radial-gradient(circle, var(--primary-soft), transparent 70%);
            z-index: -1;
            animation: pulse 3.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 0.6;
                transform: scale(0.98);
            }
            50% {
                opacity: 1;
                transform: scale(1.02);
            }
        }
    </style>
</head>
<body data-theme="light">
<div class="bg-orbs">
    <span></span>
    <span></span>
    <span></span>
</div>
<header class="topbar">
    <div class="brand">
        <span class="brand-badge">DEBA</span>
        <span><?= htmlspecialchars($appName ?? 'MiniFramework') ?></span>
    </div>
    <button class="theme-toggle" type="button" data-theme-toggle>Basculer le thème</button>
</header>
<main>
    <?= $slot ?? '' ?>
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
