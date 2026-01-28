<?php
/** @var string $title */
$layout = 'layouts/app.php';
?>

<div class="Subhead">
    <div class="Subhead-heading">🚀 Visual Project Builder</div>
    <div class="Subhead-description">Concevez votre base de données graphiquement, générez tout automatiquement</div>
</div>

<div class="d-flex gutter" style="gap: 24px;">
    <!-- Sidebar -->
    <div class="col-3">
        <div class="Box box-shadow">
            <div class="Box-header">
                <h3 class="Box-title">Actions</h3>
            </div>
            <div class="Box-body">
                <button class="btn btn-primary btn-block mb-2" onclick="addEntity()">
                    <span class="octicon octicon-plus"></span> Ajouter une Table
                </button>
                <button class="btn btn-outline btn-block" onclick="generateProject()">
                    <span class="octicon octicon-zap"></span> Générer le Projet
                </button>
            </div>
            <div class="Box-footer">
                <h4 class="h5 mb-2">Types de Champs</h4>
                <ul class="filter-list small">
                    <li><span class="filter-item">📝 <strong>string</strong> - Texte court</span></li>
                    <li><span class="filter-item">📄 <strong>text</strong> - Texte long</span></li>
                    <li><span class="filter-item">🔢 <strong>integer</strong> - Nombre entier</span></li>
                    <li><span class="filter-item">💰 <strong>decimal</strong> - Nombre décimal</span></li>
                    <li><span class="filter-item">📅 <strong>date</strong> - Date</span></li>
                    <li><span class="filter-item">✅ <strong>boolean</strong> - Vrai/Faux</span></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Canvas -->
    <div class="col-9">
        <div id="canvas">
            <div class="blankslate">
                <h3 class="mb-1">👆 Commencez par ajouter une table</h3>
                <p>Cliquez sur "Ajouter une Table" pour démarrer</p>
            </div>
        </div>
        
        <div id="result" class="flash mt-3" style="display: none;"></div>
    </div>
</div>

