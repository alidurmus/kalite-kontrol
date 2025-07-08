-- ============================================================================
-- QMS Performance Optimization - Database Indexes and Query Optimization
-- Date: 2025-01-08
-- Purpose: Comprehensive performance optimization for quality management system
-- ============================================================================

-- Performance Analysis - Before Optimization
-- ============================================================================

-- 1. Current Table Sizes and Performance Metrics
SELECT 
    table_name,
    table_rows,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb,
    ROUND((data_length / 1024 / 1024), 2) AS data_mb,
    ROUND((index_length / 1024 / 1024), 2) AS index_mb,
    ROUND((index_length / data_length) * 100, 2) AS index_ratio
FROM information_schema.TABLES 
WHERE table_schema = DATABASE()
AND table_name IN ('final_kontrol', 'girdi_kontrol', 'proses_kontrol', 'urunler', 'users', 'sonuc_secim', 'tedarikciler_table')
ORDER BY size_mb DESC;

-- 2. Slow Query Analysis
-- Enable slow query log first:
-- SET GLOBAL slow_query_log = 'ON';
-- SET GLOBAL long_query_time = 1;

-- ============================================================================
-- CRITICAL PERFORMANCE INDEXES
-- ============================================================================

-- Final Kontrol Optimization
-- ============================================================================

-- Primary performance indexes for final_kontrol
CREATE INDEX idx_final_kontrol_tarih ON final_kontrol(tarih);
CREATE INDEX idx_final_kontrol_urun ON final_kontrol(urun);
CREATE INDEX idx_final_kontrol_kullanici ON final_kontrol(kullanici);
CREATE INDEX idx_final_kontrol_sonuc ON final_kontrol(sonuc);
CREATE INDEX idx_final_kontrol_lot ON final_kontrol(lot);
CREATE INDEX idx_final_kontrol_kontrol_no ON final_kontrol(kontrol_no);

-- Composite indexes for complex queries
CREATE INDEX idx_final_kontrol_urun_tarih ON final_kontrol(urun, tarih);
CREATE INDEX idx_final_kontrol_tarih_sonuc ON final_kontrol(tarih, sonuc);
CREATE INDEX idx_final_kontrol_kullanici_tarih ON final_kontrol(kullanici, tarih);

-- Full-text search optimization
CREATE FULLTEXT INDEX idx_final_kontrol_search ON final_kontrol(lot, kontrol_no, aciklama);

-- Girdi Kontrol Optimization
-- ============================================================================

CREATE INDEX idx_girdi_kontrol_tarih ON girdi_kontrol(tarih);
CREATE INDEX idx_girdi_kontrol_urun ON girdi_kontrol(urun);
CREATE INDEX idx_girdi_kontrol_kullanici ON girdi_kontrol(kullanici);
CREATE INDEX idx_girdi_kontrol_sonuc ON girdi_kontrol(sonuc);
CREATE INDEX idx_girdi_kontrol_tedarikci ON girdi_kontrol(tedarikci);
CREATE INDEX idx_girdi_kontrol_lot ON girdi_kontrol(lot);

-- Composite indexes
CREATE INDEX idx_girdi_kontrol_tedarikci_tarih ON girdi_kontrol(tedarikci, tarih);
CREATE INDEX idx_girdi_kontrol_urun_sonuc ON girdi_kontrol(urun, sonuc);

-- Full-text search
CREATE FULLTEXT INDEX idx_girdi_kontrol_search ON girdi_kontrol(lot, aciklama);

-- Proses Kontrol Optimization
-- ============================================================================

CREATE INDEX idx_proses_kontrol_tarih ON proses_kontrol(tarih);
CREATE INDEX idx_proses_kontrol_urun ON proses_kontrol(urun);
CREATE INDEX idx_proses_kontrol_kullanici ON proses_kontrol(kullanici);
CREATE INDEX idx_proses_kontrol_sonuc ON proses_kontrol(sonuc);
CREATE INDEX idx_proses_kontrol_process_isim ON proses_kontrol(process_isim);

-- Composite indexes
CREATE INDEX idx_proses_kontrol_process_tarih ON proses_kontrol(process_isim, tarih);
CREATE INDEX idx_proses_kontrol_urun_tarih ON proses_kontrol(urun, tarih);

-- Supporting Tables Optimization
-- ============================================================================

-- Users table
CREATE INDEX idx_users_user_role_id ON users(user_role_id);
CREATE INDEX idx_users_aktif ON users(aktif);
CREATE INDEX idx_users_user_name ON users(user_name);

-- Urunler table
CREATE INDEX idx_urunler_aktif ON urunler(aktif);
CREATE FULLTEXT INDEX idx_urunler_search ON urunler(adi, aciklama);

