<?php

class Kontrol_no extends MY_Controller
{
    public $viewFolder = '';

    public function __construct()
    {
        parent::__construct();

        $this->viewFolder = 'kontrol_no';

        $this->load->model('kontrol_no/kontrol_no_model');
        $this->load->model('girdikontrol/girdi_kontrol_model');
        $this->load->model('tedarikciler/tedarikciler_model');
        $this->load->model('malzemeler/malzemeler_model');

        if (!get_active_user()) {
            redirect(base_url('login'));
        }
        if (!isAllowedViewModule()) {
            redirect(base_url());
        }
    }

    public function index()
    {
        $viewData = new stdClass();

        $this->load->library('pagination');

        $search_process = $this->input->get('search_process');
        $search_parti = $this->input->get('search_parti');
        $search_date = $this->input->get('search_date');

        $where = [];

        if (!empty($search_process)) {
            $where['process_isim'] = $search_process;
        }

        if (!empty($search_parti)) {
            $where['parti_no LIKE'] = '%' . $search_parti . '%';
        }

        if (!empty($search_date)) {
            $where['DATE(tarih)'] = $search_date;
        }

        $config = [];
        $config['base_url'] = base_url('kontrol_no/index');

        $config['total_rows'] = $this->kontrol_no_model->get_count($where);
        $config['per_page'] = 20;
        $config['use_page_numbers'] = true;
        $config['uri_segment'] = 3;

        $config['reuse_query_string'] = true;
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);

        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'İlk';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Son';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Sonraki &raquo;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo; Önceki';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? intval($this->uri->segment(3)) : 1;
        if ($page < 1) {
            $page = 1;
        }
        $start = ($page - 1) * $config['per_page'];

        $links = $this->pagination->create_links();

        $items = $this->kontrol_no_model->get_limit(
            $where,
            $config['per_page'],
            $start,
            'id DESC'
        );

        $today = date('Y-m-d');
        $week_ago = date('Y-m-d', strtotime('-7 days'));

        $today_where = $where;
        $today_where['DATE(tarih)'] = $today;
        $today_count = $this->kontrol_no_model->get_count($today_where);

        $girdi_where = $where;
        $girdi_where['process_isim'] = 'girdikontrol';
        $girdi_count = $this->kontrol_no_model->get_count($girdi_where);

        $this->db->select('COUNT(*) as count')->from('kontrol_no')->where('DATE(tarih) >=', $week_ago);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $week_count = $this->db->get()->row()->count;

        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'list';
        $viewData->items = $items;
        $viewData->links = $links;
        $viewData->total_rows = $config['total_rows'];
        $viewData->per_page = $config['per_page'];
        $viewData->current_page = $page;
        $viewData->total_pages = ceil($config['total_rows'] / $config['per_page']);

        $viewData->today_count = $today_count;
        $viewData->girdi_count = $girdi_count;
        $viewData->week_count = $week_count;

        $viewData->search_process = $search_process;
        $viewData->search_parti = $search_parti;
        $viewData->search_date = $search_date;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function listele()
    {
        $viewData = new stdClass();

        /** Tablodan Verilerin Getirilmesi.. */
        $items = $this->kontrol_no_model->get_all(
            [],
            'id ASC'
        );
        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'list';
        $viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/list/index", $viewData);
    }

    public function new_form()
    {
        $viewData = new stdClass();

        $viewData->tedarikciler = $this->tedarikciler_model->get_all();
        $viewData->malzemeler = $this->malzemeler_model->get_all();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'add';

        $this->load->view("{$viewData->viewFolder}/add/index", $viewData);
    }

