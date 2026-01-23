<?php

/** @var string $appName */
/** @var string $name */
/** @var array|null $user */
?>

<section class="card glow fade-in">
    <div class="badge">Framework PHP • Groupe 7</div>
    <h1>Bienvenue sur <strong>DEBA</strong></h1>
    <p class="muted">
        Créé par les élèves du groupe 7 de Keyce Informatique, DEBA est un mini-framework moderne,
        élégant et pédagogique, pensé pour apprendre avec style et efficacité.
    </p>
    <p>
        Salut <?= htmlspecialchars($name) ?>, prêt(e) à explorer une base propre, rapide et pleine d'animations ?
        Essaie un nom personnalisé via <code>?name=Prénom</code>.
    </p>
    <div style="margin-top: 1.5rem;">
        <a class="cta" href="/api">Découvrir l'API</a>
    </div>
</section>

<section class="grid two" style="margin-top: 2rem;">
    <div class="card fade-in" style="animation-delay: 0.1s;">
        <h2>Deux thèmes dynamiques</h2>
        <p class="muted">
            Un thème clair lumineux et un thème sombre immersif, basculables instantanément.
            Ton expérience DEBA s'adapte à ton humeur.
        </p>
    </div>
    <div class="card fade-in" style="animation-delay: 0.2s;">
        <h2>Animations fluides</h2>
        <p class="muted">
            Arrière-plan vivant, cartes flottantes et interactions soignées pour une
            introduction moderne et mémorable.
        </p>
    </div>
</section>

<section class="card fade-in" style="margin-top: 2rem; animation-delay: 0.3s;">
    <h2>Pourquoi DEBA ?</h2>
    <div class="grid three" style="margin-top: 1.2rem;">
        <div>
            <h3>Rapide à démarrer</h3>
            <p class="muted">Routing simple, helpers clairs, et un setup minimal.</p>
        </div>
        <div>
            <h3>Lisible & formateur</h3>
            <p class="muted">Structure didactique pour comprendre le coeur d'un framework.</p>
        </div>
        <div>
            <h3>Prêt pour évoluer</h3>
            <p class="muted">Base saine pour ajouter middleware, modèles et services.</p>
        </div>
    </div>
</section>

<section class="grid two" style="margin-top: 2rem;">
    <div class="card fade-in" style="animation-delay: 0.4s;">
        <h2>Équipe créatrice</h2>
        <p class="muted">
            Ce framework est une création collective des élèves du groupe 7.
            Merci pour l'énergie, la curiosité et la créativité apportées à DEBA.
        </p>
    </div>
    <div class="card fade-in" style="animation-delay: 0.5s;">
        <h2>Petit aperçu technique</h2>
        <p class="muted">
            Lance les routes avec <code>php bin/console routes</code> et démarre les migrations
            via <code>php bin/console migrate</code> pour aller plus loin.
        </p>
        <?php if (is_array($user)): ?>
            <p class="muted">
                Utilisateur en base : <?= htmlspecialchars($user['name']) ?> /
                <?= htmlspecialchars($user['email']) ?>
            </p>
        <?php endif; ?>
    </div>
</section>
