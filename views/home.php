<?php
/** @var string $appName */
/** @var string $name */
/** @var bool $isNewProject */
?>

<div class="Box p-3 m-3">
    <div class="Box-header">
        <h1 class="Box-title">Bienvenue sur <strong><?= htmlspecialchars($appName ?? 'mini-framework') ?></strong></h1>
    </div>
    <div class="Box-body">
        <p class="color-fg-muted mb-4">
            L'architecture élégante pour vos applications modernes. Design, Code, Déploie.
        </p>
        
        <?php if ($isNewProject ?? false): ?>
        <div class="Box color-bg-subtle p-4 mb-4" style="border-style: dashed; border-color: var(--color-accent-fg);">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap;">
                <div>
                    <h3 class="color-fg-accent mb-1">🚀 Prêt à construire ?</h3>
                    <p class="mb-0">Utilisez notre <strong>Visual Project Builder</strong> pour générer votre base de données, vos modèles et vos interfaces CRUD.</p>
                </div>
                <a class="btn btn-primary" href="/builder">Démarrer le Builder</a>
            </div>
        </div>
        <?php endif; ?>

        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a class="btn" href="/admin">Dashboard Admin</a>
            <a class="btn" href="/api">Explorer l'API</a>
        </div>
    </div>
</div>

<div class="container">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; margin-top: 16px;">
        <div class="Box p-3">
            <h3 class="mb-2">⚡ Génération de Code</h3>
            <p class="color-fg-muted">Générez instantanément vos Contrôleurs, Modèles et Vues à partir d'un schéma visuel.</p>
        </div>
        <div class="Box p-3">
            <h3 class="mb-2">🎨 UI GitHub Style</h3>
            <p class="color-fg-muted">Une interface d'administration moderne, inspirée de GitHub et prête à l'emploi.</p>
        </div>
        <div class="Box p-3">
            <h3 class="mb-2">🛠️ Auto Migrations</h3>
            <p class="color-fg-muted">La structure de votre base de données est synchronisée automatiquement lors de la génération.</p>
        </div>
    </div>
</div>
