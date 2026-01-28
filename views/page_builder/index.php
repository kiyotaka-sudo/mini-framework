<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?> - DEBA Builder</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        :root {
            --sidebar-width: 300px;
            --props-width: 300px;
        }

        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
            background-color: #0f172a;
        }

        /* Sidebar: Components Palette */
        .palette {
            width: var(--sidebar-width);
            background: #1e293b;
            border-right: 1px solid rgba(255,255,255,0.1);
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
        }

        .palette h2 { font-size: 1.2rem; margin-bottom: 1.5rem; color: #818cf8; }

        .comp-item {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 0.75rem;
            cursor: grab;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .comp-item:hover {
            background: rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
        }

        .comp-icon { font-size: 1.5rem; }

        /* Canvas: The drop zone */
        .canvas-container {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #canvas {
            width: 100%;
            max-width: 900px;
            min-height: 80vh;
            background: #0f172a;
            border: 2px dashed rgba(255,255,255,0.1);
            border-radius: 1rem;
            padding: 2rem;
            transition: all 0.3s;
            position: relative;
        }

        #canvas.drag-over {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.05);
        }

        /* Dropped Block Style */
        .dropped-block {
            position: relative;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            transition: all 0.2s;
            cursor: pointer;
        }

        .dropped-block:hover {
            border: 1px solid #6366f1;
            border-radius: 0.5rem;
        }

        .block-actions {
            position: absolute;
            top: -10px;
            right: 10px;
            display: none;
            gap: 0.5rem;
            z-index: 10;
        }

        .dropped-block:hover .block-actions {
            display: flex;
        }

        .action-btn {
            background: #6366f1;
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn.delete { background: #ef4444; }

        /* Property Editor */
        .props-panel {
            width: var(--props-width);
            background: #1e293b;
            border-left: 1px solid rgba(255,255,255,0.1);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }

        .prop-group { margin-bottom: 1.5rem; }
        .prop-group label { display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: #94a3b8; }
        .prop-group input, .prop-group textarea, .prop-group select {
            width: 100%;
            background: #0f172a;
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            padding: 0.5rem;
            border-radius: 0.4rem;
        }

        /* Toolbar */
        .toolbar {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            padding: 0.75rem 2rem;
            border-radius: 3rem;
            border: 1px solid rgba(255,255,255,0.1);
            display: flex;
            gap: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            z-index: 100;
        }

        /* Empty State */
        .canvas-empty {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #475569;
            text-align: center;
            pointer-events: none;
        }
    </style>
</head>
<body>

    <!-- Palette de composants -->
    <aside class="palette">
        <h2>Blocs</h2>
        <div class="comp-item" draggable="true" data-type="hero">
            <span class="comp-icon">🌟</span>
            <span>En-tête Hero</span>
        </div>
        <div class="comp-item" draggable="true" data-type="text">
            <span class="comp-icon">📝</span>
            <span>Paragraphe</span>
        </div>
        <div class="comp-item" draggable="true" data-type="grid">
            <span class="comp-icon">🪟</span>
            <span>Grille de Cartes</span>
        </div>
        <div class="comp-item" draggable="true" data-type="image">
            <span class="comp-icon">🖼️</span>
            <span>Image</span>
        </div>
        <div class="comp-item" draggable="true" data-type="cta">
            <span class="comp-icon">🔘</span>
            <span>Appel à l'Action</span>
        </div>
    </aside>

    <!-- Canvas principal -->
    <main class="canvas-container">
        <h1 style="margin-bottom: 2rem;">Concepteur de Page</h1>
        <div id="canvas">
            <div class="canvas-empty">
                <span style="font-size: 4rem;">🖱️</span>
                <p>Glissez des blocs ici pour commencer votre mise en page</p>
            </div>
        </div>
    </main>

    <!-- Éditeur de propriétés -->
    <aside class="props-panel" id="props-panel">
        <h2>Propriétés</h2>
        <div id="no-selection" style="color: #64748b;">Sélectionnez un bloc pour l'éditer</div>
        <div id="prop-editor" style="display: none;">
            <!-- Les champs d'édition seront injectés ici -->
        </div>
    </aside>

    <!-- Barre d'outils -->
    <div class="toolbar">
        <input type="text" id="page-name" placeholder="Nom de la page..." style="background: #0f172a; border: 1px solid #334155; color: white; border-radius: 0.5rem; padding: 0.5rem 1rem; width: 150px;">
        <div style="display: flex; align-items: center; background: #0f172a; border: 1px solid #334155; border-radius: 0.5rem; overflow: hidden;">
            <span style="padding: 0.5rem 0.75rem; background: #1e293b; color: #64748b; font-size: 0.9rem;">/</span>
            <input type="text" id="page-route" placeholder="ma-page" style="background: transparent; border: none; color: white; padding: 0.5rem 0.75rem; width: 120px; outline: none;">
        </div>
        <button class="cta" onclick="savePage()">Enregistrer la Page</button>
        <button class="cta secondary" onclick="window.location.href='/admin'">Quitter</button>
    </div>


    <!-- Scripts -->
    <script>
        let layout = [];
        let selectedBlockIndex = null;

        const canvas = document.getElementById('canvas');
        const emptyState = canvas.querySelector('.canvas-empty');
        const propEditor = document.getElementById('prop-editor');
        const noSelection = document.getElementById('no-selection');

        // Initialisation Drag & Drop
        document.querySelectorAll('.comp-item').forEach(item => {
            item.addEventListener('dragstart', e => {
                e.dataTransfer.setData('type', item.dataset.type);
            });
        });

        canvas.addEventListener('dragover', e => {
            e.preventDefault();
            canvas.classList.add('drag-over');
        });

        canvas.addEventListener('dragleave', () => {
            canvas.classList.remove('drag-over');
        });

        canvas.addEventListener('drop', e => {
            e.preventDefault();
            canvas.classList.remove('drag-over');
            const type = e.dataTransfer.getData('type');
            if (type) addBlock(type);
        });

        function addBlock(type) {
            const block = {
                type: type,
                content: getDefaultContent(type),
                styles: {}
            };
            layout.push(block);
            renderCanvas();
            selectBlock(layout.length - 1);
        }

        function getDefaultContent(type) {
            switch(type) {
                case 'hero': return { title: 'Titre Incroyable', subtitle: 'Une description captivante pour votre site.' };
                case 'text': return { text: 'Commencez à écrire votre contenu ici...' };
                case 'grid': return { columns: 'three', items: [{ title: 'Feature 1', body: 'Description...' }] };
                case 'image': return { url: 'https://images.unsplash.com/photo-1498050108023-c5249f4df085', alt: 'Aperçu' };
                case 'cta': return { label: 'Cliquez ici', link: '#' };
                default: return {};
            }
        }

        function renderCanvas() {
            if (layout.length > 0) emptyState.style.display = 'none';
            else emptyState.style.display = 'flex';

            canvas.querySelectorAll('.dropped-block').forEach(b => b.remove());

            layout.forEach((block, index) => {
                const div = document.createElement('div');
                div.className = 'dropped-block';
                div.onclick = (e) => { e.stopPropagation(); selectBlock(index); };
                
                let innerHtml = renderPreview(block);
                
                div.innerHTML = `
                    <div class="block-actions">
                        <button class="action-btn" onclick="moveBlock(${index}, -1)">↑</button>
                        <button class="action-btn" onclick="moveBlock(${index}, 1)">↓</button>
                        <button class="action-btn delete" onclick="deleteBlock(${index})">×</button>
                    </div>
                    ${innerHtml}
                `;
                canvas.appendChild(div);
            });
        }

        function renderPreview(block) {
            switch(block.type) {
                case 'hero': return `<section class="hero card" style="margin:0; text-align:center;"><h1>${block.content.title}</h1><p>${block.content.subtitle}</p></section>`;
                case 'text': return `<p>${block.content.text}</p>`;
                case 'grid': return `<div class="grid ${block.content.columns}">${block.content.items.map(i => `<div class="card"><h3>${i.title}</h3></div>`).join('')}</div>`;
                case 'image': return `<img src="${block.content.url}" style="width:100%; border-radius:0.5rem;">`;
                case 'cta': return `<div style="text-align:center;"><button class="cta">${block.content.label}</button></div>`;
                default: return '';
            }
        }

        function selectBlock(index) {
            selectedBlockIndex = index;
            noSelection.style.display = 'none';
            propEditor.style.display = 'block';
            
            const block = layout[index];
            let html = `<h3>Editer ${block.type}</h3>`;
            
            for (let key in block.content) {
                if (key === 'items') continue;
                html += `
                    <div class="prop-group">
                        <label>${key}</label>
                        ${key === 'text' ? `<textarea oninput="updateProp('${key}', this.value)">${block.content[key]}</textarea>` : 
                        `<input type="text" value="${block.content[key]}" oninput="updateProp('${key}', this.value)">`}
                    </div>
                `;
            }
            
            propEditor.innerHTML = html;
        }

        function updateProp(key, value) {
            layout[selectedBlockIndex].content[key] = value;
            renderCanvas();
        }

        function deleteBlock(index) {
            layout.splice(index, 1);
            selectedBlockIndex = null;
            noSelection.style.display = 'block';
            propEditor.style.display = 'none';
            renderCanvas();
        }

        function moveBlock(index, dir) {
            const newIndex = index + dir;
            if (newIndex < 0 || newIndex >= layout.length) return;
            const element = layout[index];
            layout.splice(index, 1);
            layout.splice(newIndex, 0, element);
            renderCanvas();
        }

        async function savePage() {
            const name = document.getElementById('page-name').value;
            const route = document.getElementById('page-route').value;
            
            if (!name) return alert('Veuillez donner un nom à la page');
            if (!route) return alert('Veuillez spécifier une route pour la page (ex: ma-page)');
            
            const res = await fetch('/page-builder/save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, route: '/' + route.replace(/^\//, ''), layout })
            });
            
            const data = await res.json();
            if (data.success) {
                alert(data.message + '\n\nVotre page est accessible sur: ' + data.url);
                window.location.href = '/admin';
            } else {
                alert('Erreur: ' + data.error);
            }
        }

    </script>
</body>
</html>
