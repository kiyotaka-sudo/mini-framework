<?php

/** @var string $appName */
/** @var string $name */
/** @var array|null $user */
?>

<section class="hero">
    <h1>Bienvenue sur <?= htmlspecialchars($appName) ?></h1>
    <p>Salut <?= htmlspecialchars($name) ?>, ton mini-framework est déjà opérationnel.</p>
    <?php if (is_array($user)): ?>
        <p class="meta">
            Utilisateur en base : <?= htmlspecialchars($user['name']) ?> /
            <?= htmlspecialchars($user['email']) ?>
        </p>
    <?php endif; ?>
    <p>Teste un nom personnalisé via <code>?name=Prénom</code> ou appelle <code>/api</code> pour la réponse JSON.</p>
    <p><a class="button" href="/api">Vérifier l'API</a></p>
</section>
