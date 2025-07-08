<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simple Migration Test Controller
 */
class OlcumMigrationTest extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Manuel olarak database yükle
        $this->load->database();
        
        // Database bağlantısını test et
        if (!$this->db) {
            show_error('Database bağlantısı kurulamadı');
        }
    }
    
    public function index() {
        // Output buffering başlat
        ob_start();
        
        echo "<h1>Ölçüm Migration Test</h1>";
        
        // Test database connection
        echo "<h3>Database Test:</h3>";
        try {
            // Database bağlantısını kontrol et
            if (!$this->db->conn_id) {
                throw new Exception('Database bağlantısı yok');
            }
            
            $query = $this->db->query("SELECT COUNT(*) as count FROM final_kontrol WHERE olcum IS NOT NULL AND olcum != ''");
            $result = $query->row();
            echo "✅ Final Kontrol olcum kayıtları: " . $result->count . "<br>";
            
            $query = $this->db->query("SELECT COUNT(*) as count FROM girdi_kontrol WHERE olcum IS NOT NULL AND olcum != ''");
            $result = $query->row();
            echo "✅ Girdi Kontrol olcum kayıtları: " . $result->count . "<br>";
            
            $query = $this->db->query("SELECT COUNT(*) as count FROM proses_kontrol WHERE olcum IS NOT NULL AND olcum != ''");
            $result = $query->row();
            echo "✅ Proses Kontrol olcum kayıtları: " . $result->count . "<br>";
            
        } catch (Exception $e) {
            echo "❌ Database hatası: " . $e->getMessage() . "<br>";
        }
        
        // Test measurement service
        echo "<h3>MeasurementDataService Test:</h3>";
        try {
            $this->load->library('MeasurementDataService');
            
            if (isset($this->measurementdataservice)) {
                echo "✅ MeasurementDataService yüklendi<br>";
                $stats = $this->measurementdataservice->get_storage_stats();
                echo "📊 Storage Stats: " . json_encode($stats) . "<br>";
            } else {
                echo "❌ MeasurementDataService yüklenemedi<br>";
            }
        } catch (Exception $e) {
            echo "❌ MeasurementDataService hatası: " . $e->getMessage() . "<br>";
        }
        
        // Show sample data
        echo "<h3>Sample Data:</h3>";
        try {
            $query = $this->db->query("SELECT id, olcum FROM final_kontrol WHERE olcum IS NOT NULL AND olcum != '' LIMIT 3");
            $results = $query->result();
            
            if (empty($results)) {
                echo "⚠️ Örnek veri bulunamadı<br>";
            } else {
                foreach ($results as $row) {
                    echo "<strong>ID {$row->id}:</strong><br>";
                    echo "<pre>" . htmlspecialchars(substr($row->olcum, 0, 200)) . "...</pre><br>";
                }
            }
            
        } catch (Exception $e) {
            echo "❌ Sample data hatası: " . $e->getMessage() . "<br>";
        }
        
        echo "<br><a href='" . base_url('anasayfa') . "'>← Ana Sayfa</a>";
        echo "<br><a href='" . base_url('olcummigrationtest/test_migration') . "'>Test Migration →</a>";
        
        // Output'u gönder
        $output = ob_get_clean();
        echo $output;
    }
    
    public function test_migration() {
        ob_start();
        
        echo "<h1>Test Migration</h1>";
        
        try {
            $this->load->library('MeasurementDataService');
            
            if (!isset($this->measurementdataservice)) {
                throw new Exception('MeasurementDataService yüklenemedi');
            }
            
            // Test data
            $test_data = [
                'olcum_tarihi' => date('Y-m-d H:i:s'),
                'degerler' => [
                    'test1' => 123.45,
                    'test2' => 'Test değer'
                ]
            ];
            
            // Save test
            $file_path = $this->measurementdataservice->save_measurement_data(
                'test_table',
                999,
                $test_data
            );
            
            if ($file_path) {
                echo "✅ Test dosya kaydedildi: " . $file_path . "<br>";
                
                // Load test
                $loaded_data = $this->measurementdataservice->load_measurement_data('test_table', 999);
                
                if ($loaded_data) {
                    echo "✅ Test dosya yüklendi:<br>";
                    echo "<pre>" . json_encode($loaded_data, JSON_PRETTY_PRINT) . "</pre>";
                } else {
                    echo "❌ Test dosya yüklenemedi<br>";
                }
                
            } else {
                echo "❌ Test dosya kaydedilemedi<br>";
            }
            
        } catch (Exception $e) {
            echo "❌ Test migration hatası: " . $e->getMessage() . "<br>";
        }
        
        echo "<br><a href='" . base_url('olcummigrationtest') . "'>← Geri</a>";
        
        $output = ob_get_clean();
        echo $output;
    }
} 