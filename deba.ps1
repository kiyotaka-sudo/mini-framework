# DEBA Framework CLI
# Usage: .\deba.ps1 start|stop|migrate|routes [-a]

param(
    [Parameter(Mandatory=$true)]
    [string]$Command,
    
    [Parameter(Mandatory=$false)]
    [string]$Arg,
    
    [switch]$a  # Auto-install dependencies
)

$ProjectRoot = $PSScriptRoot
$PhpPath = "C:\xampp\php\php.exe"
$ComposerPath = "composer"

# Check if PHP is installed
function Test-PhpInstalled {
    if (-not (Test-Path $PhpPath)) {
        Write-Host "❌ PHP introuvable à: $PhpPath" -ForegroundColor Red
        Write-Host ""
        Write-Host "Solutions:" -ForegroundColor Yellow
        Write-Host "  1. Installez XAMPP depuis https://www.apachefriends.org" -ForegroundColor White
        Write-Host "  2. Ou modifiez le chemin dans deba.ps1 (ligne 12)" -ForegroundColor White
        return $false
    }
    return $true
}

# Check if Composer is installed
function Test-ComposerInstalled {
    # Try global composer command
    try {
        $null = & composer --version 2>&1
        return $true
    } catch {}
    
    # Try composer.phar in current directory
    if (Test-Path "$ProjectRoot\composer.phar") {
        $script:ComposerPath = "$PhpPath $ProjectRoot\composer.phar"
        return $true
    }
    
    # Not found
    Write-Host "❌ Composer introuvable" -ForegroundColor Red
    Write-Host ""
    Write-Host "Solutions:" -ForegroundColor Yellow
    Write-Host "  1. Installez Composer globalement: https://getcomposer.org" -ForegroundColor White
    Write-Host "  2. Ou téléchargez composer.phar dans ce dossier" -ForegroundColor White
    Write-Host "     curl -sS https://getcomposer.org/installer | php" -ForegroundColor Cyan
    return $false
}

# Check if vendor directory exists
function Test-VendorInstalled {
    if (-not (Test-Path "$ProjectRoot\vendor")) {
        Write-Host "⚠️  Dossier 'vendor' introuvable" -ForegroundColor Yellow
        Write-Host "Les dépendances Composer ne sont pas installées." -ForegroundColor White
        return $false
    }
    return $true
}

# Check if .env file exists
function Test-EnvFile {
    if (-not (Test-Path "$ProjectRoot\.env")) {
        Write-Host "⚠️  Fichier .env introuvable" -ForegroundColor Yellow
        if (Test-Path "$ProjectRoot\.env.example") {
            Write-Host "Copie de .env.example vers .env..." -ForegroundColor Cyan
            Copy-Item "$ProjectRoot\.env.example" "$ProjectRoot\.env"
            Write-Host "✅ Fichier .env créé" -ForegroundColor Green
        } else {
            Write-Host "❌ .env.example introuvable" -ForegroundColor Red
            return $false
        }
    }
    return $true
}

# Install Composer dependencies
function Install-Dependencies {
    Write-Host "📦 Installation des dépendances Composer..." -ForegroundColor Cyan
    Push-Location $ProjectRoot
    try {
        if ($script:ComposerPath -like "*composer.phar*") {
            & $PhpPath $ProjectRoot\composer.phar install
        } else {
            & $ComposerPath install
        }
        Write-Host "✅ Dépendances installées" -ForegroundColor Green
        return $true
    } catch {
        Write-Host "❌ Erreur lors de l'installation" -ForegroundColor Red
        return $false
    } finally {
        Pop-Location
    }
}

