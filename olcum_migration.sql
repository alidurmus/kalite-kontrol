-- ============================================================================
-- Ölçüm Verisi Migration SQL
-- Kalite kontrol tablolarına migration tracking sütunları ekler
-- ============================================================================

-- final_kontrol tablosu için migration sütunları
ALTER TABLE final_kontrol 
ADD COLUMN olcum_migrated TINYINT(1) DEFAULT 0 COMMENT 'Ölçüm verisi JSON dosyasına migrate edildi mi?',
ADD COLUMN olcum_file_path VARCHAR(255) NULL COMMENT 'JSON dosya yolu',
ADD COLUMN migration_date DATETIME NULL COMMENT 'Migration tarihi',
ADD COLUMN migration_checksum VARCHAR(32) NULL COMMENT 'Veri bütünlüğü kontrolü için MD5';

-- girdi_kontrol tablosu için migration sütunları  
ALTER TABLE girdi_kontrol
ADD COLUMN olcum_migrated TINYINT(1) DEFAULT 0 COMMENT 'Ölçüm verisi JSON dosyasına migrate edildi mi?',
ADD COLUMN olcum_file_path VARCHAR(255) NULL COMMENT 'JSON dosya yolu',
ADD COLUMN migration_date DATETIME NULL COMMENT 'Migration tarihi',
ADD COLUMN migration_checksum VARCHAR(32) NULL COMMENT 'Veri bütünlüğü kontrolü için MD5';

-- proses_kontrol tablosu için migration sütunları
ALTER TABLE proses_kontrol
ADD COLUMN olcum_migrated TINYINT(1) DEFAULT 0 COMMENT 'Ölçüm verisi JSON dosyasına migrate edildi mi?',
ADD COLUMN olcum_file_path VARCHAR(255) NULL COMMENT 'JSON dosya yolu', 
ADD COLUMN migration_date DATETIME NULL COMMENT 'Migration tarihi',
ADD COLUMN migration_checksum VARCHAR(32) NULL COMMENT 'Veri bütünlüğü kontrolü için MD5';

-- Migration durumu için index'ler
CREATE INDEX idx_final_kontrol_migration ON final_kontrol (olcum_migrated, migration_date);
CREATE INDEX idx_girdi_kontrol_migration ON girdi_kontrol (olcum_migrated, migration_date);
CREATE INDEX idx_proses_kontrol_migration ON proses_kontrol (olcum_migrated, migration_date);

-- Migration istatistikleri view'ı
CREATE OR REPLACE VIEW v_migration_stats AS
SELECT 
    'final_kontrol' as table_name,
    COUNT(*) as total_records,
    SUM(CASE WHEN olcum IS NOT NULL AND olcum != '' THEN 1 ELSE 0 END) as records_with_olcum,
    SUM(CASE WHEN olcum_migrated = 1 THEN 1 ELSE 0 END) as migrated_records,
    ROUND(
        (SUM(CASE WHEN olcum_migrated = 1 THEN 1 ELSE 0 END) * 100.0) / 
        NULLIF(SUM(CASE WHEN olcum IS NOT NULL AND olcum != '' THEN 1 ELSE 0 END), 0), 
        2
    ) as migration_percentage
FROM final_kontrol

UNION ALL

SELECT 
    'girdi_kontrol' as table_name,
    COUNT(*) as total_records,
    SUM(CASE WHEN olcum IS NOT NULL AND olcum != '' THEN 1 ELSE 0 END) as records_with_olcum,
    SUM(CASE WHEN olcum_migrated = 1 THEN 1 ELSE 0 END) as migrated_records,
    ROUND(
        (SUM(CASE WHEN olcum_migrated = 1 THEN 1 ELSE 0 END) * 100.0) / 
        NULLIF(SUM(CASE WHEN olcum IS NOT NULL AND olcum != '' THEN 1 ELSE 0 END), 0), 
        2
    ) as migration_percentage
FROM girdi_kontrol

UNION ALL

SELECT 
    'proses_kontrol' as table_name,
    COUNT(*) as total_records,
    SUM(CASE WHEN olcum IS NOT NULL AND olcum != '' THEN 1 ELSE 0 END) as records_with_olcum,
    SUM(CASE WHEN olcum_migrated = 1 THEN 1 ELSE 0 END) as migrated_records,
    ROUND(
        (SUM(CASE WHEN olcum_migrated = 1 THEN 1 ELSE 0 END) * 100.0) / 
        NULLIF(SUM(CASE WHEN olcum IS NOT NULL AND olcum != '' THEN 1 ELSE 0 END), 0), 
        2
    ) as migration_percentage
FROM proses_kontrol;

-- Migration log tablosu
CREATE TABLE IF NOT EXISTS migration_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(50) NOT NULL,
    operation VARCHAR(20) NOT NULL COMMENT 'BACKUP, MIGRATE, VERIFY, CLEANUP',
    record_count INT DEFAULT 0,
    success_count INT DEFAULT 0,
    error_count INT DEFAULT 0,
    duration_seconds DECIMAL(10,3) DEFAULT 0,
    log_details TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_migration_log_table (table_name, operation, created_at)
) COMMENT 'Migration işlem logları';

-- Migration backup tablosu (güvenlik için)
CREATE TABLE IF NOT EXISTS migration_backup (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(50) NOT NULL,
    record_id INT NOT NULL,
    original_olcum LONGTEXT NOT NULL,
    backup_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    md5_hash VARCHAR(32) NOT NULL,
    INDEX idx_migration_backup (table_name, record_id),
    UNIQUE KEY uk_migration_backup (table_name, record_id)
) COMMENT 'Migration öncesi ölçüm verilerinin güvenli kopyası';

-- ============================================================================
-- KULLANIM TALİMATLARI
-- ============================================================================

/*
1. Bu SQL'i çalıştırarak migration altyapısını hazırlayın
2. Migration controller ile backup oluşturun
3. Tabloları sırayla migrate edin:
   - final_kontrol (31 kayıt)
   - girdi_kontrol (60 kayıt)  
   - proses_kontrol (30 kayıt)
4. Migration doğrulaması yapın
5. Başarılı migration sonrası olcum sütunlarını temizleyin

Migration istatistiklerini görüntülemek için:
SELECT * FROM v_migration_stats;

Migration loglarını görüntülemek için:
SELECT * FROM migration_log ORDER BY created_at DESC LIMIT 10;
*/ 