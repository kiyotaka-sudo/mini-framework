<?php
/** @var string $appName */
?>

<section class="card glow fade-in">
    <div class="badge">Gestion des Utilisateurs</div>
    <h1>Gestion des <strong>Utilisateurs</strong></h1>
    <p class="muted">
        Ajoutez, modifiez ou supprimez des utilisateurs. Gerez les roles et statuts.
    </p>
    <div style="margin-top: 1rem;">
        <a href="/dashboard" style="color: var(--primary); text-decoration: none;">← Retour au dashboard</a>
    </div>
</section>

<!-- Formulaire d'ajout/modification -->
<section class="card fade-in" style="margin-top: 2rem; animation-delay: 0.1s;">
    <h2 id="form-title">Ajouter un utilisateur</h2>
    <form id="user-form" style="display: flex; flex-direction: column; gap: 1rem;">
        <input type="hidden" id="user-id" value="">
        <div class="grid two">
            <div>
                <label for="user-name" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nom *</label>
                <input type="text" id="user-name" name="name" required
                       placeholder="Nom complet"
                       style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
            </div>
            <div>
                <label for="user-email" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email *</label>
                <input type="email" id="user-email" name="email" required
                       placeholder="email@exemple.com"
                       style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
            </div>
        </div>
        <div class="grid two">
            <div>
                <label for="user-phone" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Telephone</label>
                <input type="tel" id="user-phone" name="phone"
                       placeholder="+33 6 12 34 56 78"
                       style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
            </div>
            <div>
                <label for="user-password" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Mot de passe <span id="pwd-hint" class="muted">(min 6 caracteres)</span></label>
                <input type="password" id="user-password" name="password"
                       placeholder="Laisser vide pour ne pas changer"
                       style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
            </div>
        </div>
        <div class="grid two">
            <div>
                <label for="user-role" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Role</label>
                <select id="user-role" name="role"
                        style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
                    <option value="user">Utilisateur</option>
                    <option value="moderator">Moderateur</option>
                    <option value="admin">Administrateur</option>
                </select>
            </div>
            <div>
                <label for="user-status" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Statut</label>
                <select id="user-status" name="status"
                        style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                    <option value="banned">Banni</option>
                </select>
            </div>
        </div>
        <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
            <button type="submit" class="cta" id="btn-submit">Enregistrer</button>
            <button type="button" class="btn-secondary" id="btn-cancel" style="display: none;">Annuler</button>
        </div>
    </form>
</section>

<!-- Recherche et filtres -->
<section class="card fade-in" style="margin-top: 2rem; animation-delay: 0.2s;">
    <h2>Rechercher et filtrer</h2>
    <div class="grid three" style="margin-top: 1rem;">
        <div>
            <input type="text" id="search-input" placeholder="Rechercher par nom ou email..."
                   style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
        </div>
        <div>
            <select id="filter-role"
                    style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
                <option value="">Tous les roles</option>
                <option value="user">Utilisateur</option>
                <option value="moderator">Moderateur</option>
                <option value="admin">Administrateur</option>
            </select>
        </div>
        <div>
            <select id="filter-status"
                    style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
                <option value="">Tous les statuts</option>
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
                <option value="banned">Banni</option>
            </select>
        </div>
    </div>
</section>

<!-- Liste des utilisateurs -->
<section class="card fade-in" style="margin-top: 2rem; animation-delay: 0.3s;">
    <h2>Liste des utilisateurs</h2>
    <div id="users-list" style="margin-top: 1rem;">
        <p class="muted">Chargement...</p>
    </div>
    <div id="pagination" style="margin-top: 1.5rem; display: flex; justify-content: center; gap: 0.5rem;"></div>
</section>

<!-- Section Backup -->
<section class="card fade-in" style="margin-top: 2rem; animation-delay: 0.4s;">
    <h2>Sauvegarde des donnees</h2>
    <p class="muted">Creez une sauvegarde de vos donnees ou exportez-les.</p>
    <div style="display: flex; gap: 1rem; margin-top: 1rem; flex-wrap: wrap;">
        <button class="cta" id="btn-backup">Creer un Backup</button>
        <button class="btn-secondary" id="btn-export">Exporter en JSON</button>
    </div>
    <div id="backup-list" style="margin-top: 1.5rem;"></div>
</section>