# Check all requirements
function Test-Environment {
    param([bool]$AutoFix = $false)
    
    $allOk = $true
    
    # Check PHP
    if (-not (Test-PhpInstalled)) {
        return $false  # Critical - cannot continue
    }
    
    # Check Composer
    $composerOk = Test-ComposerInstalled
    if (-not $composerOk) {
        if ($AutoFix) {
            Write-Host ""
            Write-Host "Impossible de continuer sans Composer." -ForegroundColor Red
        }
        return $false  # Critical - cannot continue
    }
    
    # Check vendor
    if (-not (Test-VendorInstalled)) {
        if ($AutoFix) {
            Write-Host ""
            Write-Host "Installation automatique des dépendances..." -ForegroundColor Cyan
            if (-not (Install-Dependencies)) {
                return $false
            }
        } else {
            Write-Host ""
            Write-Host "Exécutez: composer install" -ForegroundColor Yellow
            Write-Host "   ou: .\deba.ps1 start -a" -ForegroundColor Yellow
            return $false
        }
    }
    
    # Check .env
    if (-not (Test-EnvFile)) {
        return $false
    }
    
    return $true
}

# Check if a port is available
function Test-PortAvailable {
    param([int]$Port)
    
    try {
        $listener = New-Object System.Net.Sockets.TcpListener([System.Net.IPAddress]::Any, $Port)
        $listener.Start()
        $listener.Stop()
        return $true
    } catch {
        return $false
    }
}

function Start-Server {
    # Check environment first
    if (-not (Test-Environment -AutoFix $a)) {
        Write-Host ""
        Write-Host "Impossible de démarrer le serveur: environnement non prêt" -ForegroundColor Red
        return
    }
    
    # Parse host and port
    $serverHost = if ($Arg) { $Arg } else { "localhost:8000" }
    
    if ($serverHost -match "^(.+):(\d+)$") {
        $hostname = $matches[1]
        $port = [int]$matches[2]
    } else {
        Write-Host "❌ Format invalide. Utilisez: host:port (ex: localhost:8000)" -ForegroundColor Red
        return
    }
    
    # Check if port is available
    if (-not (Test-PortAvailable -Port $port)) {
        Write-Host ""
        Write-Host "❌ Le port $port est déjà utilisé!" -ForegroundColor Red
        Write-Host ""
        Write-Host "Solutions:" -ForegroundColor Yellow
        Write-Host "  1. Arrêtez l'application qui utilise ce port" -ForegroundColor White
        Write-Host "  2. Utilisez un autre port:" -ForegroundColor White
        Write-Host "     .\deba.ps1 start localhost:3000" -ForegroundColor Cyan
        Write-Host "     .\deba.ps1 start localhost:8080" -ForegroundColor Cyan
        Write-Host "     .\deba.ps1 start localhost:5000" -ForegroundColor Cyan
        Write-Host ""
        
        # Suggest alternative ports
        $suggestedPorts = @(3000, 8080, 5000, 9000, 8001)
        foreach ($testPort in $suggestedPorts) {
            if (Test-PortAvailable -Port $testPort) {
                Write-Host "✅ Port $testPort disponible - Utilisez: .\deba.ps1 start localhost:$testPort" -ForegroundColor Green
                break
            }
        }
        
        return
    }
    
    Write-Host ""
    Write-Host "══════════════════════════════════════" -ForegroundColor Cyan
    Write-Host "   🚀 Démarrage du serveur DEBA       " -ForegroundColor Green
    Write-Host "══════════════════════════════════════" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Serveur accessible sur: http://$serverHost" -ForegroundColor White
    Write-Host "Appuyez sur Ctrl+C pour arrêter" -ForegroundColor Gray
    Write-Host ""
    
    Push-Location $ProjectRoot
    try {
        & $PhpPath bin\console serve $serverHost
    } finally {
        Pop-Location
    }
}

function Stop-Server {
    Write-Host "⏹️  Arrêt du serveur DEBA..." -ForegroundColor Yellow
    $processes = Get-Process -ErrorAction SilentlyContinue | Where-Object { 
        $_.ProcessName -eq "php" -and $_.Path -like "*xampp*" 
    }
    
    if ($processes) {
        $processes | Stop-Process -Force
        Write-Host "✅ Serveur arrêté ($($processes.Count) processus)" -ForegroundColor Green
    } else {
        Write-Host "ℹ️  Aucun serveur en cours d'exécution" -ForegroundColor Cyan
    }
}

