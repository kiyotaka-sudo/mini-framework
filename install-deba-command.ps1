# Installation de l'alias DEBA

# Ajouter cette fonction à votre profil PowerShell
function deba {
    param([string]$Command, [string]$Arg)
    
    $ScriptPath = "C:\xampp\htdocs\mini-framework\deba.ps1"
    
    if ($Arg) {
        & $ScriptPath $Command $Arg
    } else {
        & $ScriptPath $Command
    }
}

Write-Host "✅ Fonction 'deba' installée dans cette session!" -ForegroundColor Green
Write-Host ""
Write-Host "Vous pouvez maintenant utiliser:" -ForegroundColor Cyan
Write-Host "  deba start" -ForegroundColor Yellow
Write-Host "  deba stop" -ForegroundColor Yellow
Write-Host "  deba migrate" -ForegroundColor Yellow
Write-Host "  deba routes" -ForegroundColor Yellow
Write-Host ""
Write-Host "Pour rendre cette fonction permanente:" -ForegroundColor White
Write-Host "1. Ouvrez votre profil PowerShell: notepad `$PROFILE" -ForegroundColor Gray
Write-Host "2. Ajoutez le contenu de ce fichier" -ForegroundColor Gray
Write-Host "3. Rechargez: . `$PROFILE" -ForegroundColor Gray