<!-- Message de notification -->
<div id="notification" style="position: fixed; bottom: 2rem; right: 2rem; padding: 1rem 1.5rem; border-radius: 12px; background: var(--accent); color: #fff; font-weight: 600; box-shadow: 0 10px 30px rgba(0,0,0,0.2); display: none; z-index: 1000;"></div>

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
    .btn-danger {
        background: #ef4444;
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.2s;
    }
    .btn-danger:hover { background: #dc2626; }
    .btn-edit {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.2s;
    }
    .btn-edit:hover { opacity: 0.9; }
    .user-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: var(--surface-alt);
        border-radius: 12px;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .user-info { flex: 1; min-width: 200px; }
    .user-actions { display: flex; gap: 0.5rem; }
    .role-badge, .status-badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .role-admin { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .role-moderator { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .role-user { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
    .status-active { background: rgba(16, 185, 129, 0.2); color: #10b981; }
    .status-inactive { background: rgba(148, 163, 184, 0.2); color: #64748b; }
    .status-banned { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .backup-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        background: var(--surface-alt);
        border-radius: 8px;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .page-btn {
        padding: 0.5rem 1rem;
        border: 1px solid rgba(148, 163, 184, 0.3);
        background: var(--surface-alt);
        color: var(--text);
        border-radius: 8px;
        cursor: pointer;
    }
    .page-btn.active { background: var(--primary); color: #fff; border-color: var(--primary); }
    .page-btn:hover:not(.active) { background: var(--surface); }
</style>

<script>
(function() {
    const API_URL = '/api/users';
    const BACKUP_URL = '/api/backups';
    let currentPage = 1;
    let editingId = null;

    // Elements
    const form = document.getElementById('user-form');
    const userIdInput = document.getElementById('user-id');
    const userNameInput = document.getElementById('user-name');
    const userEmailInput = document.getElementById('user-email');
    const userPhoneInput = document.getElementById('user-phone');
    const userPasswordInput = document.getElementById('user-password');
    const userRoleSelect = document.getElementById('user-role');
    const userStatusSelect = document.getElementById('user-status');
    const formTitle = document.getElementById('form-title');
    const btnSubmit = document.getElementById('btn-submit');
    const btnCancel = document.getElementById('btn-cancel');
    const usersList = document.getElementById('users-list');
    const notification = document.getElementById('notification');
    const searchInput = document.getElementById('search-input');
    const filterRole = document.getElementById('filter-role');
    const filterStatus = document.getElementById('filter-status');
    const pagination = document.getElementById('pagination');
    const btnBackup = document.getElementById('btn-backup');
    const btnExport = document.getElementById('btn-export');
    const backupList = document.getElementById('backup-list');

    function showNotification(message, isError = false) {
        notification.textContent = message;
        notification.style.background = isError ? '#ef4444' : 'var(--accent)';
        notification.style.display = 'block';
        setTimeout(() => { notification.style.display = 'none'; }, 3000);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    function getRoleLabel(role) {
        const labels = { admin: 'Admin', moderator: 'Modo', user: 'User' };
        return labels[role] || role || 'user';
    }

    function getStatusLabel(status) {
        const labels = { active: 'Actif', inactive: 'Inactif', banned: 'Banni' };
        return labels[status] || status || 'active';
    }

    // Charger les utilisateurs
    async function loadUsers(page = 1) {
        currentPage = page;
        const search = searchInput.value;
        const role = filterRole.value;
        const status = filterStatus.value;

        let url = `${API_URL}?page=${page}&per_page=10`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        if (role) url += `&role=${role}`;
        if (status) url += `&status=${status}`;

        try {
            const response = await fetch(url);
            const result = await response.json();
            const users = result.data || [];

            if (users.length > 0) {
                usersList.innerHTML = users.map(user => `
                    <div class="user-item">
                        <div class="user-info">
                            <strong>${escapeHtml(user.name)}</strong>
                            <span class="role-badge role-${user.role || 'user'}">${getRoleLabel(user.role)}</span>
                            <span class="status-badge status-${user.status || 'active'}">${getStatusLabel(user.status)}</span>
                            <br><span class="muted">${escapeHtml(user.email)}</span>
                            ${user.phone ? `<br><small class="muted">Tel: ${escapeHtml(user.phone)}</small>` : ''}
                        </div>
                        <div class="user-actions">
                            <button class="btn-edit" onclick="editUser(${user.id})">Modifier</button>
                            <button class="btn-danger" onclick="deleteUser(${user.id})">Supprimer</button>
                        </div>
                    </div>
                `).join('');

                // Pagination
                if (result.pagination && result.pagination.total_pages > 1) {
                    let paginationHtml = '';
                    for (let i = 1; i <= result.pagination.total_pages; i++) {
                        paginationHtml += `<button class="page-btn ${i === page ? 'active' : ''}" onclick="loadUsers(${i})">${i}</button>`;
                    }
                    pagination.innerHTML = paginationHtml;
                } else {
                    pagination.innerHTML = '';
                }
            } else {
                usersList.innerHTML = '<p class="muted">Aucun utilisateur trouve.</p>';
                pagination.innerHTML = '';
            }
        } catch (error) {
            usersList.innerHTML = '<p class="muted" style="color: #ef4444;">Erreur de chargement</p>';
        }
    }

    // Soumettre le formulaire
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const id = userIdInput.value;
        const data = {
            name: userNameInput.value.trim(),
            email: userEmailInput.value.trim(),
            phone: userPhoneInput.value.trim() || null,
            role: userRoleSelect.value,
            status: userStatusSelect.value
        };

        if (userPasswordInput.value) {
            if (userPasswordInput.value.length < 6) {
                showNotification('Le mot de passe doit contenir au moins 6 caracteres', true);
                return;
            }
            data.password = userPasswordInput.value;
        }

        if (!data.name || !data.email) {
            showNotification('Nom et email requis', true);
            return;
        }

        try {
            let response;
            if (id) {
                response = await fetch(`${API_URL}/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
            } else {
                response = await fetch(API_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
            }

            const result = await response.json();

            if (result.success) {
                showNotification(id ? 'Utilisateur mis a jour !' : 'Utilisateur cree !');
                resetForm();
                loadUsers(currentPage);
            } else {
                showNotification(result.message || 'Erreur', true);
            }
        } catch (error) {
            showNotification('Erreur de connexion', true);
        }
    });

    // Modifier un utilisateur
    window.editUser = async function(id) {
        try {
            const response = await fetch(`${API_URL}/${id}`);
            const result = await response.json();

            if (result.success) {
                const user = result.data;
                userIdInput.value = user.id;
                userNameInput.value = user.name;
                userEmailInput.value = user.email;
                userPhoneInput.value = user.phone || '';
                userPasswordInput.value = '';
                userRoleSelect.value = user.role || 'user';
                userStatusSelect.value = user.status || 'active';

                formTitle.textContent = 'Modifier l\'utilisateur';
                btnSubmit.textContent = 'Sauvegarder';
                btnCancel.style.display = 'inline-flex';
                document.getElementById('pwd-hint').textContent = '(laisser vide pour ne pas changer)';
                userNameInput.focus();
            }
        } catch (error) {
            showNotification('Erreur', true);
        }
    };

    // Supprimer un utilisateur
    window.deleteUser = async function(id) {
        if (!confirm('Voulez-vous vraiment supprimer cet utilisateur ?')) return;

        try {
            const response = await fetch(`${API_URL}/${id}`, { method: 'DELETE' });
            const result = await response.json();

            if (result.success) {
                showNotification('Utilisateur supprime !');
                loadUsers(currentPage);
            } else {
                showNotification(result.message || 'Erreur de suppression', true);
            }
        } catch (error) {
            showNotification('Erreur de connexion', true);
        }
    };

    // Annuler la modification
    btnCancel.addEventListener('click', resetForm);

    function resetForm() {
        userIdInput.value = '';
        userNameInput.value = '';
        userEmailInput.value = '';
        userPhoneInput.value = '';
        userPasswordInput.value = '';
        userRoleSelect.value = 'user';
        userStatusSelect.value = 'active';
        formTitle.textContent = 'Ajouter un utilisateur';
        btnSubmit.textContent = 'Enregistrer';
        btnCancel.style.display = 'none';
        document.getElementById('pwd-hint').textContent = '(min 6 caracteres)';
    }

    // Filtres
    searchInput.addEventListener('input', () => loadUsers(1));
    filterRole.addEventListener('change', () => loadUsers(1));
    filterStatus.addEventListener('change', () => loadUsers(1));

    // Backup
    btnBackup.addEventListener('click', async function() {
        try {
            const response = await fetch(BACKUP_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ table: 'users' })
            });
            const result = await response.json();

            if (result.success) {
                showNotification('Backup cree : ' + result.filename);
                loadBackups();
            } else {
                showNotification(result.message || 'Erreur', true);
            }
        } catch (error) {
            showNotification('Erreur', true);
        }
    });

    // Export JSON
    btnExport.addEventListener('click', async function() {
        try {
            const response = await fetch(`${BACKUP_URL}/export/users`);
            const result = await response.json();

            if (result.success) {
                const blob = new Blob([JSON.stringify(result.data, null, 2)], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'users_export_' + new Date().toISOString().slice(0,10) + '.json';
                a.click();
                URL.revokeObjectURL(url);
                showNotification('Export telecharge !');
            }
        } catch (error) {
            showNotification('Erreur', true);
        }
    });

    // Charger les backups
    async function loadBackups() {
        try {
            const response = await fetch(BACKUP_URL);
            const result = await response.json();

            if (result.success && result.data.length > 0) {
                backupList.innerHTML = '<h4 style="margin-bottom: 0.75rem;">Backups disponibles :</h4>' +
                    result.data.slice(0, 5).map(backup => `
                        <div class="backup-item">
                            <span>${escapeHtml(backup.filename)}</span>
                            <button class="btn-danger" style="padding: 0.3rem 0.7rem; font-size: 0.8rem;"
                                    onclick="deleteBackup('${escapeHtml(backup.filename)}')">Supprimer</button>
                        </div>
                    `).join('');
            } else {
                backupList.innerHTML = '<p class="muted">Aucun backup</p>';
            }
        } catch (error) {
            backupList.innerHTML = '';
        }
    }

    window.deleteBackup = async function(filename) {
        if (!confirm('Supprimer ce backup ?')) return;
        try {
            await fetch(`${BACKUP_URL}/${filename}`, { method: 'DELETE' });
            showNotification('Backup supprime');
            loadBackups();
        } catch (error) {
            showNotification('Erreur', true);
        }
    };

    // Exposer loadUsers globalement
    window.loadUsers = loadUsers;

    // Init
    loadUsers();
    loadBackups();
})();
</script>
