<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ölçüm Verisi Migration Controller
 * 
 * Kalite kontrol modüllerindeki (final_kontrol, girdi_kontrol, proses_kontrol)
 * olcum alanlarını veritabanından JSON dosya yapısına geçiş yapar.
 * 
 * @author QMS Team
 * @version 2.0
 */
class OlcumMigration extends CI_Controller {

    private $measurement_service;
    private $migration_log = [];
    private $backup_dir;
    
    public function __construct() {
        parent::__construct();
        
        // Gerekli kütüphaneleri yükle
        $this->load->library('session');
        $this->load->library('MeasurementDataService');
        $this->load->database();
        $this->load->helper(['file', 'url', 'security']);
        
        // Sadmin erişimi (session kontrolü basit tut)
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            // Admin login olmadan da çalışabilir (development için)
            // redirect('signin');
        }
        
        $this->measurement_service = $this->measurementdataservice;
        $this->backup_dir = APPPATH . '../uploads/migration_backup/' . date('Y-m-d_H-i-s') . '/';
        
        // Backup dizinini oluştur
        if (!is_dir($this->backup_dir)) {
            mkdir($this->backup_dir, 0755, true);
        }
    }
    
    /**
     * Ana migration sayfası
     */
    public function index() {
        $data = [
            'title' => 'Ölçüm Verisi Migration - JSON Dosya Geçişi',
            'current_stats' => $this->get_current_stats(),
            'migration_status' => $this->get_migration_status()
        ];
        
        $this->load->view('migration/olcum_migration', $data);
    }
    
    /**
     * Mevcut durum istatistikleri
     */
    private function get_current_stats() {
        $stats = [];
        
        // Final Kontrol istatistikleri
        $this->db->select('COUNT(*) as total, SUM(CASE WHEN olcum IS NOT NULL AND olcum != "" THEN 1 ELSE 0 END) as with_olcum');
        $this->db->from('final_kontrol');
        $stats['final_kontrol'] = $this->db->get()->row();
        
        // Girdi Kontrol istatistikleri
        $this->db->select('COUNT(*) as total, SUM(CASE WHEN olcum IS NOT NULL AND olcum != "" THEN 1 ELSE 0 END) as with_olcum');
        $this->db->from('girdi_kontrol');
        $stats['girdi_kontrol'] = $this->db->get()->row();
        
        // Proses Kontrol istatistikleri
        $this->db->select('COUNT(*) as total, SUM(CASE WHEN olcum IS NOT NULL AND olcum != "" THEN 1 ELSE 0 END) as with_olcum');
        $this->db->from('proses_kontrol');
        $stats['proses_kontrol'] = $this->db->get()->row();
        
        return $stats;
    }
    
    /**
     * Migration durumu kontrol et
     */
    private function get_migration_status() {
        $status = [
            'final_kontrol' => false,
            'girdi_kontrol' => false,
            'proses_kontrol' => false
        ];
        
        // Her tablo için migration durumunu kontrol et
        foreach (['final_kontrol', 'girdi_kontrol', 'proses_kontrol'] as $table) {
            $this->db->select('COUNT(*) as migrated_count');
            $this->db->from($table);
            $this->db->where('olcum_migrated', 1);
            $result = $this->db->get()->row();
            
            $status[$table] = ($result && $result->migrated_count > 0);
        }
        
        return $status;
    }
    
    /**
     * Backup oluştur
     */
    public function create_backup() {
        $this->log_message('Backup işlemi başlatılıyor...');
        
        try {
            $backup_files = [];
            
            // Her tablo için backup oluştur
            foreach (['final_kontrol', 'girdi_kontrol', 'proses_kontrol'] as $table) {
                $backup_file = $this->backup_table_olcum_data($table);
                if ($backup_file) {
                    $backup_files[] = $backup_file;
                    $this->log_message("✓ {$table} backup oluşturuldu: {$backup_file}");
                }
            }
            
            $response = [
                'status' => 'success',
                'message' => 'Backup başarıyla oluşturuldu',
                'backup_files' => $backup_files,
                'backup_dir' => $this->backup_dir,
                'log' => $this->migration_log
            ];
            
        } catch (Exception $e) {
            $this->log_message('❌ Backup hatası: ' . $e->getMessage());
            $response = [
                'status' => 'error',
                'message' => 'Backup oluşturulurken hata: ' . $e->getMessage(),
                'log' => $this->migration_log
            ];
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    /**
     * Tablo ölçüm verilerini backup et
     */
    private function backup_table_olcum_data($table_name) {
        $this->db->select('id, olcum, created_at');
        $this->db->from($table_name);
        $this->db->where('olcum IS NOT NULL');
        $this->db->where('olcum !=', '');
        $records = $this->db->get()->result();
        
        if (empty($records)) {
            $this->log_message("⚠️ {$table_name} tablosunda backup edilecek ölçüm verisi bulunamadı");
            return null;
        }
        
        $backup_data = [
            'table' => $table_name,
            'backup_date' => date('Y-m-d H:i:s'),
            'record_count' => count($records),
            'records' => []
        ];
        
        foreach ($records as $record) {
            $backup_data['records'][] = [
                'id' => $record->id,
                'olcum' => $record->olcum,
                'created_at' => $record->created_at,
                'md5_hash' => md5($record->olcum)
            ];
        }
        
        $backup_file = $this->backup_dir . $table_name . '_olcum_backup.json';
        file_put_contents($backup_file, json_encode($backup_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        return $backup_file;
    }
    
    /**
     * Migration işlemini başlat
     */
    public function start_migration() {
        $table = $this->input->post('table');
        $batch_size = (int)$this->input->post('batch_size') ?: 50;
        
        if (!in_array($table, ['final_kontrol', 'girdi_kontrol', 'proses_kontrol'])) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Geçersiz tablo adı'
                ]));
            return;
        }
        
        $this->log_message("🚀 {$table} migration başlatılıyor (Batch Size: {$batch_size})");
        
        try {
            $result = $this->migrate_table_olcum_data($table, $batch_size);
            
            $response = [
                'status' => 'success',
                'message' => "{$table} migration tamamlandı",
                'migrated_count' => $result['migrated_count'],
                'skipped_count' => $result['skipped_count'],
                'error_count' => $result['error_count'],
                'log' => $this->migration_log
            ];
            
        } catch (Exception $e) {
            $this->log_message('❌ Migration hatası: ' . $e->getMessage());
            $response = [
                'status' => 'error',
                'message' => 'Migration hatası: ' . $e->getMessage(),
                'log' => $this->migration_log
            ];
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    /**
     * Tablo ölçüm verilerini migrate et
     */
    private function migrate_table_olcum_data($table_name, $batch_size = 50) {
        $migrated_count = 0;
        $skipped_count = 0;
        $error_count = 0;
        $offset = 0;
        
        // Migration durumu sütunu ekle (eğer yoksa)
        $this->add_migration_column($table_name);
        
        do {
            // Batch halinde kayıtları getir
            $this->db->select('id, olcum');
            $this->db->from($table_name);
            $this->db->where('olcum IS NOT NULL');
            $this->db->where('olcum !=', '');
            $this->db->where('(olcum_migrated IS NULL OR olcum_migrated = 0)');
            $this->db->limit($batch_size, $offset);
            $records = $this->db->get()->result();
            
            if (empty($records)) {
                break;
            }
            
            foreach ($records as $record) {
                try {
                    // JSON verisini parse et
                    $olcum_data = json_decode($record->olcum, true);
                    
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $this->log_message("⚠️ {$table_name} ID:{$record->id} - JSON parse hatası");
                        $error_count++;
                        continue;
                    }
                    
                    // MeasurementDataService ile dosyaya kaydet
                    $file_path = $this->measurement_service->save_measurement_data(
                        $table_name,
                        $record->id,
                        $olcum_data
                    );
                    
                    if ($file_path) {
                        // Migration durumunu güncelle
                        $this->db->where('id', $record->id);
                        $this->db->update($table_name, [
                            'olcum_migrated' => 1,
                            'olcum_file_path' => $file_path,
                            'migration_date' => date('Y-m-d H:i:s')
                        ]);
                        
                        $migrated_count++;
                        
                        if ($migrated_count % 10 == 0) {
                            $this->log_message("📊 {$table_name}: {$migrated_count} kayıt migrate edildi");
                        }
                    } else {
                        $this->log_message("❌ {$table_name} ID:{$record->id} - Dosya kaydedilemedi");
                        $error_count++;
                    }
                    
                } catch (Exception $e) {
                    $this->log_message("❌ {$table_name} ID:{$record->id} - Hata: " . $e->getMessage());
                    $error_count++;
                }
            }
            
            $offset += $batch_size;
            
        } while (count($records) == $batch_size);
        
        $this->log_message("✅ {$table_name} migration tamamlandı: {$migrated_count} başarılı, {$error_count} hata");
        
        return [
            'migrated_count' => $migrated_count,
            'skipped_count' => $skipped_count,
            'error_count' => $error_count
        ];
    }
    
    /**
     * Migration sütunu ekle
     */
    private function add_migration_column($table_name) {
        // Sütun var mı kontrol et
        $query = $this->db->query("SHOW COLUMNS FROM {$table_name} LIKE 'olcum_migrated'");
        
        if ($query->num_rows() == 0) {
            $sql = "ALTER TABLE {$table_name} ADD COLUMN olcum_migrated TINYINT(1) DEFAULT 0";
            $this->db->query($sql);
            
            $sql = "ALTER TABLE {$table_name} ADD COLUMN olcum_file_path VARCHAR(255) NULL";
            $this->db->query($sql);
            
            $sql = "ALTER TABLE {$table_name} ADD COLUMN migration_date DATETIME NULL";
            $this->db->query($sql);
            
            $this->log_message("✓ {$table_name} tablosuna migration sütunları eklendi");
        }
    }
    
    /**
     * Migration doğrulama
     */
    public function verify_migration() {
        $table = $this->input->post('table');
        
        if (!in_array($table, ['final_kontrol', 'girdi_kontrol', 'proses_kontrol'])) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Geçersiz tablo adı'
                ]));
            return;
        }
        
        $this->log_message("🔍 {$table} migration doğrulaması başlatılıyor...");
        
        try {
            $verification_result = $this->verify_table_migration($table);
            
            $response = [
                'status' => 'success',
                'message' => "{$table} migration doğrulaması tamamlandı",
                'verification' => $verification_result,
                'log' => $this->migration_log
            ];
            
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Doğrulama hatası: ' . $e->getMessage(),
                'log' => $this->migration_log
            ];
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    /**
     * Tablo migration doğrulaması
     */
    private function verify_table_migration($table_name) {
        $verification = [
            'total_records' => 0,
            'migrated_records' => 0,
            'verified_records' => 0,
            'integrity_errors' => 0,
            'missing_files' => 0
        ];
        
        // Toplam kayıt sayısı
        $this->db->select('COUNT(*) as total');
        $this->db->from($table_name);
        $this->db->where('olcum IS NOT NULL');
        $this->db->where('olcum !=', '');
        $verification['total_records'] = $this->db->get()->row()->total;
        
        // Migrate edilmiş kayıt sayısı
        $this->db->select('COUNT(*) as migrated');
        $this->db->from($table_name);
        $this->db->where('olcum_migrated', 1);
        $verification['migrated_records'] = $this->db->get()->row()->migrated;
        
        // Migrate edilmiş kayıtları doğrula
        $this->db->select('id, olcum, olcum_file_path');
        $this->db->from($table_name);
        $this->db->where('olcum_migrated', 1);
        $migrated_records = $this->db->get()->result();
        
        foreach ($migrated_records as $record) {
            // Dosya var mı kontrol et
            $file_path = APPPATH . '../uploads/measurements/' . $table_name . '/' . $record->id . '.json';
            
            if (!file_exists($file_path)) {
                $verification['missing_files']++;
                $this->log_message("❌ Dosya bulunamadı: {$file_path}");
                continue;
            }
            
            // Dosya içeriği doğrula
            $file_content = file_get_contents($file_path);
            $file_data = json_decode($file_content, true);
            $original_data = json_decode($record->olcum, true);
            
            // MD5 hash karşılaştırması
            if (md5(json_encode($original_data)) === md5(json_encode($file_data))) {
                $verification['verified_records']++;
            } else {
                $verification['integrity_errors']++;
                $this->log_message("❌ Veri bütünlüğü hatası: {$table_name} ID:{$record->id}");
            }
        }
        
        $this->log_message("📊 {$table_name} doğrulama sonucu:");
        $this->log_message("   - Toplam: {$verification['total_records']}");
        $this->log_message("   - Migrate: {$verification['migrated_records']}");
        $this->log_message("   - Doğrulanan: {$verification['verified_records']}");
        $this->log_message("   - Hata: {$verification['integrity_errors']}");
        
        return $verification;
    }
    
    /**
     * Migration sonrası temizlik
     */
    public function cleanup_olcum_columns() {
        $table = $this->input->post('table');
        $force = $this->input->post('force') === 'true';
        
        if (!in_array($table, ['final_kontrol', 'girdi_kontrol', 'proses_kontrol'])) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Geçersiz tablo adı'
                ]));
            return;
        }
        
        try {
            // Migration doğrulaması yap (force değilse)
            if (!$force) {
                $verification = $this->verify_table_migration($table);
                
                if ($verification['integrity_errors'] > 0 || $verification['missing_files'] > 0) {
                    throw new Exception('Migration doğrulaması başarısız. Temizlik iptal edildi.');
                }
            }
            
            // olcum sütununu NULL yap (silme, güvenlik için)
            $this->db->where('olcum_migrated', 1);
            $this->db->update($table, ['olcum' => null]);
            
            $affected_rows = $this->db->affected_rows();
            
            $this->log_message("✅ {$table} tablosunda {$affected_rows} kaydın olcum alanı temizlendi");
            
            $response = [
                'status' => 'success',
                'message' => "{$table} olcum alanları temizlendi",
                'affected_rows' => $affected_rows,
                'log' => $this->migration_log
            ];
            
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Temizlik hatası: ' . $e->getMessage(),
                'log' => $this->migration_log
            ];
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    /**
     * Migration istatistikleri
     */
    public function get_stats() {
        $stats = [
            'database' => $this->get_current_stats(),
            'files' => $this->measurement_service->get_storage_stats(),
            'migration_status' => $this->get_migration_status()
        ];
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($stats));
    }
    
    /**
     * Log mesajı ekle
     */
    private function log_message($message) {
        $this->migration_log[] = [
            'timestamp' => date('H:i:s'),
            'message' => $message
        ];
        
        // Konsol için de logla
        log_message('info', 'OlcumMigration: ' . $message);
    }
} 