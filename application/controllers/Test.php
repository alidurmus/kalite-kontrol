<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo '<h1>Test Controller</h1>';

        // Database manuel yükleme
        $this->load->database();

        echo '<p>Database yükleme tamamlandı</p>';
        echo '<p>Database object: ' . (isset($this->db) ? 'YES' : 'NO') . '</p>';

        if (isset($this->db)) {
            try {
                $query = $this->db->query('SELECT 1 as test');
                $result = $query->row();
                echo '<p>✅ Database test başarılı: ' . $result->test . '</p>';
            } catch (Exception $e) {
                echo '<p>❌ Database test hatası: ' . $e->getMessage() . '</p>';
            }
        }
    }

    public function db_config()
    {
        echo '<h1>Database Config</h1>';
        $this->load->database();

        echo '<pre>';
        print_r($this->db);
        echo '</pre>';
    }
}
