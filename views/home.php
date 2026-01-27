<?php
/** @var string $appName */
/** @var string $name */
/** @var bool $isNewProject */
?>

<div class="home-container">
    <section class="hero card glow fade-in">
        <div class="badge">Framework PHP • Next Gen</div>
        <h1>Bienvenue sur <strong><?= htmlspecialchars($appName ?? 'mini-framework') ?></strong></h1>
        <p class="hero-subtitle">
            L'architecture élégante pour vos applications modernes. Design, Code, Déploie.
        </p>
        
        <?php if ($isNewProject ?? false): ?>
        <div class="magic-builder-cta fade-in" style="animation-delay: 0.3s;">
            <div class="cta-inner">
                <div class="cta-text">
                    <h3>🚀 Prêt à construire ?</h3>
                    <p>Utilisez notre <strong>Visual Project Builder</strong> pour générer votre base de données, vos modèles et vos interfaces CRUD en quelques clics.</p>
                </div>
                <div class="cta-actions">
                    <a class="cta pulse" href="/builder">Démarrer le Builder</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="hero-links" style="margin-top: 2rem; display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;">
            <a class="cta secondary" href="/admin">Dashboard Admin</a>
            <a class="cta outline" href="/api">Explorer l'API</a>
        </div>
    </section>

    <section class="features-grid grid three" style="margin-top: 3rem;">
        <div class="card feature-card fade-in" style="animation-delay: 0.1s;">
            <div class="icon-box">⚡</div>
            <h3>Génération de Code</h3>
            <p class="muted">Générez instantanément vos Contrôleurs, Modèles et Vues à partir d'un schéma visuel.</p>
        </div>
        <div class="card feature-card fade-in" style="animation-delay: 0.2s;">
            <div class="icon-box">🎨</div>
            <h3>UI Premium</h3>
            <p class="muted">Une interface d'administration moderne, responsive et prête à l'emploi.</p>
        </div>
        <div class="card feature-card fade-in" style="animation-delay: 0.3s;">
            <div class="icon-box">🛠️</div>
            <h3>Auto Migrations</h3>
            <p class="muted">La structure de votre base de données est synchronisée automatiquement lors de la génération.</p>
        </div>
    </section>

    <div class="quick-links card fade-in" style="margin-top: 3rem; animation-delay: 0.4s;">
        <h2>Démarrage rapide</h2>
        <div class="grid two" style="margin-top: 1.5rem;">
            <div class="link-item">
                <code>/builder</code>
                <p>Interface graphique de conception de schéma</p>
            </div>
            <div class="link-item">
                <code>/admin</code>
                <p>Gestion centrale de vos modules générés</p>
            </div>
        </div>
    </div>
</div>

<style>
.home-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.hero {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
    border: 1px solid rgba(255,255,255,0.1);
}

.hero h1 {
    font-size: 3.5rem;
    margin-bottom: 1rem;
}

.hero h1 strong {
    background: linear-gradient(90deg, #6366f1, #a855f7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-subtitle {
    font-size: 1.25rem;
    color: var(--text-muted);
    max-width: 600px;
    margin: 0 auto 2rem;
}

.magic-builder-cta {
    background: rgba(99, 102, 241, 0.1);
    border: 1px solid rgba(99, 102, 241, 0.3);
    border-radius: 1.5rem;
    padding: 2rem;
    margin: 2rem auto;
    max-width: 800px;
    text-align: left;
    backdrop-filter: blur(10px);
}

.cta-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    flex-wrap: wrap;
}

.cta-text h3 {
    margin-bottom: 0.5rem;
    color: #818cf8;
}

.cta-text p {
    margin: 0;
    font-size: 1.1rem;
    line-height: 1.5;
}

.cta-actions .cta {
    padding: 1rem 2rem;
    font-size: 1.1rem;
    background: #6366f1;
    color: white;
}

.pulse {
    animation: pulse-animation 2s infinite;
}

@keyframes pulse-animation {
    0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
    70% { box-shadow: 0 0 0 20px rgba(99, 102, 241, 0); }
    100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
}

.cta.secondary {
    background: var(--surface);
    color: var(--text);
    border: 1px solid rgba(255,255,255,0.1);
}

.cta.outline {
    background: transparent;
    border: 2px solid #6366f1;
    color: #6366f1;
}

.feature-card {
    text-align: center;
    padding: 2rem;
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.icon-box {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.link-item {
    padding: 1rem;
    background: rgba(0,0,0,0.2);
    border-radius: 0.5rem;
}

.link-item code {
    color: #818cf8;
    font-weight: bold;
}

.link-item p {
    margin: 0.5rem 0 0;
    font-size: 0.9rem;
    color: var(--text-muted);
}
</style>
