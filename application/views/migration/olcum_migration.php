<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .migration-card {
            border-left: 4px solid #007bff;
            transition: all 0.3s ease;
        }
        .migration-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.8em;
            padding: 0.25rem 0.5rem;
        }
        .log-container {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            max-height: 400px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
        }
        .progress-container {
            position: relative;
            margin: 1rem 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .table-status {
            padding: 1rem;
            border-radius: 0.5rem;
            background: white;
            border: 1px solid #dee2e6;
            text-align: center;
        }
        .table-status.completed {
            border-color: #28a745;
            background: #f8fff9;
        }
        .table-status.pending {
            border-color: #ffc107;
            background: #fffdf0;
        }
        .btn-migration {
            margin: 0.25rem;
            min-width: 120px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-database text-primary me-2"></i>
                        Ölçüm Verisi Migration
                    </h1>
                    <p class="text-muted mb-0">Veritabanı → JSON Dosya Sistemi Geçişi</p>
                </div>
                <div>
                    <a href="<?php echo base_url('anasayfa'); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Ana Sayfa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Migration Durumu -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Migration Durumu
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <?php foreach(['final_kontrol', 'girdi_kontrol', 'proses_kontrol'] as $table): ?>
                        <div class="table-status <?php echo $migration_status[$table] ? 'completed' : 'pending'; ?>">
                            <h6 class="text-capitalize"><?php echo str_replace('_', ' ', $table); ?></h6>
                            <div class="mb-2">
                                <span class="badge <?php echo $migration_status[$table] ? 'bg-success' : 'bg-warning'; ?>">
                                    <?php echo $migration_status[$table] ? 'Tamamlandı' : 'Bekliyor'; ?>
                                </span>
                            </div>
                            <div class="small">
                                <strong>Toplam:</strong> <?php echo $current_stats[$table]->total; ?><br>
                                <strong>Ölçüm Verili:</strong> <?php echo $current_stats[$table]->with_olcum; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Migration İşlemleri -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card migration-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Migration İşlemleri
                    </h5>
                </div>
                <div class="card-body">
                    
                    <!-- Adım 1: Backup -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">
                            <span class="badge bg-primary me-2">1</span>
                            Backup Oluştur
                        </h6>
                        <p class="text-muted small">
                            Migration öncesi mevcut ölçüm verilerinin güvenli kopyasını oluşturun.
                        </p>
                        <button type="button" class="btn btn-warning btn-migration" id="btnCreateBackup">
                            <i class="fas fa-download me-1"></i> Backup Oluştur
                        </button>
                        <div id="backupStatus" class="mt-2"></div>
                    </div>

                    <!-- Adım 2: Migration -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">
                            <span class="badge bg-primary me-2">2</span>
                            Veri Migration
                        </h6>
                        <p class="text-muted small">
                            Seçilen tablonun ölçüm verilerini JSON dosyalarına aktarın.
                        </p>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tablo Seçin:</label>
                                <select class="form-select" id="migrationTable">
                                    <option value="">Tablo seçin...</option>
                                    <option value="final_kontrol">Final Kontrol</option>
                                    <option value="girdi_kontrol">Girdi Kontrol</option>
                                    <option value="proses_kontrol">Proses Kontrol</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Batch Size:</label>
                                <select class="form-select" id="batchSize">
                                    <option value="25">25 kayıt</option>
                                    <option value="50" selected>50 kayıt</option>
                                    <option value="100">100 kayıt</option>
                                    <option value="200">200 kayıt</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-success btn-migration" id="btnStartMigration" disabled>
                            <i class="fas fa-play me-1"></i> Migration Başlat
                        </button>
                        
                        <div class="progress-container" id="migrationProgress" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%"></div>
                            </div>
                            <div class="mt-2 small text-center" id="migrationProgressText"></div>
                        </div>
                    </div>

                    <!-- Adım 3: Doğrulama -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">
                            <span class="badge bg-primary me-2">3</span>
                            Migration Doğrulama
                        </h6>
                        <p class="text-muted small">
                            Migration işleminin doğruluğunu kontrol edin.
                        </p>
                        <button type="button" class="btn btn-info btn-migration" id="btnVerifyMigration" disabled>
                            <i class="fas fa-check-circle me-1"></i> Doğrula
                        </button>
                        <div id="verificationResults" class="mt-2"></div>
                    </div>

                    <!-- Adım 4: Temizlik -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">
                            <span class="badge bg-primary me-2">4</span>
                            Veritabanı Temizliği
                        </h6>
                        <p class="text-muted small">
                            Migration tamamlandıktan sonra eski ölçüm verilerini temizleyin.
                        </p>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="forceCleanup">
                            <label class="form-check-label" for="forceCleanup">
                                Doğrulama olmadan zorla temizle (Dikkatli kullanın!)
                            </label>
                        </div>
                        <button type="button" class="btn btn-danger btn-migration" id="btnCleanup" disabled>
                            <i class="fas fa-trash me-1"></i> Temizle
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Log ve İstatistikler -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list-alt me-2"></i>
                        İşlem Logları
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="log-container p-3" id="migrationLogs">
                        <div class="text-muted">İşlem logları burada görünecek...</div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnClearLogs">
                        <i class="fas fa-eraser me-1"></i> Logları Temizle
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnRefreshStats">
                        <i class="fas fa-sync me-1"></i> İstatistikleri Yenile
                    </button>
                </div>
            </div>

            <!-- Hızlı İstatistikler -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Hızlı İstatistikler
                    </h6>
                </div>
                <div class="card-body" id="quickStats">
                    <div class="text-center text-muted">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                        <div class="mt-2">Yükleniyor...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    
    // Sayfa yüklendiğinde istatistikleri getir
    refreshStats();
    
    // Tablo seçimi değiştiğinde butonları aktifleştir
    $('#migrationTable').change(function() {
        const selected = $(this).val();
        $('#btnStartMigration, #btnVerifyMigration, #btnCleanup').prop('disabled', !selected);
    });
    
    // Backup oluştur
    $('#btnCreateBackup').click(function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Backup Oluşturuluyor...');
        
        $.ajax({
            url: '<?php echo base_url("olcummigration/create_backup"); ?>',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#backupStatus').html(`
                        <div class="alert alert-success">
                            <i class="fas fa-check me-1"></i> ${response.message}
                            <br><small>Backup Dizini: ${response.backup_dir}</small>
                        </div>
                    `);
                    updateLogs(response.log);
                } else {
                    $('#backupStatus').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-1"></i> ${response.message}
                        </div>
                    `);
                }
            },
            error: function() {
                $('#backupStatus').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i> Backup oluşturulurken hata oluştu
                    </div>
                `);
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-download me-1"></i> Backup Oluştur');
            }
        });
    });
    
    // Migration başlat
    $('#btnStartMigration').click(function() {
        const table = $('#migrationTable').val();
        const batchSize = $('#batchSize').val();
        
        if (!table) {
            alert('Lütfen bir tablo seçin');
            return;
        }
        
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Migration Çalışıyor...');
        
        $('#migrationProgress').show();
        $('.progress-bar').css('width', '0%');
        $('#migrationProgressText').text('Migration başlatılıyor...');
        
        $.ajax({
            url: '<?php echo base_url("olcummigration/start_migration"); ?>',
            type: 'POST',
            data: {
                table: table,
                batch_size: batchSize
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('.progress-bar').css('width', '100%').removeClass('progress-bar-animated');
                    $('#migrationProgressText').html(`
                        <strong>Tamamlandı!</strong><br>
                        Migrate: ${response.migrated_count} | 
                        Hata: ${response.error_count}
                    `);
                    
                    setTimeout(() => {
                        $('#migrationProgress').hide();
                        refreshStats();
                    }, 3000);
                } else {
                    $('#migrationProgressText').html(`<span class="text-danger">Hata: ${response.message}</span>`);
                }
                updateLogs(response.log);
            },
            error: function() {
                $('#migrationProgressText').html('<span class="text-danger">Migration sırasında hata oluştu</span>');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-play me-1"></i> Migration Başlat');
            }
        });
    });
    
    // Migration doğrula
    $('#btnVerifyMigration').click(function() {
        const table = $('#migrationTable').val();
        
        if (!table) {
            alert('Lütfen bir tablo seçin');
            return;
        }
        
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Doğrulanıyor...');
        
        $.ajax({
            url: '<?php echo base_url("olcummigration/verify_migration"); ?>',
            type: 'POST',
            data: { table: table },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const v = response.verification;
                    $('#verificationResults').html(`
                        <div class="alert alert-info">
                            <strong>Doğrulama Sonucu:</strong><br>
                            <small>
                                Toplam: ${v.total_records} | 
                                Migrate: ${v.migrated_records} | 
                                Doğrulanan: ${v.verified_records} | 
                                Hata: ${v.integrity_errors} | 
                                Eksik Dosya: ${v.missing_files}
                            </small>
                        </div>
                    `);
                } else {
                    $('#verificationResults').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-1"></i> ${response.message}
                        </div>
                    `);
                }
                updateLogs(response.log);
            },
            error: function() {
                $('#verificationResults').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i> Doğrulama sırasında hata oluştu
                    </div>
                `);
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> Doğrula');
            }
        });
    });
    
    // Temizlik işlemi
    $('#btnCleanup').click(function() {
        const table = $('#migrationTable').val();
        const force = $('#forceCleanup').is(':checked');
        
        if (!table) {
            alert('Lütfen bir tablo seçin');
            return;
        }
        
        if (!force && !confirm('Bu işlem geri alınamaz. Devam etmek istediğinizden emin misiniz?')) {
            return;
        }
        
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Temizleniyor...');
        
        $.ajax({
            url: '<?php echo base_url("olcummigration/cleanup_olcum_columns"); ?>',
            type: 'POST',
            data: {
                table: table,
                force: force
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(`Başarılı! ${response.affected_rows} kayıt temizlendi.`);
                    refreshStats();
                } else {
                    alert('Hata: ' + response.message);
                }
                updateLogs(response.log);
            },
            error: function() {
                alert('Temizlik sırasında hata oluştu');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Temizle');
            }
        });
    });
    
    // Logları temizle
    $('#btnClearLogs').click(function() {
        $('#migrationLogs').html('<div class="text-muted">İşlem logları burada görünecek...</div>');
    });
    
    // İstatistikleri yenile
    $('#btnRefreshStats').click(function() {
        refreshStats();
    });
    
    // İstatistikleri getir
    function refreshStats() {
        $.ajax({
            url: '<?php echo base_url("olcummigration/get_stats"); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let html = '';
                
                // Veritabanı istatistikleri
                html += '<h6 class="border-bottom pb-1">Veritabanı</h6>';
                for (const [table, stats] of Object.entries(response.database)) {
                    html += `
                        <div class="d-flex justify-content-between mb-1">
                            <small>${table.replace('_', ' ')}</small>
                            <small><strong>${stats.with_olcum}/${stats.total}</strong></small>
                        </div>
                    `;
                }
                
                // Dosya sistemi istatistikleri
                if (response.files) {
                    html += '<h6 class="border-bottom pb-1 mt-3">Dosya Sistemi</h6>';
                    html += `
                        <div class="d-flex justify-content-between mb-1">
                            <small>Toplam Dosya</small>
                            <small><strong>${response.files.total_files || 0}</strong></small>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <small>Toplam Boyut</small>
                            <small><strong>${formatBytes(response.files.total_size || 0)}</strong></small>
                        </div>
                    `;
                }
                
                $('#quickStats').html(html);
            },
            error: function() {
                $('#quickStats').html('<div class="text-danger">İstatistikler yüklenemedi</div>');
            }
        });
    }
    
    // Logları güncelle
    function updateLogs(logs) {
        if (!logs || !Array.isArray(logs)) return;
        
        let html = '';
        logs.forEach(function(log) {
            html += `<div class="mb-1"><span class="text-muted">[${log.timestamp}]</span> ${log.message}</div>`;
        });
        
        $('#migrationLogs').html(html);
        $('#migrationLogs').scrollTop($('#migrationLogs')[0].scrollHeight);
    }
    
    // Byte formatı
    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
});
</script>

</body>
</html> 