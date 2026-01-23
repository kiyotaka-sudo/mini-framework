<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($appName ?? 'MiniFramework') ?></title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f4f6fb;
            color: #1f2933;
            margin: 0;
            padding: 0;
        }

        .hero {
            max-width: 680px;
            margin: 6rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .15);
            text-align: center;
        }

        .meta {
            color: #475569;
            font-size: 0.95rem;
            margin-top: 1rem;
        }

        .button {
            display: inline-block;
            padding: .65rem 1.5rem;
            margin-top: 1rem;
            color: #fff;
            background: #2563eb;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
<main>
    <?= $slot ?? '' ?>
</main>
</body>
</html>
