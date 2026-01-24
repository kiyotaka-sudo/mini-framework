<?php
/** @var string $appName */
?>

<section class="card glow fade-in">
    <div class="badge">Dashboard</div>
    <h1>Tableau de bord</h1>
    <p class="muted" id="welcome-message">Bienvenue sur votre espace personnel.</p>
    <div id="user-info" style="margin-top: 1rem; display: none;">
        <p><strong>Connecte en tant que :</strong> <span id="user-name"></span></p>
        <p class="muted"><span id="user-email"></span> | Role: <span id="user-role"></span></p>
    </div>
    <div style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
        <a class="cta" href="/users">Gerer les Utilisateurs</a>
        <a class="cta" href="/tasks" style="background: var(--accent);">Gerer les Taches</a>
        <button id="btn-logout" class="btn-secondary" style="display: none;">Deconnexion</button>
    </div>
</section>

<!-- Statistiques -->
<section class="grid two" style="margin-top: 2rem;">
    <div class="card fade-in" style="animation-delay: 0.1s;">
        <h2>Utilisateurs</h2>
        <p class="stat" id="stat-users" style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">-</p>
        <p class="muted">Utilisateurs enregistres</p>
    </div>
    <div class="card fade-in" style="animation-delay: 0.2s;">
        <h2>Taches</h2>
        <p class="stat" id="stat-tasks" style="font-size: 2.5rem; font-weight: 700; color: var(--accent);">-</p>
        <p class="muted">Taches totales</p>
    </div>
</section>

<!-- Stats détaillées des tâches -->
<section class="card fade-in" style="margin-top: 2rem; animation-delay: 0.3s;">
    <h2>Repartition des taches</h2>
    <div class="grid three" style="margin-top: 1rem;">
        <div style="text-align: center; padding: 1rem; background: var(--surface-alt); border-radius: 12px;">
            <p style="font-size: 2rem; font-weight: 700; color: #f59e0b;" id="stat-pending">0</p>
            <p class="muted">En attente</p>
        </div>
        <div style="text-align: center; padding: 1rem; background: var(--surface-alt); border-radius: 12px;">
            <p style="font-size: 2rem; font-weight: 700; color: #3b82f6;" id="stat-progress">0</p>
            <p class="muted">En cours</p>
        </div>
        <div style="text-align: center; padding: 1rem; background: var(--surface-alt); border-radius: 12px;">
            <p style="font-size: 2rem; font-weight: 700; color: #10b981;" id="stat-completed">0</p>
            <p class="muted">Terminees</p>
        </div>
    </div>
</section>

<!-- Navigation rapide -->
<section class="card fade-in" style="margin-top: 2rem; animation-delay: 0.4s;">
    <h2>Navigation rapide</h2>
    <div class="grid three" style="margin-top: 1rem;">
        <a href="/users" class="nav-card">
            <h3>Utilisateurs</h3>
            <p class="muted">Gerer les comptes</p>
        </a>
        <a href="/tasks" class="nav-card">
            <h3>Taches</h3>
            <p class="muted">Gerer les taches</p>
        </a>
        <a href="/api" class="nav-card">
            <h3>API</h3>
            <p class="muted">Documentation</p>
        </a>
    </div>
</section>

<style>
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.9rem 1.7rem;
        border-radius: 999px;
        background: var(--surface-alt);
        color: var(--text);
        text-decoration: none;
        font-weight: 600;
        border: 1px solid rgba(148, 163, 184, 0.3);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .nav-card {
        display: block;
        padding: 1.5rem;
        background: var(--surface-alt);
        border-radius: 12px;
        text-decoration: none;
        color: var(--text);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .nav-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .nav-card h3 {
        margin: 0 0 0.5rem 0;
        color: var(--primary);
    }
    .nav-card p {
        margin: 0;
    }
</style>

<script>
(function() {
    // Charger les infos utilisateur
    async function loadUser() {
        try {
            const response = await fetch('/api/auth/me');
            const result = await response.json();

            if (result.success && result.user) {
                document.getElementById('user-info').style.display = 'block';
                document.getElementById('user-name').textContent = result.user.name;
                document.getElementById('user-email').textContent = result.user.email;
                document.getElementById('user-role').textContent = result.user.role || 'user';
                document.getElementById('btn-logout').style.display = 'inline-flex';
                document.getElementById('welcome-message').textContent = 'Bienvenue, ' + result.user.name + ' !';
            }
        } catch (error) {
            // Non connecté, ce n'est pas grave
        }
    }

    // Charger les stats
    async function loadStats() {
        try {
            // Stats utilisateurs
            const usersResponse = await fetch('/api/users');
            const usersResult = await usersResponse.json();
            document.getElementById('stat-users').textContent = usersResult.count || usersResult.data?.length || 0;

            // Stats tâches
            const tasksResponse = await fetch('/api/tasks/stats');
            const tasksResult = await tasksResponse.json();

            if (tasksResult.success) {
                document.getElementById('stat-tasks').textContent = tasksResult.total || 0;
                document.getElementById('stat-pending').textContent = tasksResult.data?.pending || 0;
                document.getElementById('stat-progress').textContent = tasksResult.data?.in_progress || 0;
                document.getElementById('stat-completed').textContent = tasksResult.data?.completed || 0;
            }
        } catch (error) {
            console.error('Erreur chargement stats:', error);
        }
    }

    // Déconnexion
    document.getElementById('btn-logout').addEventListener('click', async function() {
        try {
            await fetch('/api/auth/logout', { method: 'POST' });
            window.location.href = '/login';
        } catch (error) {
            window.location.href = '/login';
        }
    });

    loadUser();
    loadStats();
})();
</script>
