<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Measurement Data Service.
 *
 * Kalite kontrol ölçüm verilerini dosya sisteminde yönetir
 * Veritabanı boyutunu azaltmak için JSON verilerini harici dosyalarda saklar
 *
 * @author QMS Development Team
 * @version 1.0
 * @since 2025-01-07
 */
class MeasurementDataService
{
    private $CI;

    private $base_path;

    private $allowed_types = ['final_kontrol', 'girdi_kontrol', 'proses_kontrol'];

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->base_path = FCPATH . 'uploads/measurements/';

        // Klasör yapısını oluştur
        $this->_ensure_directory_structure();
    }

    /**
     * Ölçüm verisini dosyaya kaydet.
     *
     * @param string $type Kontrol tipi (final_kontrol, girdi_kontrol, proses_kontrol)
     * @param int $record_id Kayıt ID'si
     * @param array $measurement_data Ölçüm verisi
     * @return string|false Dosya adı veya false
     */
    public function save_measurement_data($type, $record_id, $measurement_data)
    {
        try {
            // Tip kontrolü
            if (!in_array($type, $this->allowed_types)) {
                throw new Exception("Invalid measurement type: {$type}");
            }

            // Veri validasyonu
            if (empty($measurement_data) || !is_array($measurement_data)) {
                throw new Exception('Invalid measurement data');
            }

            // Dosya adı oluştur
            $filename = $this->_generate_filename($type, $record_id);
            $filepath = $this->_get_file_path($filename);

            // JSON verisini hazırla
            $json_data = [
                'type' => $type,
                'record_id' => $record_id,
                'created_at' => date('Y-m-d H:i:s'),
                'data' => $measurement_data,
                'checksum' => md5(json_encode($measurement_data)),
            ];

            // Dosyaya yaz
            $json_string = json_encode($json_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            if (file_put_contents($filepath, $json_string) === false) {
                throw new Exception("Failed to write measurement file: {$filepath}");
            }

            // Log kaydı
            log_message('info', "Measurement data saved: {$filename} for {$type} ID: {$record_id}");

            return $filename;
        } catch (Exception $e) {
            log_message('error', 'MeasurementDataService::save_measurement_data() - ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Ölçüm verisini dosyadan oku.
     *
     * @param string $filename Dosya adı
     * @return array|false Ölçüm verisi veya false
     */
    public function load_measurement_data($filename)
    {
        try {
            if (empty($filename)) {
                return false;
            }

            $filepath = $this->_get_file_path($filename);

            if (!file_exists($filepath)) {
                log_message('error', "Measurement file not found: {$filepath}");

                return false;
            }

            $json_string = file_get_contents($filepath);
            if ($json_string === false) {
                throw new Exception("Failed to read measurement file: {$filepath}");
            }

            $data = json_decode($json_string, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid JSON in measurement file: {$filepath}");
            }

            // Checksum kontrolü
            if (isset($data['data']) && isset($data['checksum'])) {
                $calculated_checksum = md5(json_encode($data['data']));
                if ($calculated_checksum !== $data['checksum']) {
                    log_message('error', "Checksum mismatch for file: {$filename}");
                }
            }

            return $data['data'] ?? false;
        } catch (Exception $e) {
            log_message('error', 'MeasurementDataService::load_measurement_data() - ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Ölçüm dosyasını sil.
     *
     * @param string $filename Dosya adı
     * @return bool Başarı durumu
     */
    public function delete_measurement_data($filename)
    {
        try {
            if (empty($filename)) {
                return false;
            }

            $filepath = $this->_get_file_path($filename);

            if (file_exists($filepath)) {
                $result = unlink($filepath);
                if ($result) {
                    log_message('info', "Measurement file deleted: {$filename}");
                }

                return $result;
            }

            return true; // Dosya zaten yok
        } catch (Exception $e) {
            log_message('error', 'MeasurementDataService::delete_measurement_data() - ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Dosya adı oluştur.
     *
     * @param string $type Kontrol tipi
     * @param int $record_id Kayıt ID'si
     * @return string Dosya adı
     */
    private function _generate_filename($type, $record_id)
    {
        $date = date('Ymd');
        $time = date('His');
        $prefix = substr($type, 0, 2); // fk, gk, pk

        return "{$prefix}_{$date}_{$time}_{$record_id}.json";
    }

    /**
     * Dosya yolunu oluştur.
     *
     * @param string $filename Dosya adı
     * @return string Tam dosya yolu
     */
    private function _get_file_path($filename)
    {
        // Migration sonrası dosyalar için önce mevcut dosya konumlarını kontrol et
        $possible_paths = [
            // Önce 2025/07 klasörünü kontrol et (migration dosyaları)
            $this->base_path . '2025/07/' . $filename,
            // Sonra mevcut tarih klasörünü kontrol et
            $this->base_path . date('Y') . '/' . date('m') . '/' . $filename,
        ];
        
        // Mevcut dosyayı ara
        foreach ($possible_paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        // Dosya bulunamadıysa varsayılan yolu döndür (yeni dosyalar için)
        $year = date('Y');
        $month = date('m');

        $directory = $this->base_path . $year . '/' . $month . '/';
        $this->_ensure_directory_exists($directory);

        return $directory . $filename;
    }

    /**
     * Klasör yapısını oluştur.
     */
    private function _ensure_directory_structure()
    {
        $this->_ensure_directory_exists($this->base_path);
    }

    /**
     * Klasörün var olduğundan emin ol.
     *
     * @param string $directory Klasör yolu
     */
    private function _ensure_directory_exists($directory)
    {
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                throw new Exception("Failed to create directory: {$directory}");
            }
        }
    }

    /**
     * Eski dosyaları temizle (opsiyonel).
     *
     * @param int $days Kaç günden eski dosyalar silinsin
     * @return int Silinen dosya sayısı
     */
    public function cleanup_old_files($days = 365)
    {
        $deleted_count = 0;
        $cutoff_time = time() - ($days * 24 * 60 * 60);

        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->base_path),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'json') {
                    if ($file->getMTime() < $cutoff_time) {
                        if (unlink($file->getPathname())) {
                            ++$deleted_count;
                        }
                    }
                }
            }

            log_message('info', "Cleaned up {$deleted_count} old measurement files");
        } catch (Exception $e) {
            log_message('error', 'MeasurementDataService::cleanup_old_files() - ' . $e->getMessage());
        }

        return $deleted_count;
    }

    /**
     * Dosya boyutu istatistikleri.
     *
     * @return array İstatistik bilgileri
     */
    public function get_storage_stats()
    {
        $stats = [
            'total_files' => 0,
            'total_size' => 0,
            'by_year' => [],
            'by_type' => [],
        ];

        try {
            if (!is_dir($this->base_path)) {
                return $stats;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->base_path),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'json') {
                    ++$stats['total_files'];
                    $stats['total_size'] += $file->getSize();

                    // Yıl bazında istatistik
                    $year = date('Y', $file->getMTime());
                    if (!isset($stats['by_year'][$year])) {
                        $stats['by_year'][$year] = ['count' => 0, 'size' => 0];
                    }
                    ++$stats['by_year'][$year]['count'];
                    $stats['by_year'][$year]['size'] += $file->getSize();

                    // Tip bazında istatistik (dosya adından çıkar)
                    $basename = $file->getBasename('.json');
                    $prefix = substr($basename, 0, 2);
                    if (!isset($stats['by_type'][$prefix])) {
                        $stats['by_type'][$prefix] = ['count' => 0, 'size' => 0];
                    }
                    ++$stats['by_type'][$prefix]['count'];
                    $stats['by_type'][$prefix]['size'] += $file->getSize();
                }
            }
        } catch (Exception $e) {
            log_message('error', 'MeasurementDataService::get_storage_stats() - ' . $e->getMessage());
        }

        return $stats;
    }
}
