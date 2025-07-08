<?php

class Girdikontrol extends MY_Controller
{
    public $viewFolder = '';

    public function __construct()
    {
        parent::__construct();

        $this->viewFolder = 'girdikontrol';

        $this->load->model('girdikontrol/girdi_kontrol_model');
        $this->load->model('tedarikciler/tedarikciler_model');
        $this->load->model('malzemeler/malzemeler_model');
        $this->load->model('kontrol_no/kontrol_no_model');
        $this->load->library('pagination');

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

        $search_tedarikci = $this->input->get('search_tedarikci');
        $search_malzeme = $this->input->get('search_malzeme');
        $search_parti_no = $this->input->get('search_parti_no');

        $where = [];

        if ($search_tedarikci) {
            $where['tedarikci LIKE'] = '%' . $search_tedarikci . '%';
        }
        if ($search_malzeme) {
            $where['malzeme LIKE'] = '%' . $search_malzeme . '%';
        }
        if ($search_parti_no) {
            $where['parti_no LIKE'] = '%' . $search_parti_no . '%';
        }

        $config = [];
        $config['base_url'] = base_url('girdikontrol/index');
        $config['total_rows'] = $this->girdi_kontrol_model->get_count($where);
        $config['per_page'] = 100;
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = true;
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);

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

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $viewData->links = $this->pagination->create_links();
        $items = $this->girdi_kontrol_model->get_limit(
            $where,
            $config['per_page'],
            $page
        );

        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'list';
        $viewData->items = $items;
        $viewData->search_tedarikci = $search_tedarikci;
        $viewData->search_malzeme = $search_malzeme;
        $viewData->search_parti_no = $search_parti_no;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function listele()
    {
        $viewData = new stdClass();
        $config = [];
        $config['base_url'] = base_url() . 'girdikontrol';

        $config['total_rows'] = $this->girdi_kontrol_model->get_count();

        $config['per_page'] = 100;

        $config['uri_segment'] = 2;
        $config['num_links'] = 5;

        //$config["use_page_numbers"] = TRUE;
        //$config["page_query_string"] = TRUE;
        //$config["reuse_query_string"] = FALSE;
        $config['prefix'] = '';
        $config['suffix'] = '';
        //$config["use_global_url_suffix"] = FALSE;

        $config['full_tag_open'] = "<nav> <ul class='pagination'>";
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'İlk';
        $config['first_tag_open'] = "<li class='page-item'>";
        $config['first_tag_close'] = '</li>';
        $config['first_url'] = '';
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

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        $links = $this->pagination->create_links();

        $items = $this->girdi_kontrol_model->get_limit([], $config['per_page'], $page);

        // Tablodan Verilerin Getirilmesi..
        //  $items = $this->girdi_kontrol_model->get_all(
        //     array(), "id ASC"
        //  );
        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'list';
        $viewData->items = $items;
        $viewData->links = $links;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function new_form()
    {
        $viewData = new stdClass();

        $viewData->tedarikciler = $this->tedarikciler_model->get_all();
        $viewData->malzemeler = $this->malzemeler_model->get_all();

        // ölçüm tablosu json açılarak veri olarak al
        //malzeme seçme işlemi yapıldıktan sonra alttaki yorumu kaldır
        //$viewData->json = json_decode($malzeme->olcum);

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'add';

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
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

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
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
            ]
        );

        $viewData->malzemeler = $this->malzemeler_model->get_all(
            [
            ]
        );

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'update';
        $viewData->item = $item;
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
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

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function delete($id)
    {
        $delete = $this->girdi_kontrol_model->delete(
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
                'title' => 'İşlem Başarılı',
                'text' => 'Kayıt silme sırasında bir problem oluştu',
                'type'  => 'error',
            ];
        }

        $this->session->set_flashdata('alert', $alert);
        redirect(base_url('girdikontrol'));
    }

    public function gorsel_new_form()
    {
        $viewData = new stdClass();
        
        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'gorsel_add';

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
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
        $viewData = new stdClass();
        
        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'add';

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
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
}
