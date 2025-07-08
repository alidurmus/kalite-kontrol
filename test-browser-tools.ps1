# BrowserTools MCP Test Script for PowerShell
# Windows ortamında BrowserTools MCP kurulumunu test eder

Write-Host "🚀 BrowserTools MCP Test Başlatılıyor..." -ForegroundColor Green

# 1. Chrome Extension kontrolü
Write-Host "`n📋 1. Chrome Extension Kontrolü" -ForegroundColor Yellow
Write-Host "Chrome'da extension yüklü olduğundan emin olun:"
Write-Host "- chrome://extensions/ adresine gidin"
Write-Host "- 'BrowserTools MCP Extension' aktif olmalı"

# 2. MCP Server kontrolü
Write-Host "`n🔧 2. MCP Server Kontrolü" -ForegroundColor Yellow
try {
    $mcpVersion = npm list -g @agentdeskai/browser-tools-mcp --depth=0 2>$null
    if ($mcpVersion) {
        Write-Host "✅ BrowserTools MCP global olarak yüklü" -ForegroundColor Green
        Write-Host $mcpVersion
    } else {
        Write-Host "❌ BrowserTools MCP global kurulumu bulunamadı" -ForegroundColor Red
        Write-Host "Kurulum için: npm install -g @agentdeskai/browser-tools-mcp"
    }
} catch {
    Write-Host "❌ npm komutu çalıştırılamadı" -ForegroundColor Red
}

# 3. Local dependency kontrolü
Write-Host "`n📦 3. Local Dependencies Kontrolü" -ForegroundColor Yellow
if (Test-Path "node_modules/@agentdeskai/browser-tools-mcp") {
    Write-Host "✅ BrowserTools MCP local olarak yüklü" -ForegroundColor Green
} else {
    Write-Host "❌ BrowserTools MCP local kurulumu bulunamadı" -ForegroundColor Red
    Write-Host "Kurulum için: npm install @agentdeskai/browser-tools-mcp --save-dev"
}

# 4. Konfigürasyon dosyası kontrolü
Write-Host "`n⚙️ 4. Konfigürasyon Kontrolü" -ForegroundColor Yellow
if (Test-Path "browser-tools-config.json") {
    Write-Host "✅ Konfigürasyon dosyası mevcut" -ForegroundColor Green
} else {
    Write-Host "❌ Konfigürasyon dosyası bulunamadı" -ForegroundColor Red
}

# 5. Chrome process kontrolü
Write-Host "`n🌐 5. Chrome Process Kontrolü" -ForegroundColor Yellow
$chromeProcesses = Get-Process chrome -ErrorAction SilentlyContinue
if ($chromeProcesses) {
    Write-Host "✅ Chrome çalışıyor ($($chromeProcesses.Count) process)" -ForegroundColor Green
} else {
    Write-Host "⚠️ Chrome çalışmıyor - test için Chrome'u başlatın" -ForegroundColor Yellow
}

# 6. Port kontrolü
Write-Host "`n🔌 6. Port Kontrolü" -ForegroundColor Yellow
try {
    $portCheck = netstat -an | Select-String ":3000"
    if ($portCheck) {
        Write-Host "✅ Port 3000 kullanımda" -ForegroundColor Green
    } else {
        Write-Host "ℹ️ Port 3000 boş (normal)" -ForegroundColor Cyan
    }
} catch {
    Write-Host "⚠️ Port kontrolü yapılamadı" -ForegroundColor Yellow
}

# 7. Test komutları
Write-Host "`n🧪 7. Test Komutları" -ForegroundColor Yellow
Write-Host "Aşağıdaki komutları test edebilirsiniz:"
Write-Host "npm run browser:start      - BrowserTools MCP'yi başlat"
Write-Host "npm run browser:debug      - Debug modunda başlat"
Write-Host "npm run browser:monitor    - Monitoring modunda başlat"
Write-Host "npm run browser:capture    - Screenshot capture modu"

# 8. Manuel test önerisi
Write-Host "`n🎯 8. Manuel Test" -ForegroundColor Yellow
Write-Host "Manuel test için:"
Write-Host "1. 'npm run browser:start' komutunu çalıştırın"
Write-Host "2. Chrome'da localhost:8090 adresini açın"
Write-Host "3. Extension'ın çalıştığını kontrol edin"
Write-Host "4. Console'da BrowserTools loglarını izleyin"

Write-Host "`n🎉 Test tamamlandı!" -ForegroundColor Green
Write-Host "Sorun yaşarsanız GitHub repo'sundaki dokümantasyonu inceleyin:" -ForegroundColor Cyan
Write-Host "https://github.com/AgentDeskAI/browser-tools-mcp" -ForegroundColor Blue 