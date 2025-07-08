-- ============================================================================
-- Kalite Yönetim Sistemi - Ölçüm Verisi Optimizasyonu Migration
-- Tarih: 2025-01-07
-- Amaç: JSON ölçüm verilerini dosya sistemine taşıyarak veritabanı boyutunu azalt
-- ============================================================================

-- Backup öncesi kontrol
SELECT 
    COUNT(*) as toplam_kayit,
    AVG(LENGTH(olcum)) as ortalama_json_boyut,
    SUM(LENGTH(olcum)) / 1024 / 1024 as toplam_mb
FROM final_kontrol 
WHERE olcum IS NOT NULL AND olcum != '';

-- 1. Final Kontrol tablosuna yeni alanlar ekle
ALTER TABLE `final_kontrol` 
ADD COLUMN `measurement_file` VARCHAR(255) NULL COMMENT 'Ölçüm dosyası referansı',
ADD COLUMN `measurement_checksum` VARCHAR(32) NULL COMMENT 'Ölçüm verisi doğrulama hash';

-- 2. Girdi Kontrol tablosuna yeni alanlar ekle
ALTER TABLE `girdi_kontrol` 
ADD COLUMN `measurement_file` VARCHAR(255) NULL COMMENT 'Ölçüm dosyası referansı',
ADD COLUMN `measurement_checksum` VARCHAR(32) NULL COMMENT 'Ölçüm verisi doğrulama hash';

-- 3. Proses Kontrol tablosuna yeni alanlar ekle
ALTER TABLE `proses_kontrol` 
ADD COLUMN `measurement_file` VARCHAR(255) NULL COMMENT 'Ölçüm dosyası referansı',
ADD COLUMN `measurement_checksum` VARCHAR(32) NULL COMMENT 'Ölçüm verisi doğrulama hash';

-- 4. İndeksler ekle (performans için)
CREATE INDEX `idx_final_kontrol_measurement_file` ON `final_kontrol` (`measurement_file`);
CREATE INDEX `idx_girdi_kontrol_measurement_file` ON `girdi_kontrol` (`measurement_file`);
CREATE INDEX `idx_proses_kontrol_measurement_file` ON `proses_kontrol` (`measurement_file`);

-- 5. Migration tamamlandıktan sonra eski olcum alanlarını kaldırmak için (YEDEK ALINMADAN ÇALIŞTIRMAYIN!)
-- ALTER TABLE `final_kontrol` DROP COLUMN `olcum`;
-- ALTER TABLE `girdi_kontrol` DROP COLUMN `olcum`;
-- ALTER TABLE `proses_kontrol` DROP COLUMN `olcum`;

-- 6. Migration sonrası kontrol sorguları
-- SELECT 
--     COUNT(*) as toplam_kayit,
--     COUNT(measurement_file) as dosya_ile_kayit,
--     COUNT(olcum) as json_ile_kayit
-- FROM final_kontrol;

COMMIT; 