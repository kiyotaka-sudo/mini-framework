<?php
/** @var string $appName */
?>

<section class="card glow fade-in">
    <div class="badge">Gestion des Taches</div>
    <h1>Gestion des <strong>Taches</strong></h1>
    <p class="muted">Creez, modifiez, supprimez et organisez vos taches. Corbeille disponible pour restaurer.</p>
    <div style="margin-top: 1rem;">
        <a href="/dashboard" style="color: var(--primary); text-decoration: none;">← Retour au dashboard</a>
    </div>
</section>

<!-- Stats rapides -->
<section class="grid three" style="margin-top: 1.5rem;">
    <div class="card fade-in stat-card" style="animation-delay: 0.05s; text-align: center;">
        <p style="font-size: 2rem; font-weight: 700; color: #f59e0b;" id="count-pending">0</p>
        <p class="muted">En attente</p>
    </div>
    <div class="card fade-in stat-card" style="animation-delay: 0.1s; text-align: center;">
        <p style="font-size: 2rem; font-weight: 700; color: #3b82f6;" id="count-progress">0</p>
        <p class="muted">En cours</p>
    </div>
    <div class="card fade-in stat-card" style="animation-delay: 0.15s; text-align: center;">
        <p style="font-size: 2rem; font-weight: 700; color: #10b981;" id="count-completed">0</p>
        <p class="muted">Terminees</p>
    </div>
</section>

<!-- Formulaire d'ajout/modification -->
<section class="card fade-in" style="margin-top: 2rem; animation-delay: 0.2s;">
    <h2 id="form-title">Ajouter une tache</h2>
    <form id="task-form" style="display: flex; flex-direction: column; gap: 1rem;">
        <input type="hidden" id="task-id" value="">
        <div class="grid two">
            <div>
                <label for="task-title" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Titre *</label>
                <input type="text" id="task-title" name="title" required
                       placeholder="Titre de la tache"
                       style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
            </div>
            <div>
                <label for="task-user" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Assigne a</label>
                <select id="task-user" name="user_id"
                        style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
                    <option value="">Chargement...</option>
                </select>
            </div>
        </div>
        <div>
            <label for="task-description" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Description</label>
            <textarea id="task-description" name="description" rows="3"
                      placeholder="Description de la tache..."
                      style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem; resize: vertical;"></textarea>
        </div>
        <div class="grid three">
            <div>
                <label for="task-priority" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Priorite</label>
                <select id="task-priority" name="priority"
                        style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
                    <option value="low">Basse</option>
                    <option value="medium" selected>Moyenne</option>
                    <option value="high">Haute</option>
                </select>
            </div>
            <div>
                <label for="task-status" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Statut</label>
                <select id="task-status" name="status"
                        style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
                    <option value="pending">En attente</option>
                    <option value="in_progress">En cours</option>
                    <option value="completed">Terminee</option>
                </select>
            </div>
            <div>
                <label for="task-due" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Date limite</label>
                <input type="date" id="task-due" name="due_date"
                       style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
            </div>
        </div>
        <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
            <button type="submit" class="cta" id="btn-submit">Enregistrer</button>
            <button type="button" class="btn-secondary" id="btn-cancel" style="display: none;">Annuler</button>
        </div>
    </form>
</section>

<!-- Filtres et recherche -->
<section class="card fade-in" style="margin-top: 2rem; animation-delay: 0.3s;">
    <h2>Rechercher et filtrer</h2>
    <div class="grid three" style="margin-top: 1rem;">
        <div>
            <input type="text" id="search-input" placeholder="Rechercher..."
                   style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
        </div>
        <div>
            <select id="filter-status"
                    style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
                <option value="">Tous les statuts</option>
                <option value="pending">En attente</option>
                <option value="in_progress">En cours</option>
                <option value="completed">Terminee</option>
            </select>
        </div>
        <div>
            <select id="filter-priority"
                    style="width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.3); background: var(--surface-alt); color: var(--text); font-size: 1rem;">
                <option value="">Toutes les priorites</option>
                <option value="low">Basse</option>
                <option value="medium">Moyenne</option>
                <option value="high">Haute</option>
            </select>
        </div>
    </div>
