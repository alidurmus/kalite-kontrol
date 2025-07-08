<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - QMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .migration-card {
            transition: transform 0.2s;
        }
        .migration-card:hover {
            transform: translateY(-2px);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .progress-container {
            display: none;
        }
        .log-container {
            max-height: 300px;
            overflow-y: auto;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-database me-2"></i>
                        <?= $title ?>
                    </h1>
                    <a href="<?= base_url() ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Ana Sayfaya Dön
                    </a>
                </div>
            </div>
        </div>

        <!-- İstatistik Kartları -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <h5>Final Kontrol</h5>
                        <p class="mb-1">
                            <strong><?= $stats['final_kontrol']['toplam_kayit'] ?? 0 ?></strong> Toplam
                        </p>
                        <small>
                            JSON: <?= $stats['final_kontrol']['json_kayit'] ?? 0 ?> | 
                            Dosya: <?= $stats['final_kontrol']['dosya_kayit'] ?? 0 ?>
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <h5>Girdi Kontrol</h5>
                        <p class="mb-1">
                            <strong><?= $stats['girdi_kontrol']['toplam_kayit'] ?? 0 ?></strong> Toplam
                        </p>
                        <small>
                            JSON: <?= $stats['girdi_kontrol']['json_kayit'] ?? 0 ?> | 
                            Dosya: <?= $stats['girdi_kontrol']['dosya_kayit'] ?? 0 ?>
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="fas fa-cogs fa-2x mb-2"></i>
                        <h5>Proses Kontrol</h5>
                        <p class="mb-1">
                            <strong><?= $stats['proses_kontrol']['toplam_kayit'] ?? 0 ?></strong> Toplam
                        </p>
                        <small>
                            JSON: <?= $stats['proses_kontrol']['json_kayit'] ?? 0 ?> | 
                            Dosya: <?= $stats['proses_kontrol']['dosya_kayit'] ?? 0 ?>
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="fas fa-hdd fa-2x mb-2"></i>
                        <h5>Toplam Boyut</h5>
                        <p class="mb-1">
                            <strong>
                                <?= round(($stats['final_kontrol']['json_mb'] ?? 0) + 
                                         ($stats['girdi_kontrol']['json_mb'] ?? 0) + 
                                         ($stats['proses_kontrol']['json_mb'] ?? 0), 2) ?> MB
                            </strong>
                        </p>
                        <small>JSON Veritabanında</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Migration İşlemleri -->
        <div class="row">
            <div class="col-md-6">
                <div class="card migration-card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-play-circle me-2"></i>
                            Migration İşlemleri
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-success btn-lg" onclick="startMigration('test')">
                                <i class="fas fa-vial me-2"></i>
                                Test Migration (5 kayıt)
                            </button>
                            
                            <button class="btn btn-warning btn-lg" onclick="startMigration('final_kontrol')">
                                <i class="fas fa-check-circle me-2"></i>
                                Final Kontrol Migration
                            </button>
                            
                            <button class="btn btn-info btn-lg" onclick="startMigration('girdi_kontrol')">
                                <i class="fas fa-inbox me-2"></i>
                                Girdi Kontrol Migration
                            </button>
                            
                            <button class="btn btn-secondary btn-lg" onclick="startMigration('proses_kontrol')">
                                <i class="fas fa-cogs me-2"></i>
                                Proses Kontrol Migration
                            </button>
                            
                            <hr>
                            
                            <button class="btn btn-danger btn-lg" onclick="startMigration('auto')" id="autoMigrationBtn">
                                <i class="fas fa-magic me-2"></i>
                                Otomatik Tüm Migration
                            </button>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="progress-container mt-3">
                            <div class="progress mb-2">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%" id="migrationProgress">
                                </div>
                            </div>
                            <div class="text-center">
                                <small id="progressText">Hazırlanıyor...</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-terminal me-2"></i>
                            Migration Logları
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="log-container" id="migrationLogs">
                            <p class="text-muted mb-0">Migration işlemi başlatıldığında loglar burada görünecek...</p>
                        </div>
                        
                        <div class="mt-3">
                            <button class="btn btn-outline-secondary btn-sm" onclick="clearLogs()">
                                <i class="fas fa-trash me-1"></i>
                                Logları Temizle
                            </button>
                            <button class="btn btn-outline-info btn-sm" onclick="refreshStats()">
                                <i class="fas fa-sync me-1"></i>
                                İstatistikleri Yenile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dosya Yönetimi -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-folder-open me-2"></i>
                            Dosya Sistemi Yönetimi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-2">
                                    <strong>Dosya Sistemi İstatistikleri:</strong>
                                </p>
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-file text-primary me-2"></i>
                                        Toplam Dosya: <strong><?= $stats['storage']['total_files'] ?? 0 ?></strong>
                                    </li>
                                    <li><i class="fas fa-hdd text-success me-2"></i>
                                        Toplam Boyut: <strong><?= round(($stats['storage']['total_size'] ?? 0) / 1024 / 1024, 2) ?> MB</strong>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 text-end">
                                <button class="btn btn-outline-danger" onclick="cleanupFiles()">
                                    <i class="fas fa-broom me-2"></i>
                                    Eski Dosyaları Temizle (365+ gün)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let migrationInProgress = false;

        function addLog(message, type = 'info') {
            const logContainer = document.getElementById('migrationLogs');
            const timestamp = new Date().toLocaleTimeString();
            const logClass = type === 'error' ? 'text-danger' : type === 'success' ? 'text-success' : 'text-info';
            
            const logEntry = document.createElement('div');
            logEntry.innerHTML = `<span class="text-muted">[${timestamp}]</span> <span class="${logClass}">${message}</span>`;
            
            logContainer.appendChild(logEntry);
            logContainer.scrollTop = logContainer.scrollHeight;
        }

        function clearLogs() {
            document.getElementById('migrationLogs').innerHTML = 
                '<p class="text-muted mb-0">Loglar temizlendi...</p>';
        }

        function updateProgress(percentage, text) {
            const progressBar = document.getElementById('migrationProgress');
            const progressText = document.getElementById('progressText');
            
            progressBar.style.width = percentage + '%';
            progressText.textContent = text;
            
            if (percentage > 0) {
                document.querySelector('.progress-container').style.display = 'block';
            }
        }

        function startMigration(type) {
            if (migrationInProgress) {
                alert('Bir migration işlemi zaten devam ediyor!');
                return;
            }

            migrationInProgress = true;
            const buttons = document.querySelectorAll('button');
            buttons.forEach(btn => btn.disabled = true);

            addLog(`${type.toUpperCase()} migration başlatıldı...`, 'info');
            updateProgress(10, 'Migration başlatılıyor...');

            let url;
            switch(type) {
                case 'test':
                    url = '<?= base_url("migration/test_migration") ?>';
                    break;
                case 'final_kontrol':
                    url = '<?= base_url("migration/migrate_final_kontrol") ?>';
                    break;
                case 'girdi_kontrol':
                    url = '<?= base_url("migration/migrate_girdi_kontrol") ?>';
                    break;
                case 'proses_kontrol':
                    url = '<?= base_url("migration/migrate_proses_kontrol") ?>';
                    break;
                case 'auto':
                    url = '<?= base_url("migration/auto_migrate") ?>';
                    break;
                default:
                    addLog('Geçersiz migration tipi!', 'error');
                    migrationInProgress = false;
                    buttons.forEach(btn => btn.disabled = false);
                    return;
            }

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateProgress(100, 'Migration tamamlandı!');
                
                if (data.success) {
                    addLog(data.message, 'success');
                    
                    if (data.stats) {
                        addLog(`İşlenen: ${data.stats.processed}, Migration: ${data.stats.migrated}, Hata: ${data.stats.errors}`, 'info');
                    }
                    
                    if (data.execution_time) {
                        addLog(`Çalışma süresi: ${data.execution_time}`, 'info');
                    }
                    
                    // İstatistikleri yenile
                    setTimeout(refreshStats, 1000);
                } else {
                    addLog('Migration başarısız: ' + (data.message || 'Bilinmeyen hata'), 'error');
                }
            })
            .catch(error => {
                addLog('Migration hatası: ' + error.message, 'error');
                updateProgress(0, 'Hata oluştu!');
            })
            .finally(() => {
                migrationInProgress = false;
                buttons.forEach(btn => btn.disabled = false);
                
                // Progress bar'ı 3 saniye sonra gizle
                setTimeout(() => {
                    document.querySelector('.progress-container').style.display = 'none';
                }, 3000);
            });
        }

        function refreshStats() {
            addLog('İstatistikler yenileniyor...', 'info');
            location.reload();
        }

        function cleanupFiles() {
            if (!confirm('365 günden eski dosyalar silinecek. Emin misiniz?')) {
                return;
            }

            addLog('Dosya temizleme başlatıldı...', 'info');

            fetch('<?= base_url("migration/cleanup_files") ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addLog(data.message, 'success');
                } else {
                    addLog('Temizleme başarısız: ' + data.message, 'error');
                }
            })
            .catch(error => {
                addLog('Temizleme hatası: ' + error.message, 'error');
            });
        }

        // Sayfa yüklendiğinde ilk log
        document.addEventListener('DOMContentLoaded', function() {
            addLog('Migration paneli hazır. Bir işlem seçin.', 'info');
        });
    </script>
</body>
</html> 