    public function save()
    {
        $this->load->library('form_validation');

        // Kurallar yazilir..

        $parti_no = $this->input->post('parti_no');
        $tedarikci = $this->input->post('tedarikci');
        $malzeme = $this->input->post('malzeme');
        $irsaliye = $this->input->post('irsaliye');
        $tarih = $this->input->post('tarih');
        $aciklama = $this->input->post('aciklama');
        $kullanici = $this->input->post('kullanici');

        $this->form_validation->set_rules('parti_no', 'Parti No', 'required|trim');
        // $this->form_validation->set_rules("tedarikci", "tedarikci", "required|trim");
        //$this->form_validation->set_rules("malzeme", "malzeme ", "required|trim");
        $this->form_validation->set_rules('irsaliye', 'irsaliye ', 'required|trim');

        $this->form_validation->set_message(
            [
                'required'  => '<b>{field}</b> alanı doldurulmalıdır',
            ]
        );

        // Form Validation Calistirilir..
        $validate = $this->form_validation->run();

        if ($validate) {
            $data2 = [
                'process_isim'  => 'girdikontrol',
                'parti_no'      => $this->input->post('parti_no'),
                'lot_no'        => '',
                'kutu_no'       => '',
                'tarih'         => date('Y-m-d H:i:s'),
            ];

            // kontrol numarası alma işlemi
            $insert2 = $this->kontrol_no_model->add($data2);
            $get_kontrol_id = get_kontrol_id($data2);

            // aktif kullanıcı bilgilerini al
            $user = get_active_user();

            $data = [
                'parti_no'      => $this->input->post('parti_no'),
                'tedarikci'     => $this->input->post('tedarikci'),
                'malzeme'       => $this->input->post('malzeme'),
                'irsaliye'      => $this->input->post('irsaliye'),
                'aciklama'      => $this->input->post('aciklama'),
                'kullanici'      => $this->input->post('kullanici'),
                'kontrol_no'    => $get_kontrol_id,
                'tarih'         => $this->input->post('tarih'),
            ];

            $insert = $this->girdi_kontrol_model->add($data);

            // TODO Alert sistemi eklenecek...
            if ($insert) {
                $alert = [
                    'title' => 'İşlem Başarılı',
                    'text' => 'Kayıt başarılı bir şekilde eklendi',
                    'type'  => 'success',
                ];
            } else {
                $alert = [
                    'title' => 'İşlem Başarısız',
                    'text' => 'Kayıt Ekleme sırasında bir problem oluştu',
                    'type'  => 'error',
                ];
            }

            // İşlemin Sonucunu Session'a yazma işlemi...
            $this->session->set_flashdata('alert', $alert);

            redirect(base_url('girdikontrol'));
        } else {
            $viewData = new stdClass();

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'add';
            $viewData->form_error = true;
            $viewData->parti_no = $parti_no;
            $viewData->tedarikci = $tedarikci;
            $viewData->malzeme = $malzeme;
            $viewData->irsaliye = $irsaliye;
            $viewData->kullanici = $kullanici;

            $this->load->view("{$viewData->viewFolder}/add/index", $viewData);
        }
    }

    public function update_form($id)
    {
        $viewData = new stdClass();

        /** Tablodan Verilerin Getirilmesi.. */
        $item = $this->girdi_kontrol_model->get(
            [
                'id'    => $id,
            ]
        );

        $viewData->tedarikciler = $this->tedarikciler_model->get_all(
            [
                'isActive'  => 1,
            ]
        );

        $viewData->malzemeler = $this->malzemeler_model->get_all(
            [
                'isActive'  => 1,
            ]
        );

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'update';
        $viewData->item = $item;
        $this->load->view("{$viewData->viewFolder}/update/index", $viewData);
    }

