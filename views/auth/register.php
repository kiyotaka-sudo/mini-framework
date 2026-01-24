<?php
/** @var string $appName */
?>

<section class="card glow fade-in" style="max-width: 450px; margin: 2rem auto;">
    <div class="badge">Inscription</div>
    <h1>Creer un compte</h1>
    <p class="muted">Remplissez le formulaire pour creer votre compte.</p>

    <form id="register-form" style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
        <div>
            <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nom complet</label>
            <input type="text" id="name" name="name" required
                   placeholder="Jean Dupont"
                   style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
        </div>
        <div>
            <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email</label>
            <input type="email" id="email" name="email" required
                   placeholder="votre@email.com"
                   style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
        </div>
        <div>
            <label for="phone" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Telephone (optionnel)</label>
            <input type="tel" id="phone" name="phone"
                   placeholder="+33 6 12 34 56 78"
                   style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
        </div>
        <div>
            <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Mot de passe</label>
            <input type="password" id="password" name="password" required minlength="6"
                   placeholder="Minimum 6 caracteres"
                   style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
        </div>
        <div>
            <label for="password_confirm" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Confirmer le mot de passe</label>
            <input type="password" id="password_confirm" name="password_confirm" required
                   placeholder="Retapez votre mot de passe"
                   style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
        </div>
        <div id="error-message" style="color: #ef4444; display: none; padding: 0.75rem; background: rgba(239, 68, 68, 0.1); border-radius: 8px;"></div>
        <button type="submit" class="cta" style="width: 100%; justify-content: center; margin-top: 0.5rem;">
            S'inscrire
        </button>
    </form>

    <p style="margin-top: 1.5rem; text-align: center;" class="muted">
        Deja un compte ? <a href="/login" style="color: var(--primary); text-decoration: none; font-weight: 600;">Se connecter</a>
    </p>

    <div style="margin-top: 1rem; text-align: center;">
        <a href="/" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">← Retour a l'accueil</a>
    </div>
</section>

<script>
(function() {
    const form = document.getElementById('register-form');
    const errorDiv = document.getElementById('error-message');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        errorDiv.style.display = 'none';

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirm').value;

        if (password !== passwordConfirm) {
            errorDiv.textContent = 'Les mots de passe ne correspondent pas';
            errorDiv.style.display = 'block';
            return;
        }

        try {
            const response = await fetch('/api/auth/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, email, phone, password })
            });

            const result = await response.json();

            if (result.success) {
                window.location.href = result.redirect || '/dashboard';
            } else {
                errorDiv.textContent = result.message || 'Erreur lors de l\'inscription';
                errorDiv.style.display = 'block';
            }
        } catch (error) {
            errorDiv.textContent = 'Erreur de connexion au serveur';
            errorDiv.style.display = 'block';
        }
    });
})();
</script>
