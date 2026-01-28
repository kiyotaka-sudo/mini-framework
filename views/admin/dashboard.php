<div class="Box mb-4">
    <div class="Box-header">
        <h1 class="Box-title">Tableau de Bord Administratif</h1>
    </div>
    <div class="Box-body color-bg-subtle">
        <p class="mb-0">Gérez les données de votre projet en toute simplicité.</p>
    </div>
</div>

<div class="Subhead mt-4">
    <div class="Subhead-heading">Bases de données actives</div>
</div>

<?php if (empty($entities)): ?>
    <div class="flash flash-warn">
        <strong>Aucune table personnalisée n'a été détectée.</strong>
        <p class="mb-0">Utilisez le <a href="/builder">Visual Project Builder</a> pour créer vos premières entités (Produits, Commandes, etc.).</p>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px;">
        <?php foreach ($entities as $entity): ?>
            <div class="Box">
                <div class="Box-body" style="display: flex; align-items: flex-start; gap: 16px;">
                    <div style="font-size: 24px;">🗄️</div>
                    <div style="flex: 1;">
                        <h3 class="mb-1"><a href="<?= $entity['url'] ?>"><?= $entity['name'] ?></a></h3>
                        <p class="color-fg-muted mb-2" style="font-size: 13px;">Gérez la table <code><?= $entity['slug'] ?>s</code></p>
                        <span class="Label Label--secondary">Généré via Builder</span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="Box mt-6 p-4 text-center color-bg-subtle" style="background-color: var(--color-canvas-subtle);">
    <h3 class="mb-3">Extensions de développement</h3>
    <div style="display: flex; gap: 12px; justify-content: center;">
        <a href="/builder" class="btn btn-primary">Lancer le Project Builder (CRUD)</a>
        <a href="/page-builder" class="btn">Lancer le Concepteur de Page (D&D)</a>
    </div>
</div>