<script>
    let entities = [];
    let entityCounter = 0;
    
    function addEntity() {
        const id = ++entityCounter;
        const entity = {
            id: id,
            name: '',
            table: '',
            fields: []
        };
        
        entities.push(entity);
        renderCanvas();
        
        setTimeout(() => {
            const input = document.querySelector(`#entity-${id} input[name="name"]`);
            if(input) input.focus();
        }, 100);
    }
    
    function deleteEntity(id) {
        if (!confirm('Supprimer cette table ?')) return;
        entities = entities.filter(e => e.id !== id);
        renderCanvas();
    }
    
    function addField(entityId) {
        const entity = entities.find(e => e.id === entityId);
        if (!entity) return;
        
        entity.fields.push({
            name: '',
            type: 'string',
            nullable: false
        });
        
        renderCanvas();
    }
    
    function deleteField(entityId, fieldIndex) {
        const entity = entities.find(e => e.id === entityId);
        if (!entity) return;
        
        entity.fields.splice(fieldIndex, 1);
        renderCanvas();
    }
    
    function updateEntity(entityId, field, value) {
        const entity = entities.find(e => e.id === entityId);
        if (!entity) return;
        
        entity[field] = value;
        
        if (field === 'name' && value) {
            entity.table = value.toLowerCase().replace(/\s+/g, '_') + 's';
            renderCanvas();
        }
    }
    
    function updateField(entityId, fieldIndex, field, value) {
        const entity = entities.find(e => e.id === entityId);
        if (!entity) return;
        
        entity.fields[fieldIndex][field] = field === 'nullable' ? value : value;
    }
    
    function renderCanvas() {
        const canvas = document.getElementById('canvas');
        
        if (entities.length === 0) {
            canvas.innerHTML = `
                <div class="blankslate">
                    <h3 class="mb-1">👆 Commencez par ajouter une table</h3>
                    <p>Cliquez sur "Ajouter une Table" pour démarrer</p>
                </div>
            `;
            return;
        }
        
        canvas.innerHTML = entities.map(entity => `
            <div class="Box box-shadow mb-4" id="entity-${entity.id}">
                <div class="Box-header d-flex flex-justify-between flex-items-center bg-gray-light">
                    <h4 class="Box-title m-0">${entity.name || 'Nouvelle Table'}</h4>
                    <button class="btn btn-sm btn-danger" onclick="deleteEntity(${entity.id})">Supprimer</button>
                </div>
                
                <div class="Box-body">
                    <div class="d-flex gutter mb-3">
                        <div class="col-6">
                            <label class="d-block mb-1">Nom de l'entité</label>
                            <input type="text" class="form-control width-full" name="name" value="${entity.name}" 
                                   onchange="updateEntity(${entity.id}, 'name', this.value)"
                                   placeholder="Product">
                        </div>
                        <div class="col-6">
                            <label class="d-block mb-1">Nom de la table</label>
                            <input type="text" class="form-control width-full" name="table" value="${entity.table}" 
                                   onchange="updateEntity(${entity.id}, 'table', this.value)"
                                   placeholder="products">
                        </div>
                    </div>
                    
                    <div class="border-top pt-3">
                        <h5 class="mb-2">Champs</h5>
                        ${entity.fields.map((field, index) => `
                            <div class="d-flex flex-items-center gutter-condensed mb-2">
                                <div class="col-4">
                                    <input type="text" class="form-control width-full input-sm" placeholder="Nom" value="${field.name}"
                                           onchange="updateField(${entity.id}, ${index}, 'name', this.value)">
                                </div>
                                <div class="col-3">
                                    <select class="form-select width-full select-sm" onchange="updateField(${entity.id}, ${index}, 'type', this.value)">
                                        <option value="string" ${field.type === 'string' ? 'selected' : ''}>String</option>
                                        <option value="text" ${field.type === 'text' ? 'selected' : ''}>Text</option>
                                        <option value="integer" ${field.type === 'integer' ? 'selected' : ''}>Integer</option>
                                        <option value="decimal" ${field.type === 'decimal' ? 'selected' : ''}>Decimal</option>
                                        <option value="date" ${field.type === 'date' ? 'selected' : ''}>Date</option>
                                        <option value="boolean" ${field.type === 'boolean' ? 'selected' : ''}>Boolean</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="text-small">
                                        <input type="checkbox" ${field.nullable ? 'checked' : ''}
                                               onchange="updateField(${entity.id}, ${index}, 'nullable', this.checked)">
                                        Nullable
                                    </label>
                                </div>
                                <div class="col-2 text-right">
                                    <button class="btn-link text-red" onclick="deleteField(${entity.id}, ${index})">✕</button>
                                </div>
                            </div>
                        `).join('')}
                        <button class="btn btn-sm mt-2" onclick="addField(${entity.id})">
                            ➕ Ajouter un champ
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    async function generateProject() {
        if (entities.length === 0) {
            alert('Ajoutez au moins une table !');
            return;
        }
        
        for (const entity of entities) {
            if (!entity.name || !entity.table) {
                alert(`La table "${entity.name || 'sans nom'}" doit avoir un nom !`);
                return;
            }
        }
        
        const schema = {
            entities: entities.map(e => ({
                name: e.name,
                table: e.table,
                fields: e.fields.filter(f => f.name)
            }))
        };
        
        const resultDiv = document.getElementById('result');
        resultDiv.className = 'flash flash-warn mt-3';
        resultDiv.innerHTML = '⏳ Génération en cours...';
        resultDiv.style.display = 'block';
        
        try {
            const response = await fetch('/builder/generate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(schema)
            });
            
            const result = await response.json();
            
            if (result.success) {
                resultDiv.className = 'flash flash-success mt-3';
                resultDiv.innerHTML = `
                    <h4 class="mb-2">✅ Projet généré avec succès !</h4>
                    <p><strong>Fichiers créés:</strong></p>
                    <ul class="ml-4">
                        ${Object.entries(result.files).map(([type, files]) => 
                            files.map(f => `<li>${f}</li>`).join('')
                        ).join('')}
                    </ul>
                    <p class="mt-2">
                        Visitez <strong>/admin/${schema.entities[0].table}</strong> pour voir l'interface CRUD !
                    </p>
                `;
            } else {
                throw new Error(result.error || 'Generation failed');
            }
        } catch (error) {
            resultDiv.className = 'flash flash-error mt-3';
            resultDiv.innerHTML = `
                <h4>❌ Erreur</h4>
                <p>${error.message}</p>
            `;
        }
    }
    
    renderCanvas();
</script>