-- Tedarikciler optimization
CREATE INDEX idx_tedarikciler_aktif ON tedarikciler_table(aktif);
CREATE INDEX idx_tedarikciler_onay_durumu ON tedarikciler_table(onay_durumu);
CREATE INDEX idx_tedarikciler_sehir ON tedarikciler_table(sehir);
CREATE INDEX idx_tedarikciler_composite ON tedarikciler_table(aktif, onay_durumu);
CREATE FULLTEXT INDEX idx_tedarikciler_search ON tedarikciler_table(firma_adi, iletisim_kisi);

-- ============================================================================
-- QUERY OPTIMIZATION VIEWS
-- ============================================================================

-- Optimized view for dashboard statistics
CREATE OR REPLACE VIEW v_dashboard_stats AS
SELECT 
    (SELECT COUNT(*) FROM final_kontrol WHERE DATE(tarih) = CURDATE()) as bugun_final,
    (SELECT COUNT(*) FROM girdi_kontrol WHERE DATE(tarih) = CURDATE()) as bugun_girdi,
    (SELECT COUNT(*) FROM proses_kontrol WHERE DATE(tarih) = CURDATE()) as bugun_proses,
    (SELECT COUNT(*) FROM final_kontrol WHERE WEEK(tarih) = WEEK(NOW())) as hafta_final,
    (SELECT COUNT(*) FROM girdi_kontrol WHERE WEEK(tarih) = WEEK(NOW())) as hafta_girdi,
    (SELECT COUNT(*) FROM proses_kontrol WHERE WEEK(tarih) = WEEK(NOW())) as hafta_proses,
    (SELECT COUNT(*) FROM final_kontrol) as toplam_final,
    (SELECT COUNT(*) FROM girdi_kontrol) as toplam_girdi,
    (SELECT COUNT(*) FROM proses_kontrol) as toplam_proses;

-- Optimized view for recent activities
CREATE OR REPLACE VIEW v_recent_activities AS
SELECT 
    'final' as tip,
    fk.id,
    fk.tarih,
    ur.adi as urun_adi,
    us.user_name as kullanici_adi,
    son.adi as sonuc_adi,
    fk.lot,
    fk.kontrol_no
FROM final_kontrol fk
INNER JOIN urunler ur ON ur.id = fk.urun
INNER JOIN users us ON us.id = fk.kullanici  
INNER JOIN sonuc_secim son ON son.id = fk.sonuc
WHERE fk.tarih >= DATE_SUB(NOW(), INTERVAL 7 DAY)

UNION ALL

SELECT 
    'girdi' as tip,
    gk.id,
    gk.tarih,
    ur.adi as urun_adi,
    us.user_name as kullanici_adi,
    son.adi as sonuc_adi,
    gk.lot,
    '' as kontrol_no
FROM girdi_kontrol gk
INNER JOIN urunler ur ON ur.id = gk.urun
INNER JOIN users us ON us.id = gk.kullanici
INNER JOIN sonuc_secim son ON son.id = gk.sonuc  
WHERE gk.tarih >= DATE_SUB(NOW(), INTERVAL 7 DAY)

UNION ALL

SELECT 
    'proses' as tip,
    pk.id,
    pk.tarih,
    ur.adi as urun_adi,
    us.user_name as kullanici_adi,
    son.adi as sonuc_adi,
    '',
    pk.process_isim as kontrol_no
FROM proses_kontrol pk
INNER JOIN urunler ur ON ur.id = pk.urun
INNER JOIN users us ON us.id = pk.kullanici
INNER JOIN sonuc_secim son ON son.id = pk.sonuc
WHERE pk.tarih >= DATE_SUB(NOW(), INTERVAL 7 DAY)

ORDER BY tarih DESC
LIMIT 100;

-- ============================================================================
-- PERFORMANCE MONITORING SETUP
-- ============================================================================

-- Performance monitoring table
CREATE TABLE IF NOT EXISTS performance_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    query_type VARCHAR(50) NOT NULL,
    execution_time DECIMAL(10,4) NOT NULL,
    query_hash VARCHAR(32) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_perf_log_type (query_type),
    INDEX idx_perf_log_time (execution_time),
    INDEX idx_perf_log_created (created_at)
);

-- Query cache optimization
SET GLOBAL query_cache_size = 64 * 1024 * 1024; -- 64MB
SET GLOBAL query_cache_type = ON;

-- ============================================================================
-- STORED PROCEDURES FOR OPTIMIZED OPERATIONS
-- ============================================================================

DELIMITER //

-- Optimized dashboard statistics procedure
CREATE PROCEDURE sp_get_dashboard_stats()
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    SELECT * FROM v_dashboard_stats;
END //