</section>

<!-- Liste des tâches -->
<section class="card fade-in" style="margin-top: 2rem; animation-delay: 0.4s;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h2>Liste des taches</h2>
        <button class="btn-secondary" id="btn-show-trash">Voir la corbeille</button>
    </div>
    <div id="tasks-list" style="margin-top: 1rem;">
        <p class="muted">Chargement...</p>
    </div>
    <!-- Pagination -->
    <div id="pagination" style="margin-top: 1.5rem; display: flex; justify-content: center; gap: 0.5rem;"></div>
</section>

<!-- Corbeille (cachée par défaut) -->
<section class="card fade-in" id="trash-section" style="margin-top: 2rem; display: none;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h2>Corbeille</h2>
        <button class="btn-secondary" id="btn-hide-trash">Masquer la corbeille</button>
    </div>
    <div id="trash-list" style="margin-top: 1rem;">
        <p class="muted">Chargement...</p>
    </div>
</section>

<!-- Notification -->
<div id="notification" style="position: fixed; bottom: 2rem; right: 2rem; padding: 1rem 1.5rem; border-radius: 12px; background: var(--accent); color: #fff; font-weight: 600; box-shadow: 0 10px 30px rgba(0,0,0,0.2); display: none; z-index: 1000;"></div>

<style>
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.7rem 1.3rem;
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
    .btn-success {
        background: #10b981;
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
    }
    .btn-success:hover { background: #059669; }
    .task-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1rem;
        background: var(--surface-alt);
        border-radius: 12px;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .task-info { flex: 1; min-width: 200px; }
    .task-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }
    .priority-high { border-left: 4px solid #ef4444; }
    .priority-medium { border-left: 4px solid #f59e0b; }
    .priority-low { border-left: 4px solid #10b981; }
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .status-in_progress { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
    .status-completed { background: rgba(16, 185, 129, 0.2); color: #10b981; }
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
    .stat-card { padding: 1rem !important; }
</style>

<script>
(function() {
    const API_URL = '/api/tasks';
    let currentPage = 1;
    let users = [];

    // Elements
    const form = document.getElementById('task-form');
    const taskIdInput = document.getElementById('task-id');
    const taskTitleInput = document.getElementById('task-title');
    const taskDescInput = document.getElementById('task-description');
    const taskUserSelect = document.getElementById('task-user');
    const taskPrioritySelect = document.getElementById('task-priority');
    const taskStatusSelect = document.getElementById('task-status');
    const taskDueInput = document.getElementById('task-due');
    const formTitle = document.getElementById('form-title');
    const btnSubmit = document.getElementById('btn-submit');
    const btnCancel = document.getElementById('btn-cancel');
    const tasksList = document.getElementById('tasks-list');
    const trashList = document.getElementById('trash-list');
    const trashSection = document.getElementById('trash-section');
    const notification = document.getElementById('notification');
    const searchInput = document.getElementById('search-input');
    const filterStatus = document.getElementById('filter-status');
    const filterPriority = document.getElementById('filter-priority');
    const pagination = document.getElementById('pagination');

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

    // Charger les utilisateurs
    async function loadUsers() {
        try {
            const response = await fetch('/api/users');
            const result = await response.json();
            users = result.data || [];

            taskUserSelect.innerHTML = '<option value="">-- Selectionner --</option>' +
                users.map(u => `<option value="${u.id}">${escapeHtml(u.name)}</option>`).join('');
        } catch (error) {
            taskUserSelect.innerHTML = '<option value="">Erreur</option>';
        }
    }

    // Charger les stats
    async function loadStats() {
        try {
            const response = await fetch(`${API_URL}/stats`);
            const result = await response.json();
            if (result.success) {
                document.getElementById('count-pending').textContent = result.data.pending || 0;
                document.getElementById('count-progress').textContent = result.data.in_progress || 0;
                document.getElementById('count-completed').textContent = result.data.completed || 0;
            }
        } catch (error) {}
    }

    // Charger les tâches
    async function loadTasks(page = 1) {
        currentPage = page;
        const search = searchInput.value;
        const status = filterStatus.value;
        const priority = filterPriority.value;

        let url = `${API_URL}?page=${page}&per_page=10`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        if (status) url += `&status=${status}`;
        if (priority) url += `&priority=${priority}`;

        try {
            const response = await fetch(url);
            const result = await response.json();

            if (result.success && result.data.length > 0) {
                tasksList.innerHTML = result.data.map(task => `
                    <div class="task-item priority-${task.priority || 'medium'}">
                        <div class="task-info">
                            <strong>${escapeHtml(task.title)}</strong>
                            <span class="status-badge status-${task.status}">${getStatusLabel(task.status)}</span>
                            <br><span class="muted">${escapeHtml(task.description || 'Pas de description')}</span>
                            <br><small class="muted">Assigne a: ${escapeHtml(task.user_name || 'Non assigne')} | Priorite: ${getPriorityLabel(task.priority)}</small>
                            ${task.due_date ? `<br><small class="muted">Echeance: ${task.due_date}</small>` : ''}
                        </div>
                        <div class="task-actions">
                            <button class="btn-edit" onclick="editTask(${task.id})">Modifier</button>
                            <button class="btn-danger" onclick="deleteTask(${task.id})">Supprimer</button>
                        </div>
                    </div>
                `).join('');

                // Pagination
                if (result.pagination && result.pagination.total_pages > 1) {
                    let paginationHtml = '';
                    for (let i = 1; i <= result.pagination.total_pages; i++) {
                        paginationHtml += `<button class="page-btn ${i === page ? 'active' : ''}" onclick="loadTasks(${i})">${i}</button>`;
                    }
                    pagination.innerHTML = paginationHtml;
                } else {
                    pagination.innerHTML = '';
                }
            } else {
                tasksList.innerHTML = '<p class="muted">Aucune tache trouvee.</p>';
                pagination.innerHTML = '';
            }
        } catch (error) {
            tasksList.innerHTML = '<p class="muted" style="color: #ef4444;">Erreur de chargement</p>';
        }

        loadStats();
    }

    function getStatusLabel(status) {
        const labels = { pending: 'En attente', in_progress: 'En cours', completed: 'Terminee' };
        return labels[status] || status;
    }

    function getPriorityLabel(priority) {
        const labels = { low: 'Basse', medium: 'Moyenne', high: 'Haute' };
        return labels[priority] || priority;
    }

    // Soumettre le formulaire
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const id = taskIdInput.value;
        const data = {
            title: taskTitleInput.value.trim(),
            description: taskDescInput.value.trim(),
            user_id: taskUserSelect.value || 1,
            priority: taskPrioritySelect.value,
            status: taskStatusSelect.value,
            due_date: taskDueInput.value || null
        };

        if (!data.title) {
            showNotification('Le titre est requis', true);
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
                showNotification(id ? 'Tache mise a jour !' : 'Tache creee !');
                resetForm();
                loadTasks(currentPage);
            } else {
                showNotification(result.message || 'Erreur', true);
            }
        } catch (error) {
            showNotification('Erreur de connexion', true);
        }
    });

    // Modifier une tâche
    window.editTask = async function(id) {
        try {
            const response = await fetch(`${API_URL}/${id}`);
            const result = await response.json();

            if (result.success) {
                const task = result.data;
                taskIdInput.value = task.id;
                taskTitleInput.value = task.title;
                taskDescInput.value = task.description || '';
                taskUserSelect.value = task.user_id || '';
                taskPrioritySelect.value = task.priority || 'medium';
                taskStatusSelect.value = task.status || 'pending';
                taskDueInput.value = task.due_date || '';

                formTitle.textContent = 'Modifier la tache';
                btnSubmit.textContent = 'Sauvegarder';
                btnCancel.style.display = 'inline-flex';
                taskTitleInput.focus();
            }
        } catch (error) {
            showNotification('Erreur', true);
        }
    };

    // Supprimer (soft delete)
    window.deleteTask = async function(id) {
        if (!confirm('Deplacer cette tache dans la corbeille ?')) return;

        try {
            const response = await fetch(`${API_URL}/${id}`, { method: 'DELETE' });
            const result = await response.json();

            if (result.success) {
                showNotification('Tache deplacee dans la corbeille');
                loadTasks(currentPage);
                loadTrash();
            } else {
                showNotification(result.message || 'Erreur', true);
            }
        } catch (error) {
            showNotification('Erreur', true);
        }
    };

    // Restaurer
    window.restoreTask = async function(id) {
        try {
            const response = await fetch(`${API_URL}/${id}/restore`, { method: 'POST' });
            const result = await response.json();

            if (result.success) {
                showNotification('Tache restauree');
                loadTasks(currentPage);
                loadTrash();
            }
        } catch (error) {
            showNotification('Erreur', true);
        }
    };

    // Supprimer définitivement
    window.forceDeleteTask = async function(id) {
        if (!confirm('Supprimer definitivement cette tache ? Cette action est irreversible.')) return;

        try {
            const response = await fetch(`${API_URL}/${id}/force`, { method: 'DELETE' });
            const result = await response.json();

            if (result.success) {
                showNotification('Tache supprimee definitivement');
                loadTrash();
            }
        } catch (error) {
            showNotification('Erreur', true);
        }
    };

    // Charger la corbeille
    async function loadTrash() {
        try {
            const response = await fetch(`${API_URL}/trash`);
            const result = await response.json();

            if (result.success && result.data.length > 0) {
                trashList.innerHTML = result.data.map(task => `
                    <div class="task-item" style="opacity: 0.7;">
                        <div class="task-info">
                            <strong>${escapeHtml(task.title)}</strong>
                            <br><span class="muted">Supprime le: ${task.deleted_at}</span>
                        </div>
                        <div class="task-actions">
                            <button class="btn-success" onclick="restoreTask(${task.id})">Restaurer</button>
                            <button class="btn-danger" onclick="forceDeleteTask(${task.id})">Supprimer</button>
                        </div>
                    </div>
                `).join('');
            } else {
                trashList.innerHTML = '<p class="muted">La corbeille est vide</p>';
            }
        } catch (error) {
            trashList.innerHTML = '<p class="muted">Erreur</p>';
        }
    }

    // Annuler
    btnCancel.addEventListener('click', resetForm);

    function resetForm() {
        taskIdInput.value = '';
        taskTitleInput.value = '';
        taskDescInput.value = '';
        taskUserSelect.value = '';
        taskPrioritySelect.value = 'medium';
        taskStatusSelect.value = 'pending';
        taskDueInput.value = '';
        formTitle.textContent = 'Ajouter une tache';
        btnSubmit.textContent = 'Enregistrer';
        btnCancel.style.display = 'none';
    }

    // Filtres
    searchInput.addEventListener('input', () => loadTasks(1));
    filterStatus.addEventListener('change', () => loadTasks(1));
    filterPriority.addEventListener('change', () => loadTasks(1));

    // Corbeille
    document.getElementById('btn-show-trash').addEventListener('click', () => {
        trashSection.style.display = 'block';
        loadTrash();
    });
    document.getElementById('btn-hide-trash').addEventListener('click', () => {
        trashSection.style.display = 'none';
    });

    // Exposer loadTasks globalement pour la pagination
    window.loadTasks = loadTasks;

    // Init
    loadUsers();
    loadTasks();
})();
</script>
