-- ============================================================================
-- QMS Performance Optimization - Critical Database Indexes
-- Date: 2025-01-08
-- Purpose: Fix performance bottlenecks in quality management system
-- ============================================================================

-- CRITICAL PERFORMANCE INDEXES
-- ============================================================================

-- Final Kontrol Optimization (HIGHEST PRIORITY)
-- ============================================================================
CREATE INDEX idx_final_kontrol_tarih ON final_kontrol(tarih);
CREATE INDEX idx_final_kontrol_urun ON final_kontrol(urun);
CREATE INDEX idx_final_kontrol_kullanici ON final_kontrol(kullanici);
CREATE INDEX idx_final_kontrol_sonuc ON final_kontrol(sonuc);
CREATE INDEX idx_final_kontrol_lot ON final_kontrol(lot);
CREATE INDEX idx_final_kontrol_kontrol_no ON final_kontrol(kontrol_no);

-- Composite indexes for JOIN optimization
CREATE INDEX idx_final_kontrol_urun_tarih ON final_kontrol(urun, tarih);
CREATE INDEX idx_final_kontrol_tarih_sonuc ON final_kontrol(tarih, sonuc);

-- Girdi Kontrol Optimization
-- ============================================================================
CREATE INDEX idx_girdi_kontrol_tarih ON girdi_kontrol(tarih);
CREATE INDEX idx_girdi_kontrol_urun ON girdi_kontrol(urun);
CREATE INDEX idx_girdi_kontrol_kullanici ON girdi_kontrol(kullanici);
CREATE INDEX idx_girdi_kontrol_sonuc ON girdi_kontrol(sonuc);
CREATE INDEX idx_girdi_kontrol_tedarikci ON girdi_kontrol(tedarikci);

-- Proses Kontrol Optimization
-- ============================================================================
CREATE INDEX idx_proses_kontrol_tarih ON proses_kontrol(tarih);
CREATE INDEX idx_proses_kontrol_urun ON proses_kontrol(urun);
CREATE INDEX idx_proses_kontrol_kullanici ON proses_kontrol(kullanici);
CREATE INDEX idx_proses_kontrol_sonuc ON proses_kontrol(sonuc);
CREATE INDEX idx_proses_kontrol_process_isim ON proses_kontrol(process_isim);

-- Supporting Tables
-- ============================================================================
CREATE INDEX idx_users_user_role_id ON users(user_role_id);
CREATE INDEX idx_users_aktif ON users(aktif);
CREATE INDEX idx_urunler_aktif ON urunler(aktif);

-- Performance Analysis Query
-- ============================================================================
SELECT 
    table_name,
    table_rows,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
FROM information_schema.TABLES 
WHERE table_schema = DATABASE()
AND table_name IN ('final_kontrol', 'girdi_kontrol', 'proses_kontrol')
ORDER BY size_mb DESC; 