-- Optimized pagination procedure for final_kontrol
CREATE PROCEDURE sp_get_final_kontrol_paginated(
    IN p_limit INT,
    IN p_offset INT,
    IN p_search VARCHAR(255)
)
BEGIN
    DECLARE v_search_condition VARCHAR(500) DEFAULT '';
    
    IF p_search IS NOT NULL AND p_search != '' THEN
        SET v_search_condition = CONCAT(
            ' AND (ur.adi LIKE "%', p_search, '%" OR fk.kontrol_no LIKE "%', p_search, '%")'
        );
    END IF;
    
    SET @sql = CONCAT(
        'SELECT fk.id, fk.urun, fk.lot, fk.kontrol_no, fk.kutu_no, fk.tarih,
                ur.adi as urun_adi, 
                us.user_name as kullanici_adi,
                son.adi as sonuc_adi
         FROM final_kontrol fk
         INNER JOIN urunler ur ON ur.id = fk.urun
         INNER JOIN users us ON us.id = fk.kullanici
         INNER JOIN sonuc_secim son ON son.id = fk.sonuc
         WHERE 1=1 ',
        v_search_condition,
        ' ORDER BY fk.id DESC
         LIMIT ', p_limit, ' OFFSET ', p_offset
    );
    
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //

-- Performance analysis procedure
CREATE PROCEDURE sp_analyze_performance()
BEGIN
    -- Table sizes
    SELECT 'Table Sizes' as analysis_type;
    SELECT 
        table_name,
        table_rows,
        ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
    FROM information_schema.TABLES 
    WHERE table_schema = DATABASE()
    ORDER BY size_mb DESC;
    
    -- Index usage
    SELECT 'Index Usage' as analysis_type;
    SELECT 
        TABLE_NAME,
        INDEX_NAME,
        CARDINALITY,
        SUB_PART,
        NULLABLE
    FROM information_schema.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME IN ('final_kontrol', 'girdi_kontrol', 'proses_kontrol')
    ORDER BY TABLE_NAME, CARDINALITY DESC;
    
    -- Recent performance metrics
    SELECT 'Performance Metrics' as analysis_type;
    SELECT 
        query_type,
        COUNT(*) as query_count,
        AVG(execution_time) as avg_time,
        MAX(execution_time) as max_time,
        MIN(execution_time) as min_time
    FROM performance_log 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    GROUP BY query_type
    ORDER BY avg_time DESC;
END //

DELIMITER ;

-- ============================================================================
-- OPTIMIZATION VERIFICATION QUERIES
-- ============================================================================

-- Test query performance before/after optimization
-- Run these queries to measure improvement:

-- 1. Dashboard statistics (should be < 50ms)
SELECT BENCHMARK(1000, (
    SELECT COUNT(*) FROM final_kontrol WHERE DATE(tarih) = CURDATE()
));

-- 2. Paginated query performance (should be < 100ms)
SELECT BENCHMARK(100, (
    SELECT fk.id, ur.adi, us.user_name 
    FROM final_kontrol fk
    INNER JOIN urunler ur ON ur.id = fk.urun
    INNER JOIN users us ON us.id = fk.kullanici
    ORDER BY fk.id DESC 
    LIMIT 50
));

-- 3. Search query performance (should be < 200ms)
SELECT BENCHMARK(50, (
    SELECT COUNT(*) 
    FROM final_kontrol fk
    INNER JOIN urunler ur ON ur.id = fk.urun
    WHERE ur.adi LIKE '%test%' OR fk.kontrol_no LIKE '%test%'
));

-- ============================================================================
-- MAINTENANCE PROCEDURES
-- ============================================================================

-- Weekly maintenance procedure
CREATE EVENT IF NOT EXISTS weekly_maintenance
ON SCHEDULE EVERY 1 WEEK
STARTS CURRENT_TIMESTAMP
DO
BEGIN
    -- Optimize tables
    OPTIMIZE TABLE final_kontrol, girdi_kontrol, proses_kontrol;
    
    -- Clean old performance logs (keep 30 days)
    DELETE FROM performance_log WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
    
    -- Update table statistics
    ANALYZE TABLE final_kontrol, girdi_kontrol, proses_kontrol;
END;

-- ============================================================================
-- PERFORMANCE TARGETS
-- ============================================================================

/*
Performance Targets After Optimization:

1. Dashboard Statistics: < 50ms
2. Paginated Queries: < 100ms  
3. Search Queries: < 200ms
4. Large Dataset Exports: < 2s for 1000 records
5. Database Size Reduction: 30-50% through measurement file migration
6. Query Cache Hit Ratio: > 80%
7. Index Usage: > 90% of queries should use indexes

Monitoring Commands:
- SHOW PROCESSLIST; (check for slow queries)
- SHOW STATUS LIKE 'Qcache%'; (query cache stats)
- EXPLAIN your_query; (query execution plan)
- CALL sp_analyze_performance(); (custom performance analysis)
*/ 