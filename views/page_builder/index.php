<?php
/** @var string $title */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/github-theme.css">
</head>
<body class="bg-white">

<style>
    .builder-layout {
        height: calc(100vh - 70px);
        overflow: hidden;
        display: flex; /* Enforce flex layout */
    }
    
    /* Layout Utilities that are missing from github-theme.css */
    .d-flex { display: flex; }
    .flex-items-center { align-items: center; }
    .flex-column { flex-direction: column; }
    .justify-between { justify-content: space-between; }
    
    .col-2 { width: 20%; min-width: 250px; }
    .col-7 { flex: 1; }
    .col-3 { width: 25%; min-width: 300px; }
    
    .border-right { border-right: 1px solid #d0d7de; }
    .border-left { border-left: 1px solid #d0d7de; }
    .p-3 { padding: 16px !important; }
    .p-4 { padding: 24px !important; }
    .mb-2 { margin-bottom: 8px !important; }
    .mb-3 { margin-bottom: 16px !important; }
    .mr-2 { margin-right: 8px !important; }
    .ml-2 { margin-left: 8px !important; }
    .mt-5 { margin-top: 32px !important; }
    
    .bg-gray-light { background-color: #f6f8fa; }
    .bg-white { background-color: #ffffff; }
    
    .comp-item {
        cursor: grab;
        transition: all 0.2s;
        background: white;
        border: 1px solid #d0d7de;
        border-radius: 6px;
    }
    .comp-item:hover {
        background-color: #f6f8fa;
        border-color: #0969da;
        color: #0969da;
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.08);
    }
    
    #canvas {
        min-height: 100%;
        transition: background 0.2s;
    }
    #canvas.drag-over {
        background-color: #f0f8ff;
        border-color: #0969da !important;
    }
    
    .dropped-block {
        position: relative;
        border: 1px solid transparent;
        transition: all 0.2s;
        cursor: pointer;
    }
    .dropped-block:hover {
        border-color: #0969da;
        border-radius: 6px;
        background-color: rgba(9, 105, 218, 0.03);
    }
    
    .block-actions {
        display: none;
        position: absolute;
        top: -12px;
        right: 10px;
        z-index: 10;
        background: white;
        border: 1px solid #d0d7de;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
    }
    .dropped-block:hover .block-actions {
        display: flex;
    }
</style>

<div class="Subhead p-3 border-bottom mb-0 bg-gray-light">
    <div class="Subhead-heading">Concepteur de Page</div>
    <div class="Subhead-actions">
        <div class="d-flex flex-items-center">
            <span class="mr-2 text-gray">/</span>
            <input type="text" id="page-route" class="form-control input-sm mr-2" placeholder="slug">
            <input type="text" id="page-name" class="form-control input-sm mr-2" placeholder="Nom de la page">
            <button class="btn btn-primary btn-sm" onclick="savePage()">Enregistrer</button>
            <a href="/admin" class="btn btn-sm ml-2">Quitter</a>
        </div>
    </div>
</div>

<div class="d-flex builder-layout">
    <!-- Palette (Left) -->
    <div class="col-2 border-right bg-white p-3" style="overflow-y: auto;">
        <h4 class="f5 mb-3 text-gray-dark">Blocs</h4>
        
        <div class="comp-item Box p-2 mb-2 d-flex flex-items-center" draggable="true" data-type="hero">
            <span class="mr-2 h4 m-0">🌟</span>
            <span class="text-small text-bold">Hero Header</span>
        </div>
        
        <div class="comp-item Box p-2 mb-2 d-flex flex-items-center" draggable="true" data-type="text">
            <span class="mr-2 h4 m-0">📝</span>
            <span class="text-small text-bold">Paragraphe</span>
        </div>
        
        <div class="comp-item Box p-2 mb-2 d-flex flex-items-center" draggable="true" data-type="grid">
            <span class="mr-2 h4 m-0">🪟</span>
            <span class="text-small text-bold">Grille 3 Cols</span>
        </div>
        
        <div class="comp-item Box p-2 mb-2 d-flex flex-items-center" draggable="true" data-type="image">
            <span class="mr-2 h4 m-0">🖼️</span>
            <span class="text-small text-bold">Image</span>
        </div>
        
        <div class="comp-item Box p-2 mb-2 d-flex flex-items-center" draggable="true" data-type="cta">
            <span class="mr-2 h4 m-0">🔘</span>
            <span class="text-small text-bold">Bouton CTA</span>
        </div>
    </div>

    <!-- Canvas (Center) -->
    <div class="col-7 bg-gray-light p-4" style="overflow-y: auto; background-image: radial-gradient(#e1e4e8 1px, transparent 1px); background-size: 20px 20px;">
        <div id="canvas" class="border border-dashed rounded-2 p-4 bg-white box-shadow">
            <div class="blankslate pt-6">
                <span class="h1">🖱️</span>
                <h3 class="mb-1">Zone de construction</h3>
                <p>Glissez des blocs depuis le menu de gauche</p>
            </div>
        </div>
    </div>

    <!-- Properties (Right) -->
    <div class="col-3 border-left bg-white p-3" style="overflow-y: auto;">
        <h4 class="f5 mb-3 text-gray-dark">Propriétés</h4>
        
        <div id="no-selection" class="text-center text-gray mt-5">
            <p>Sélectionnez un bloc pour l'éditer</p>
        </div>
        
        <div id="prop-editor" style="display: none;">
            <!-- Properties injected here -->
        </div>
    </div>
</div>

<script>
    let layout = [];
    let selectedBlockIndex = null;

    const canvas = document.getElementById('canvas');
    const emptyState = canvas.querySelector('.blankslate');
    const propEditor = document.getElementById('prop-editor');
    const noSelection = document.getElementById('no-selection');

    // Drag & Drop
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
            case 'hero': return { title: 'Titre Principal', subtitle: 'Sous-titre accrocheur' };
            case 'text': return { text: 'Votre texte ici...' };
            case 'grid': return { columns: 'three', items: [{ title: 'Item 1', body: '...' }, { title: 'Item 2', body: '...' }, { title: 'Item 3', body: '...' }] };
            case 'image': return { url: 'https://via.placeholder.com/800x400', alt: 'Image' };
            case 'cta': return { label: 'Cliquez ici', link: '#' };
            default: return {};
        }
    }

    function renderCanvas() {
        if (layout.length > 0) emptyState.style.display = 'none';
        else emptyState.style.display = 'block';

        canvas.querySelectorAll('.dropped-block').forEach(b => b.remove());

        layout.forEach((block, index) => {
            const div = document.createElement('div');
            div.className = 'dropped-block p-2 mb-2';
            div.onclick = (e) => { e.stopPropagation(); selectBlock(index); };
            
            let innerHtml = renderPreview(block);
            
            div.innerHTML = `
                <div class="block-actions btn-group">
                    <button class="btn btn-sm" onclick="moveBlock(${index}, -1)" title="Monter">↑</button>
                    <button class="btn btn-sm" onclick="moveBlock(${index}, 1)" title="Descendre">↓</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteBlock(${index})" title="Supprimer">×</button>
                </div>
                ${innerHtml}
            `;
            canvas.appendChild(div);
        });
    }

    function renderPreview(block) {
        // Simple preview rendering tailored for the builder view
        switch(block.type) {
            case 'hero': return `<div class="p-4 text-center bg-gray-light rounded-1"><h1>${block.content.title}</h1><p class="lead">${block.content.subtitle}</p></div>`;
            case 'text': return `<p>${block.content.text}</p>`;
            case 'grid': return `<div class="d-flex gutter"><div class="col-4 p-2 border">1</div><div class="col-4 p-2 border">2</div><div class="col-4 p-2 border">3</div></div>`;
            case 'image': return `<img src="${block.content.url}" style="max-width:100%; height:auto;" class="rounded-1">`;
            case 'cta': return `<div class="text-center"><button class="btn btn-primary">${block.content.label}</button></div>`;
            default: return `<div class="border p-2">${block.type}</div>`;
        }
    }

    function selectBlock(index) {
        selectedBlockIndex = index;
        noSelection.style.display = 'none';
        propEditor.style.display = 'block';
        
        const block = layout[index];
        let html = `<div class="Box box-shadow"><div class="Box-header"><h5 class="Box-title">Editer ${block.type}</h5></div><div class="Box-body">`;
        
        for (let key in block.content) {
            if (key === 'items') continue; // Skip complex items for this demo
            const val = block.content[key];
            const isLong = key === 'text' || key === 'subtitle';
            
            html += `
                <div class="form-group">
                    <div class="form-group-header">
                        <label>${key}</label>
                    </div>
                    <div class="form-group-body">
                        ${isLong 
                            ? `<textarea class="form-control width-full" oninput="updateProp('${key}', this.value)">${val}</textarea>`
                            : `<input type="text" class="form-control width-full" value="${val}" oninput="updateProp('${key}', this.value)">`
                        }
                    </div>
                </div>
            `;
        }
        html += `</div></div>`;
        
        propEditor.innerHTML = html;
    }

    function updateProp(key, value) {
        if (selectedBlockIndex !== null) {
            layout[selectedBlockIndex].content[key] = value;
            renderCanvas();
        }
    }

    function deleteBlock(index) {
        layout.splice(index, 1);
        renderCanvas();
        selectedBlockIndex = null;
        propEditor.style.display = 'none';
        noSelection.style.display = 'block';
    }

    function moveBlock(index, dir) {
        const newIndex = index + dir;
        if (newIndex >= 0 && newIndex < layout.length) {
            const temp = layout[index];
            layout[index] = layout[newIndex];
            layout[newIndex] = temp;
            renderCanvas();
        }
    }

    function logMessage(message, type = 'info') {
        const logs = document.getElementById('console-logs');
        const colors = { info: '#c9d1d9', success: '#2ea043', error: '#f85149' };
        const time = new Date().toLocaleTimeString();
        
        const div = document.createElement('div');
        div.style.color = colors[type] || colors.info;
        div.style.marginBottom = '4px';
        div.innerText = `[${time}] ${message}`;
        
        logs.appendChild(div);
        document.getElementById('status-console').scrollTop = document.getElementById('status-console').scrollHeight;
    }

    async function savePage() {
        const name = document.getElementById('page-name').value;
        const route = document.getElementById('page-route').value;
        
        if (!name || !route) {
            logMessage('Error: Name and route are required!', 'error');
            return;
        }
        
        logMessage(`Compiling page '${name}' to '${route}'...`, 'info');
        
        try {
            const res = await fetch('/page-builder/save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, route: '/' + route.replace(/^\//, ''), layout })
            });
            
            const data = await res.json();
            
            if (data.success) {
                logMessage('Build Successful!', 'success');
                logMessage(`File generated: ${data.file}`, 'success');
                logMessage(`Route registered: ${data.url}`, 'success');
            } else {
                logMessage('Build Failed: ' + data.error, 'error');
            }
        } catch (e) {
            logMessage('Network Error: ' + e.message, 'error');
        }
    }
</script>
</body>
</html>