function Run-Migrations {
    if (-not (Test-Environment)) {
        return
    }
    
    Write-Host "📦 Exécution des migrations..." -ForegroundColor Cyan
    Push-Location $ProjectRoot
    try {
        & $PhpPath bin\console migrate
    } finally {
        Pop-Location
    }
}

function Show-Routes {
    if (-not (Test-Environment)) {
        return
    }
    
    Write-Host "🗺️  Routes enregistrées:" -ForegroundColor Cyan
    Write-Host ""
    Push-Location $ProjectRoot
    try {
        & $PhpPath bin\console routes
    } finally {
        Pop-Location
    }
}

function Show-Status {
    Write-Host ""
    Write-Host "════════════════════════════════════════" -ForegroundColor Cyan
    Write-Host "   📊 État de l'environnement DEBA       " -ForegroundColor White
    Write-Host "════════════════════════════════════════" -ForegroundColor Cyan
    Write-Host ""
    
    # PHP Check
    Write-Host "PHP:        " -NoNewline
    if (Test-PhpInstalled) {
        $version = & $PhpPath -v 2>&1 | Select-String "PHP (\d+\.\d+\.\d+)" | ForEach-Object { $_.Matches.Groups[1].Value }
        Write-Host "✅ v$version" -ForegroundColor Green
    } else {
        Write-Host "❌ Non installé" -ForegroundColor Red
    }
    
    # Composer Check
    Write-Host "Composer:   " -NoNewline
    if (Test-ComposerInstalled) {
        Write-Host "✅ Installé" -ForegroundColor Green
    } else {
        Write-Host "❌ Non installé" -ForegroundColor Red
    }
    
    # Vendor Check
    Write-Host "Dépendances:" -NoNewline
    if (Test-VendorInstalled) {
        Write-Host "✅ Installées" -ForegroundColor Green
    } else {
        Write-Host "❌ Manquantes" -ForegroundColor Red
    }
    
    # .env Check
    Write-Host ".env:       " -NoNewline
    if (Test-Path "$ProjectRoot\.env") {
        Write-Host "✅ Présent" -ForegroundColor Green
    } else {
        Write-Host "❌ Manquant" -ForegroundColor Red
    }
    
    # Server Status
    Write-Host "Serveur:    " -NoNewline
    $running = Get-Process -ErrorAction SilentlyContinue | Where-Object { 
        $_.ProcessName -eq "php" -and $_.Path -like "*xampp*" 
    }
    if ($running) {
        Write-Host "🟢 En cours" -ForegroundColor Green
    } else {
        Write-Host "⚪ Arrêté" -ForegroundColor Gray
    }
    
    Write-Host ""
}

function Show-Help {
    Write-Host @"

╔══════════════════════════════════════════╗
║        DEBA Framework - CLI Tool         ║
╚══════════════════════════════════════════╝

Usage: .\deba.ps1 <commande> [options]

Commandes disponibles:
  start [host:port]   Démarre le serveur de développement
                      -a : Auto-installe les dépendances si manquantes
  stop                Arrête le serveur
  migrate             Exécute les migrations de base de données
  routes              Affiche toutes les routes enregistrées
  status              Affiche l'état de l'environnement
  help                Affiche cette aide

Exemples:
  .\deba.ps1 start              # Démarrage normal
  .\deba.ps1 start -a           # Avec auto-installation
  .\deba.ps1 start localhost:3000
  .\deba.ps1 migrate
  .\deba.ps1 status

"@ -ForegroundColor White
}

# Main execution
switch ($Command.ToLower()) {
    "start"   { Start-Server }
    "stop"    { Stop-Server }
    "migrate" { Run-Migrations }
    "routes"  { Show-Routes }
    "status"  { Show-Status }
    "help"    { Show-Help }
    default   { 
        Write-Host "❌ Commande inconnue: $Command" -ForegroundColor Red
        Write-Host ""
        Show-Help
    }
}