    public function update($id)
    {
        $this->load->library('form_validation');

        // Kurallar yazilir.

        $parti_no = $this->input->post('parti_no');
        $tedarikci = $this->input->post('tedarikci');
        $malzeme = $this->input->post('malzeme');
        $irsaliye = $this->input->post('irsaliye');
        $tarih = $this->input->post('tarih');
        $aciklama = $this->input->post('aciklama');
        $kullanici = $this->input->post('kullanici');

        $this->form_validation->set_rules('parti_no', 'Parti No', 'required|trim');
        // $this->form_validation->set_rules("tedarikci", "tedarikci", "required|trim");
        //$this->form_validation->set_rules("malzeme", "malzeme ", "required|trim");
        $this->form_validation->set_rules('irsaliye', 'irsaliye ', 'required|trim');

        $this->form_validation->set_message(
            [
                'required'  => '<b>{field}</b> alanı doldurulmalıdır',
            ]
        );

        // Form Validation Calistirilir..
        $validate = $this->form_validation->run();

        if ($validate) {
            $data = [
                'parti_no'      => $this->input->post('parti_no'),
                'tedarikci'     => $this->input->post('tedarikci'),
                'malzeme'       => $this->input->post('malzeme'),
                'irsaliye'      => $this->input->post('irsaliye'),
                'aciklama'      => $this->input->post('aciklama'),
                'kullanici'      => $this->input->post('kullanici'),
                'tarih'         => $this->input->post('tarih'),
            ];

            $update = $this->girdi_kontrol_model->update(['id' => $id], $data);

            // TODO Alert sistemi eklenecek...
            if ($update) {
                $alert = [
                    'title' => 'İşlem Başarılı',
                    'text' => 'Kayıt başarılı bir şekilde güncellendi',
                    'type'  => 'success',
                ];
            } else {
                $alert = [
                    'title' => 'İşlem Başarısız',
                    'text' => 'Kayıt Güncelleme sırasında bir problem oluştu',
                    'type'  => 'error',
                ];
            }

            // İşlemin Sonucunu Session'a yazma işlemi...
            $this->session->set_flashdata('alert', $alert);

            redirect(base_url('girdikontrol'));
        } else {
            $viewData = new stdClass();

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'update';
            $viewData->form_error = true;
            $viewData->parti_no = $parti_no;
            $viewData->tedarikci = $tedarikci;
            $viewData->malzeme = $malzeme;
            $viewData->irsaliye = $irsaliye;

            // Tablodan Verilerin Getirilmesi..
            $viewData->item = $this->girdi_kontrol_model->get(
                [
                    'id'    => $id,
                ]
            );

            $this->load->view("{$viewData->viewFolder}/update/index", $viewData);
        }
    }

    public function delete($id)
    {
        $delete = $this->kontrol_no_model->delete(
            [
                'id'    => $id,
            ]
        );

        // TODO Alert Sistemi Eklenecek...
        if ($delete) {
            $alert = [
                'title' => 'İşlem Başarılı',
                'text' => 'Kayıt başarılı bir şekilde silindi',
                'type'  => 'success',
            ];
        } else {
            $alert = [
                'title' => 'İşlem Başarısız',
                'text' => 'Kayıt silme sırasında bir problem oluştu',
                'type'  => 'error',
            ];
        }

        $this->session->set_flashdata('alert', $alert);
        redirect(base_url('kontrol_no'));
    }

    public function gorsel_new_form()
    {
        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'gorsel_add';

        $this->load->view("{$viewData->viewFolder}/gorsel_add/index", $viewData);
    }

    public function gorsel_save()
    {
    }

    public function gorsel_update_form($id)
    {
    }

    public function gorsel_update($id)
    {
    }

    public function gorsel_delete($id)
    {
    }

    public function olcum_new_form()
    {
        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'add';

        $this->load->view("{$viewData->viewFolder}/add/index", $viewData);
    }

    public function olcum_save()
    {
    }

    public function olcum_update_form($id)
    {
    }

    public function olcum_update($id)
    {
    }

    public function olcum_delete($id)
    {
    }

    public function export_excel()
    {
        // Excel export functionality
        $this->load->library('excel');

        // Get search parameters
        $search_process = $this->input->get('search_process');
        $search_parti = $this->input->get('search_parti');
        $search_date = $this->input->get('search_date');

        // Build where conditions
        $where = [];

        if (!empty($search_process)) {
            $where['process_isim'] = $search_process;
        }

        if (!empty($search_parti)) {
            $where['parti_no LIKE'] = '%' . $search_parti . '%';
        }

        if (!empty($search_date)) {
            $where['DATE(tarih)'] = $search_date;
        }

        // Get data
        $items = $this->kontrol_no_model->get_all($where, 'id DESC');

        // Simple CSV export as alternative
        $filename = 'kontrol_kayitlari_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Headers
        fputcsv($output, ['Kontrol No', 'Proses Türü', 'Parti No', 'Lot No', 'Kutu No', 'Tarih']);

        // Data rows
        foreach ($items as $item) {
            fputcsv($output, [
                $item->id,
                $item->process_isim,
                $item->parti_no,
                $item->lot_no,
                $item->kutu_no,
                $item->tarih,
            ]);
        }

        fclose($output);
    }

    public function view($id)
    {
        $viewData = new stdClass();

        // Get item details
        $item = $this->kontrol_no_model->get(['id' => $id]);

        if (!$item) {
            show_404();

            return;
        }

        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'view';
        $viewData->item = $item;

        $this->load->view("{$viewData->viewFolder}/view/index", $viewData);
    }
}
