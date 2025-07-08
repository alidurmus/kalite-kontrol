<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public $viewFolder = '';
    private $dashboard_config = [];

    public function __construct()
    {
        parent::__construct();

        $this->viewFolder = 'dashboard';
        
        // Load dashboard configuration and helper
        $this->config->load('dashboard_config');
        $this->load->helper('dashboard');
        $this->dashboard_config = $this->config->item('dashboard_config') ?: [];
        
        // Load required models
        $this->load->model('kontrol_no/kontrol_no_model');
        $this->load->model('girdikontrol/girdi_kontrol_model');
        $this->load->model('proseskontrol/proses_kontrol_model');
        $this->load->model('finalkontrol/final_kontrol_model');
        $this->load->model('tedarikciler/tedarikciler_model');
        $this->load->model('malzemeler/malzemeler_model');
        $this->load->model('urunler/urunler_model');
        $this->load->model('users/user_model');
        
        $this->load->library('pagination');

        // Authentication check
        if (!get_active_user()) {
            redirect(base_url('login'));
        }
    }

    public function index()
    {
        $viewData = new stdClass();

        // Get dashboard statistics using cached approach
        $viewData->stats = $this->get_dashboard_stats();
        
        // Get mini statistics
        $viewData->mini_stats = $this->get_mini_stats();
        
        // Get recent activities
        $viewData->activities = $this->get_recent_activities();
        
        // Get performance metrics
        $viewData->performance_metrics = $this->calculate_performance_metrics();
        
        // Generate chart data
        $viewData->chart_data = $this->generate_chart_data();

        // Pagination configuration
        $pagination_config = $this->get_pagination_config();
        $search_text = $this->handle_search();
        
        $this->pagination->initialize($pagination_config);
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $links = $this->pagination->create_links();
        $items = $this->kontrol_no_model->get_limit([], $pagination_config['per_page'], $page, $search_text);

        // Set view data
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'dashboard';
        $viewData->items = $items;
        $viewData->links = $links;
        $viewData->search_text = $search_text;
        $viewData->dashboard_config = $this->dashboard_config;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    /**
     * Get dashboard statistics with caching
     */
    private function get_dashboard_stats()
    {
        // Check cache first
        $cache_key = 'dashboard_stats';
        $cache_ttl = $this->config->item('cache_ttl')['dashboard_stats'] ?? 300;
        
        // For now, get fresh data (implement caching later)
        $stats = new stdClass();
        
        try {
            // Main statistics
            $stats->girdi_kontrol_total = $this->girdi_kontrol_model->get_count();
            $stats->proses_kontrol_total = $this->proses_kontrol_model->get_count();
            $stats->final_kontrol_total = $this->final_kontrol_model->get_count();
            $stats->kontrol_no_total = $this->kontrol_no_model->get_count();

            // Today's counts
            $today = date('Y-m-d');
            $stats->girdi_kontrol_today = method_exists($this->girdi_kontrol_model, 'get_count_by_date') ?
                $this->girdi_kontrol_model->get_count_by_date($today) : 0;
            $stats->proses_kontrol_today = method_exists($this->proses_kontrol_model, 'get_count_by_date') ?
                $this->proses_kontrol_model->get_count_by_date($today) : 0;
            $stats->final_kontrol_today = method_exists($this->final_kontrol_model, 'get_count_by_date') ?
                $this->final_kontrol_model->get_count_by_date($today) : 0;
                
        } catch (Exception $e) {
            log_message('error', 'Dashboard stats error: ' . $e->getMessage());
            // Return default values on error
            $stats->girdi_kontrol_total = 0;
            $stats->proses_kontrol_total = 0;
            $stats->final_kontrol_total = 0;
            $stats->kontrol_no_total = 0;
            $stats->girdi_kontrol_today = 0;
            $stats->proses_kontrol_today = 0;
            $stats->final_kontrol_today = 0;
        }

        return $stats;
    }

    /**
     * Get mini statistics for dashboard
     */
    private function get_mini_stats()
    {
        $mini_stats = new stdClass();
        
        try {
            $mini_stats->tedarikciler_total = $this->tedarikciler_model->get_count();
            $mini_stats->malzemeler_total = $this->malzemeler_model->get_count();
            $mini_stats->urunler_total = $this->urunler_model->get_count();
            $mini_stats->users_total = $this->user_model->get_count();
        } catch (Exception $e) {
            log_message('error', 'Mini stats error: ' . $e->getMessage());
            $mini_stats->tedarikciler_total = 0;
            $mini_stats->malzemeler_total = 0;
            $mini_stats->urunler_total = 0;
            $mini_stats->users_total = 0;
        }

        return $mini_stats;
    }

    /**
     * Get recent activities for dashboard
     */
    private function get_recent_activities()
    {
        $activities = new stdClass();
        
        try {
            $activities->recent_girdi = $this->girdi_kontrol_model->get_all([], 'id DESC', 5);
            $activities->recent_proses = $this->proses_kontrol_model->get_all([], 'id DESC', 5);
            $activities->recent_final = $this->final_kontrol_model->get_all([], 'id DESC', 5);
        } catch (Exception $e) {
            log_message('error', 'Recent activities error: ' . $e->getMessage());
            $activities->recent_girdi = [];
            $activities->recent_proses = [];
            $activities->recent_final = [];
        }

        return $activities;
    }

    /**
     * Calculate performance metrics
     */
    private function calculate_performance_metrics()
    {
        $metrics = [];
        
        try {
            // Calculate quality success rate
            $total_controls = $this->girdi_kontrol_model->get_count() + 
                            $this->proses_kontrol_model->get_count() + 
                            $this->final_kontrol_model->get_count();
            
            // This is a placeholder calculation - implement actual business logic
            $successful_controls = $total_controls * 0.95; // Placeholder: 95% success rate
            $quality_success = $total_controls > 0 ? round(($successful_controls / $total_controls) * 100) : 0;
            
            $metrics = [
                'quality_success' => $quality_success,
                'delivery_ontime' => 88, // Placeholder - implement actual calculation
                'customer_satisfaction' => 92 // Placeholder - implement actual calculation
            ];
            
        } catch (Exception $e) {
            log_message('error', 'Performance metrics error: ' . $e->getMessage());
            $metrics = [
                'quality_success' => 0,
                'delivery_ontime' => 0,
                'customer_satisfaction' => 0
            ];
        }

        return $metrics;
    }

    /**
     * Generate chart data for dashboard
     */
    private function generate_chart_data()
    {
        $stats = $this->get_dashboard_stats();
        
        return generate_chart_data([
            ['label' => 'Girdi Kontrol', 'value' => $stats->girdi_kontrol_total],
            ['label' => 'Proses Kontrol', 'value' => $stats->proses_kontrol_total],
            ['label' => 'Final Kontrol', 'value' => $stats->final_kontrol_total]
        ]);
    }

    /**
     * Get pagination configuration
     */
    private function get_pagination_config()
    {
        $config = [];
        $config['base_url'] = base_url() . 'dashboard';
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        $config['num_links'] = 5;
        
        // Bootstrap 4 pagination styling
        $config['full_tag_open'] = "<nav> <ul class='pagination'>";
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'İlk';
        $config['first_tag_open'] = "<li class='page-item'>";
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Son';
        $config['last_tag_open'] = "<li class='page-item'>";
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = "<li class='page-item'>";
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = "<li class='page-item'>";
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='page-item active'><a href='#'>";
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = "<li class='page-item'>";
        $config['num_tag_close'] = '</li>';

        return $config;
    }

    /**
     * Handle search functionality
     */
    private function handle_search()
    {
        $search_text = '';
        
        if ($this->input->post('submit') != null) {
            $search_text = $this->security->xss_clean($this->input->post('search'));
            $this->session->set_userdata(['search' => $search_text]);
        } else {
            if ($this->session->userdata('search') != null) {
                $search_text = $this->session->userdata('search');
            }
        }

        return $search_text;
    }

    public function search()
    {
        // Redirect to main dashboard with search parameter
        $search_data = $this->security->xss_clean($this->input->post('keyword'));
        $this->session->set_userdata(['search' => $search_data]);
        redirect('dashboard');
    }

    /**
     * AJAX endpoint for refreshing dashboard data
     */
    public function refresh_data()
    {
        // Set JSON response headers
        $this->output->set_content_type('application/json');
        
        try {
            $data = [
                'stats' => $this->get_dashboard_stats(),
                'mini_stats' => $this->get_mini_stats(),
                'chart_data' => $this->generate_chart_data(),
                'performance_metrics' => $this->calculate_performance_metrics(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $this->output->set_output(json_encode([
                'success' => true,
                'data' => $data
            ]));
            
        } catch (Exception $e) {
            log_message('error', 'Dashboard refresh error: ' . $e->getMessage());
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Veri yenileme hatası oluştu.'
            ]));
        }
    }

    /**
     * Reduce dashboard data (keep existing method)
     */
    public function reduce_data()
    {
        $models_to_process = [
            'girdikontrol/girdi_kontrol_model',
            'proseskontrol/proses_kontrol_model',
            'finalkontrol/final_kontrol_model',
            'malzemeler/malzemeler_model',
            'urunler/Urunler_model',
            'isemri/Isemri_model',
            'musteriler/Musteriler_model',
            'tedarikciler/Tedarikciler_model',
        ];

        echo '<h1>Veritabanı Kayıt Azaltma</h1>';
        echo '<p>Bu işlem, belirtilen tablolardaki kayıt sayısını en son 100 kayda indirecektir.</p><hr>';

        foreach ($models_to_process as $model_path) {
            $this->load->model($model_path);

            $model_property = basename(strtolower($model_path));

            $table_name = $this->$model_property->tableName;

            if (!$table_name) {
                echo "<b style='color:red;'>'{$model_property}' modeli için tablo adı bulunamadı. Atlanıyor.</b><hr>";
                continue;
            }

            $count = $this->db->count_all($table_name);

            echo "<b>'{$table_name}' tablosu işleniyor...</b> Toplam {$count} kayıt bulundu.<br>";

            if ($count > 100) {
                $query = $this->db->select('id')->from($table_name)->order_by('id', 'DESC')->limit(100)->get();
                $result = $query->result();

                if (!empty($result)) {
                    $ids_to_keep = array_column($result, 'id');

                    $this->db->where_not_in('id', $ids_to_keep);
                    $this->db->delete($table_name);

                    $affected_rows = $this->db->affected_rows();
                    echo "<span style='color:green;'>Başarılı: {$affected_rows} kayıt silindi. Tabloda 100 kayıt bırakıldı.</span><br>";
                } else {
                    echo "<span style='color:orange;'>Uyarı: Tutulacak kayıtlar seçilemedi. Tabloya dokunulmadı.</span><br>";
                }
            } else {
                echo "<span style='color:blue;'>Bilgi: Tabloda 100 veya daha az kayıt olduğundan işlem yapılmadı.</span><br>";
            }
            echo '<hr>';
        }
        echo '<h2>Tüm işlemler tamamlandı!</h2>';
    }
}
