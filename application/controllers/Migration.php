<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Migration Controller.
 *
 * Ölçüm verilerini JSON'dan dosya sistemine migrate etmek için
 *
 * @author QMS Development Team
 * @version 1.0
 * @since 2025-01-07
 */
class Migration extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Session'ı manuel yükle (permissions helper için gerekli)
        $this->load->library('session');

        // Model'leri yükle
        $this->load->model('finalkontrol/final_kontrol_model');
        $this->load->library('MeasurementDataService');

        // Execution time limit kaldır
        set_time_limit(0);
        ini_set('memory_limit', '512M');
    }

    /**
     * Migration ana sayfası.
     */
    public function index()
    {
        $data = [
            'title' => 'Ölçüm Verisi Migration İşlemleri',
            'stats' => $this->get_migration_stats(),
        ];

        $this->load->view('migration/index', $data);
    }

    /**
     * Final kontrol verilerini migrate et.
     */
    public function migrate_final_kontrol($limit = 5)
    {
        echo '<h2>Final Kontrol Migration Başlatılıyor...</h2>';

        $limit = (int) $limit;
        if ($limit > 100) {
            $limit = 100;
        } // Güvenlik sınırı

        echo "<p>İşlenecek kayıt limiti: {$limit}</p>";

        try {
            $stats = $this->final_kontrol_model->migrate_json_to_files($limit);

            echo '<h3>Migration Sonuçları:</h3>';
            echo '<ul>';
            echo '<li>İşlenen kayıt: ' . $stats['processed'] . '</li>';
            echo '<li>Migrate edilen: ' . $stats['migrated'] . '</li>';
            echo '<li>Hata: ' . $stats['errors'] . '</li>';
            echo '<li>Atlanan: ' . $stats['skipped'] . '</li>';
            echo '</ul>';

            if ($stats['migrated'] > 0) {
                echo '<p>✅ Migration başarılı!</p>';
            } else {
                echo '<p>⚠️ Hiçbir kayıt migrate edilmedi.</p>';
            }
        } catch (Exception $e) {
            echo '<p>❌ Migration hatası: ' . $e->getMessage() . '</p>';
        }
    }

    /**
     * Girdi kontrol verilerini migrate et.
     */
    public function migrate_girdi_kontrol($limit = 50)
    {
        $limit = (int) $limit;
        if ($limit > 100) {
            $limit = 100;
        }

        try {
            // Girdi kontrol modelini yükle
            $CI = &get_instance();
            $CI->load->model('girdikontrol/girdi_kontrol_model');

            $stats = $CI->girdi_kontrol_model->migrate_json_to_files($limit);

            $continue = ($stats['processed'] == $limit);

            $response = [
                'success' => true,
                'message' => "Girdi Kontrol Migration - {$stats['processed']} kayıt işlendi, {$stats['migrated']} migrate edildi",
                'stats' => $stats,
                'continue' => $continue,
            ];
        } catch (Exception $e) {
            log_message('error', 'Migration::migrate_girdi_kontrol() - ' . $e->getMessage());
            $response = [
                'success' => false,
                'message' => 'Migration Hatası: ' . $e->getMessage(),
                'stats' => ['processed' => 0, 'migrated' => 0, 'errors' => 1, 'skipped' => 0],
                'continue' => false,
            ];
        }

        if ($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        } else {
            echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
        }
    }

    /**
     * Proses kontrol verilerini migrate et.
     */
    public function migrate_proses_kontrol($limit = 50)
    {
        $limit = (int) $limit;
        if ($limit > 100) {
            $limit = 100;
        }

        try {
            // Proses kontrol modelini yükle
            $CI = &get_instance();
            $CI->load->model('proseskontrol/proses_kontrol_model');

            $stats = $CI->proses_kontrol_model->migrate_json_to_files($limit);

            $continue = ($stats['processed'] == $limit);

            $response = [
                'success' => true,
                'message' => "Proses Kontrol Migration - {$stats['processed']} kayıt işlendi, {$stats['migrated']} migrate edildi",
                'stats' => $stats,
                'continue' => $continue,
            ];
        } catch (Exception $e) {
            log_message('error', 'Migration::migrate_proses_kontrol() - ' . $e->getMessage());
            $response = [
                'success' => false,
                'message' => 'Migration Hatası: ' . $e->getMessage(),
                'stats' => ['processed' => 0, 'migrated' => 0, 'errors' => 1, 'skipped' => 0],
                'continue' => false,
            ];
        }

        if ($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        } else {
            echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
        }
    }

    /**
     * Tüm migration işlemlerini otomatik çalıştır.
     */
    public function auto_migrate()
    {
        $total_stats = [
            'final_kontrol' => ['processed' => 0, 'migrated' => 0, 'errors' => 0, 'skipped' => 0],
            'girdi_kontrol' => ['processed' => 0, 'migrated' => 0, 'errors' => 0, 'skipped' => 0],
            'proses_kontrol' => ['processed' => 0, 'migrated' => 0, 'errors' => 0, 'skipped' => 0],
        ];

        $start_time = microtime(true);

        try {
            // Final kontrol migration
            do {
                $stats = $this->final_kontrol_model->migrate_json_to_files(50);
                $total_stats['final_kontrol']['processed'] += $stats['processed'];
                $total_stats['final_kontrol']['migrated'] += $stats['migrated'];
                $total_stats['final_kontrol']['errors'] += $stats['errors'];
                $total_stats['final_kontrol']['skipped'] += $stats['skipped'];
            } while ($stats['processed'] == 50);

            // Girdi kontrol migration
            $CI = &get_instance();
            $CI->load->model('girdikontrol/girdi_kontrol_model');

            do {
                $stats = $CI->girdi_kontrol_model->migrate_json_to_files(50);
                $total_stats['girdi_kontrol']['processed'] += $stats['processed'];
                $total_stats['girdi_kontrol']['migrated'] += $stats['migrated'];
                $total_stats['girdi_kontrol']['errors'] += $stats['errors'];
                $total_stats['girdi_kontrol']['skipped'] += $stats['skipped'];
            } while ($stats['processed'] == 50);

            // Proses kontrol migration
            $CI->load->model('proseskontrol/proses_kontrol_model');

            do {
                $stats = $CI->proses_kontrol_model->migrate_json_to_files(50);
                $total_stats['proses_kontrol']['processed'] += $stats['processed'];
                $total_stats['proses_kontrol']['migrated'] += $stats['migrated'];
                $total_stats['proses_kontrol']['errors'] += $stats['errors'];
                $total_stats['proses_kontrol']['skipped'] += $stats['skipped'];
            } while ($stats['processed'] == 50);
        } catch (Exception $e) {
            log_message('error', 'Migration::auto_migrate() - ' . $e->getMessage());
        }

        $end_time = microtime(true);
        $execution_time = round($end_time - $start_time, 2);

        $response = [
            'success' => true,
            'message' => 'Otomatik Migration Tamamlandı',
            'execution_time' => $execution_time . ' saniye',
            'total_stats' => $total_stats,
            'storage_stats' => $this->measurementdataservice->get_storage_stats(),
        ];

        if ($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        } else {
            echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
        }
    }

    /**
     * Migration istatistiklerini getir.
     */
    public function get_migration_stats()
    {
        $stats = [];
        $CI = &get_instance();

        try {
            // Final kontrol istatistikleri
            $query = $CI->db->query("
                SELECT 
                    COUNT(*) as toplam_kayit,
                    COUNT(CASE WHEN olcum IS NOT NULL AND olcum != '' THEN 1 END) as json_kayit,
                    COUNT(CASE WHEN measurement_file IS NOT NULL AND measurement_file != '' THEN 1 END) as dosya_kayit,
                    ROUND(SUM(LENGTH(olcum)) / 1024 / 1024, 2) as json_mb
                FROM final_kontrol
            ");
            $stats['final_kontrol'] = $query->row_array();

            // Girdi kontrol istatistikleri
            $query = $CI->db->query("
                SELECT 
                    COUNT(*) as toplam_kayit,
                    COUNT(CASE WHEN olcum IS NOT NULL AND olcum != '' THEN 1 END) as json_kayit,
                    COUNT(CASE WHEN measurement_file IS NOT NULL AND measurement_file != '' THEN 1 END) as dosya_kayit,
                    ROUND(SUM(LENGTH(olcum)) / 1024 / 1024, 2) as json_mb
                FROM girdi_kontrol
            ");
            $stats['girdi_kontrol'] = $query->row_array();

            // Proses kontrol istatistikleri
            $query = $CI->db->query("
                SELECT 
                    COUNT(*) as toplam_kayit,
                    COUNT(CASE WHEN olcum IS NOT NULL AND olcum != '' THEN 1 END) as json_kayit,
                    COUNT(CASE WHEN measurement_file IS NOT NULL AND measurement_file != '' THEN 1 END) as dosya_kayit,
                    ROUND(SUM(LENGTH(olcum)) / 1024 / 1024, 2) as json_mb
                FROM proses_kontrol
            ");
            $stats['proses_kontrol'] = $query->row_array();

            // Dosya sistemi istatistikleri
            $stats['storage'] = $this->measurementdataservice->get_storage_stats();
        } catch (Exception $e) {
            log_message('error', 'Migration::get_migration_stats() - ' . $e->getMessage());
            $stats['error'] = $e->getMessage();
        }

        return $stats;
    }

    /**
     * Test migration (küçük örnek).
     */
    public function test_migration()
    {
        echo '<h2>Test Migration Başlatılıyor...</h2>';

        try {
            // Final kontrol tablosunda kaç kayıt var kontrol et
            $CI = &get_instance();
            $query = $CI->db->query("SELECT COUNT(*) as count FROM final_kontrol WHERE olcum IS NOT NULL AND olcum != ''");
            $result = $query->row();

            echo '<p>Migrate edilebilir kayıt sayısı: ' . $result->count . '</p>';

            if ($result->count == 0) {
                echo '<p>❌ Migrate edilecek kayıt bulunamadı.</p>';

                return;
            }

            // İlk 5 kaydı getir
            $query = $CI->db->query("SELECT id, olcum FROM final_kontrol WHERE olcum IS NOT NULL AND olcum != '' LIMIT 5");
            $records = $query->result();

            echo '<p>✅ Test için ' . count($records) . ' kayıt bulundu.</p>';

            foreach ($records as $record) {
                echo "<p>ID: {$record->id} - Ölçüm verisi: " . strlen($record->olcum) . ' karakter</p>';
            }

            echo '<p>✅ Test migration tamamlandı.</p>';
        } catch (Exception $e) {
            echo '<p>❌ Hata: ' . $e->getMessage() . '</p>';
        }
    }

    /**
     * Dosya sistemi temizleme.
     */
    public function cleanup_files($days = 365)
    {
        $days = (int) $days;
        if ($days < 30) {
            $days = 30;
        } // Minimum 30 gün

        $deleted_count = $this->measurementdataservice->cleanup_old_files($days);

        $response = [
            'success' => true,
            'message' => "{$days} günden eski {$deleted_count} dosya temizlendi",
            'deleted_count' => $deleted_count,
        ];

        if ($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        } else {
            echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
        }
    }
}
