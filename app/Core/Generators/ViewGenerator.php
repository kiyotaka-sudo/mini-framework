<?php

namespace App\Core\Generators;

use App\Core\CodeGenerator;

/**
 * Generates admin view files for CRUD operations
 */
class ViewGenerator extends CodeGenerator
{
    public function generate(array $schema): array
    {
        $files = [];
        
        foreach ($schema['entities'] ?? [] as $entity) {
            $entityName = $this->toPascalCase($entity['name']);
            $viewPrefix = $this->toSnakeCase($entity['name']);
            $fields = $entity['fields'] ?? [];
            
            // Generate index view
            $files[] = $this->generateIndexView($viewPrefix, $entityName, $fields);
            
            // Generate create view
            $files[] = $this->generateCreateView($viewPrefix, $entityName, $fields);
            
            // Generate edit view
            $files[] = $this->generateEditView($viewPrefix, $entityName, $fields);
        }
        
        return $files;
    }
    
    private function generateIndexView(string $viewPrefix, string $entityName, array $fields): string
    {
        $tableHeaders = $this->generateTableHeaders($fields);
        $tableRows = $this->generateTableRows($fields);
        
        $content = $this->renderTemplate($this->getIndexTemplate(), [
            'entityName' => $entityName,
            'viewPrefix' => $viewPrefix,
            'tableHeaders' => $tableHeaders,
            'tableRows' => $tableRows,
        ]);
        
        $filename = "views/admin/{$viewPrefix}/index.php";
        $this->writeFile($filename, $content);
        return $filename;
    }
    
    private function generateCreateView(string $viewPrefix, string $entityName, array $fields): string
    {
        $formFields = $this->generateFormFields($fields);
        
        $content = $this->renderTemplate($this->getCreateTemplate(), [
            'entityName' => $entityName,
            'viewPrefix' => $viewPrefix,
            'formFields' => $formFields,
        ]);
        
        $filename = "views/admin/{$viewPrefix}/create.php";
        $this->writeFile($filename, $content);
        return $filename;
    }
    
    private function generateEditView(string $viewPrefix, string $entityName, array $fields): string
    {
        $formFields = $this->generateFormFields($fields, true);
        
        $content = $this->renderTemplate($this->getEditTemplate(), [
            'entityName' => $entityName,
            'viewPrefix' => $viewPrefix,
            'formFields' => $formFields,
        ]);
        
        $filename = "views/admin/{$viewPrefix}/edit.php";
        $this->writeFile($filename, $content);
        return $filename;
    }
    
    private function generateTableHeaders(array $fields): string
    {
        $headers = ['<th>ID</th>'];
        foreach ($fields as $field) {
            $label = ucwords(str_replace('_', ' ', $field['name']));
            $headers[] = "<th>{$label}</th>";
        }
        $headers[] = '<th>Actions</th>';
        return implode("\n                ", $headers);
    }
    
    private function generateTableRows(array $fields): string
    {
        $cells = ['<td><?= $item[\'id\'] ?></td>'];
        foreach ($fields as $field) {
            $name = $field['name'];
            $cells[] = "<td><?= htmlspecialchars(\$item['{$name}']) ?></td>";
        }
        return implode("\n                    ", $cells);
    }
    
    private function generateFormFields(array $fields, bool $isEdit = false): string
    {
        $html = [];
        foreach ($fields as $field) {
            $name = $field['name'];
            $label = ucwords(str_replace('_', ' ', $name));
            $type = $this->getInputType($field['type'] ?? 'string');
            $value = $isEdit ? "<?= htmlspecialchars(\$item['{$name}'] ?? '') ?>" : '';
            
            $html[] = "<div class=\"form-group\">";
            $html[] = "    <label>{$label}</label>";
            $html[] = "    <input type=\"{$type}\" name=\"{$name}\" value=\"{$value}\" class=\"form-control\" required>";
            $html[] = "</div>";
        }
        return implode("\n            ", $html);
    }
    
    private function getInputType(string $fieldType): string
    {
        return match($fieldType) {
            'integer' => 'number',
            'decimal', 'float' => 'number',
            'date' => 'date',
            'datetime' => 'datetime-local',
            'text' => 'textarea',
            'boolean' => 'checkbox',
            default => 'text',
        };
    }
    
    private function getIndexTemplate(): string
    {
        return <<<'HTML'
<div class="Subhead">
    <div class="Subhead-heading">{{entityName}} Management</div>
    <div class="Subhead-actions">
        <a href="/admin/{{viewPrefix}}/create" class="btn btn-primary btn-sm">+ New {{entityName}}</a>
    </div>
</div>

<div class="Box">
    <div class="Box-header">
        <h3 class="Box-title">List of {{entityName}}</h3>
    </div>
    <div class="overflow-auto">
        <table class="width-full">
            <thead>
                <tr class="text-left">
                    {{tableHeaders}}
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr class="border-top">
                    {{tableRows}}
                    <td>
                        <a href="/admin/{{viewPrefix}}/<?= $item['id'] ?>/edit" class="btn btn-sm">Edit</a>
                        <button onclick="deleteItem(<?= $item['id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function deleteItem(id) {
    if (!confirm('Are you sure?')) return;
    fetch(`/api/{{viewPrefix}}/${id}`, { method: 'DELETE' })
        .then(() => location.reload());
}
</script>
HTML;
    }
    
    private function getCreateTemplate(): string
    {
        return <<<'HTML'
<div class="container-md my-4">
    <div class="Subhead">
        <div class="Subhead-heading">Create {{entityName}}</div>
    </div>
    
    <div class="Box">
        <div class="Box-header">
            <h3 class="Box-title">New Record</h3>
        </div>
        <div class="Box-body">
            <form id="createForm">
                {{formFields}}
                
                <div class="form-actions mt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="/admin/{{viewPrefix}}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('createForm').addEventListener('submit', async (e) => {
    e.preventDefault();
   const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    const response = await fetch('/api/{{viewPrefix}}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    if (response.ok) {
        window.location.href = '/admin/{{viewPrefix}}';
    }
});
</script>
HTML;
    }
    
    private function getEditTemplate(): string
    {
        return <<<'HTML'
<div class="container-md my-4">
    <div class="Subhead">
        <div class="Subhead-heading">Edit {{entityName}}</div>
    </div>
    
    <div class="Box">
        <div class="Box-header">
            <h3 class="Box-title">Edit Record #<?= $item['id'] ?></h3>
        </div>
        <div class="Box-body">
            <form id="editForm">
                {{formFields}}
                
                <div class="form-actions mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/admin/{{viewPrefix}}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const itemId = <?= $item['id'] ?>;
document.getElementById('editForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    const response = await fetch(`/api/{{viewPrefix}}/${itemId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    if (response.ok) {
        window.location.href = '/admin/{{viewPrefix}}';
    }
});
</script>
HTML;
    }
}
