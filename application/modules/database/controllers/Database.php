<?php

class Database extends MY_Controller
{
    public $viewFolder = '';

    public function __construct()
    {
        parent::__construct();

        // Şimdilik permission kontrolü yapmıyoruz - admin aracı olarak çalışacak
        // $this->load->helper('permissions');
        // $this->load->helper('tools');

        // if (!isAllowedViewModule("database")) {
        //     redirect(base_url());
        // }

        $this->viewFolder = 'database_v';
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
    }

    public function index()
    {
        $viewData = new stdClass();
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'list';
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function backup()
    {
        // Memory limit'i artır
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        try {
            // Sadece SQL formatı kullan (ZIP yerine)
            $prefs = [
                'format'      => 'txt',
                'filename'    => 'cms_db_backup.sql',
            ];

            $backup = $this->dbutil->backup($prefs);

            $db_name = 'backup-on-' . date('Y-m-d-H-i-s') . '.sql';

            // Doğrudan download et, dosyaya yazma
            force_download($db_name, $backup);
        } catch (Exception $e) {
            $alert = [
                'title' => 'İşlem Başarısız',
                'text' => 'Yedekleme sırasında hata oluştu: ' . $e->getMessage(),
                'type'  => 'error',
            ];
            $this->session->set_flashdata('alert', $alert);
            redirect(base_url('database'));
        }
    }

    public function reduce_data()
    {
        $models_to_process = [
            ['path' => 'girdikontrol/girdi_kontrol_model', 'property' => 'girdi_kontrol_model'],
            ['path' => 'proseskontrol/proses_kontrol_model', 'property' => 'proses_kontrol_model'],
            ['path' => 'finalkontrol/final_kontrol_model', 'property' => 'final_kontrol_model'],
            ['path' => 'malzemeler/malzemeler_model', 'property' => 'malzemeler_model'],
            ['path' => 'urunler/Urunler_model', 'property' => 'Urunler_model'],
            ['path' => 'isemri/Isemri_model', 'property' => 'Isemri_model'],
            ['path' => 'musteriler/Musteriler_model', 'property' => 'Musteriler_model'],
            ['path' => 'tedarikciler/Tedarikciler_model', 'property' => 'Tedarikciler_model'],
        ];

        echo "<html><head><title>Veritabanı Bakımı</title><link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'></head><body class='container pt-5'>";
        echo '<h1>Veritabanı Kayıt Azaltma</h1>';
        echo '<p>Bu işlem, belirtilen tablolardaki kayıt sayısını en son 100 kayda indirecektir.</p><hr>';

        foreach ($models_to_process as $model_info) {
            $this->load->model($model_info['path']);

            $model_property = $model_info['property'];

            if (!property_exists($this->$model_property, 'tableName')) {
                echo "<div class='alert alert-danger'><b>Hata:</b> '{$model_property}' modeli için 'tableName' özelliği bulunamadı. Atlanıyor.</div>";
                continue;
            }

            $table_name = $this->$model_property->tableName;
            $count = $this->db->count_all($table_name);

            echo "<div class='alert alert-info'><b>'{$table_name}' tablosu işleniyor...</b> Toplam {$count} kayıt bulundu.</div>";

            if ($count > 100) {
                $query = $this->db->select('id')->from($table_name)->order_by('id', 'DESC')->limit(100)->get();
                $result = $query->result();

                if (!empty($result)) {
                    $ids_to_keep = array_column($result, 'id');

                    $this->db->where_not_in('id', $ids_to_keep);
                    $this->db->delete($table_name);

                    $affected_rows = $this->db->affected_rows();
                    echo "<div class='alert alert-success'><b>Başarılı:</b> {$affected_rows} kayıt silindi. Tabloda 100 kayıt bırakıldı.</div>";
                } else {
                    echo "<div class='alert alert-warning'><b>Uyarı:</b> Tutulacak kayıtlar seçilemedi. Tabloya dokunulmadı.</div>";
                }
            } else {
                echo "<div class='alert alert-secondary'><b>Bilgi:</b> Tabloda 100 veya daha az kayıt olduğundan işlem yapılmadı.</div>";
            }
            echo '<hr>';
        }
        echo '<h2>Tüm işlemler tamamlandı!</h2>';
        echo "<a href='" . base_url('database') . "' class='btn btn-primary mt-3'>Veritabanı Yönetimine Geri Dön</a>";
        echo '</body></html>';
    }
}
