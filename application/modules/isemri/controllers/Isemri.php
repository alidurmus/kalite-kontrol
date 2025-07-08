<?php

class Isemri extends MY_Controller
{
    public $viewFolder = '';

    public function __construct()
    {
        parent::__construct();

        $this->viewFolder = 'isemri';

        $this->load->model('isemri/isemri_model');

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

        $search_isemri_no = $this->input->get('search_isemri_no');
        $search_stok_kodu = $this->input->get('search_stok_kodu');
        $search_parti_no = $this->input->get('search_parti_no');

        $where = [];

        if ($search_isemri_no) {
            $where['isemri_no LIKE'] = '%' . $search_isemri_no . '%';
        }
        if ($search_stok_kodu) {
            $where['stok_kodu LIKE'] = '%' . $search_stok_kodu . '%';
        }
        if ($search_parti_no) {
            $where['parti_no LIKE'] = '%' . $search_parti_no . '%';
        }

        $config['base_url'] = base_url('isemri/index');
        $config['total_rows'] = $this->isemri_model->get_count($where);
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = true;
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);

        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'İlk';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Son';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-chevron-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-chevron-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $viewData->links = $this->pagination->create_links();

        $items = $this->isemri_model->get_limit(
            $where,
            $config['per_page'],
            $page
        );

        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'list';
        $viewData->items = $items;
        $viewData->search_isemri_no = $search_isemri_no;
        $viewData->search_stok_kodu = $search_stok_kodu;
        $viewData->search_parti_no = $search_parti_no;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function listele()
    {
        $viewData = new stdClass();

        /** Tablodan Verilerin Getirilmesi.. */
        $items = $this->isemri_model->get_all(
            [],
            'id ASC'
        );
        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'list';
        $viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function new_form()
    {
        $viewData = new stdClass();

        $viewData->isemri = $this->isemri_model->get_all();

        $json = '
        {"isemri":
            {
                "urun1":{"adi":"urun1","adet":"0"},
                "urun2":{"adi":"urun2","adet":"0"},
                "urun3":{"adi":"urun3","adet":"0"},
                "urun4":{"adi":"urun4","adet":"0"},
                "urun5":{"adi":"urun5","adet":"0"},
                "urun6":{"adi":"urun6","adet":"0"},
                "urun7":{"adi":"urun7","adet":"0"},
                "urun8":{"adi":"urun8","adet":"0"},
                "urun9":{"adi":"urun9","adet":"0"},
                "urun10":{"adi":"urun10","adet":"0"}
            }
        }';

        $viewData->json = json_decode($json);

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'add';

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function save()
    {
        $this->load->library('form_validation');

        // Kurallar yazilir..

        $lot = $this->input->post('lot');
        $musteri = $this->input->post('musteri');
        $siparis_no = $this->input->post('siparis_no');
        $uretim_tarihi = $this->input->post('uretim_tarihi');
        $sevk_tarihi = $this->input->post('sevk_tarihi');
        $tarih = $this->input->post('tarih');

        $urun = json_encode($this->input->post('form'));
        $aciklama = $this->input->post('aciklama');

        $this->form_validation->set_rules('lot', 'lot ', 'required|trim');

        $this->form_validation->set_rules('musteri', 'musteri ', 'required|trim');

        $this->form_validation->set_message(
            [
                'required'  => '<b>{field}</b> alanı doldurulmalıdır',
            ]
        );

        // Form Validation Calistirilir..
        $validate = $this->form_validation->run();

        if ($validate) {
            // aktif kullanıcı bilgilerini al
            $user = get_active_user();
            $data = [
                'lot'               =>  $this->input->post('lot'),
                'musteri'           => $this->input->post('musteri'),
                'siparis_no'        => $this->input->post('siparis_no'),
                'uretim_tarihi'     => $this->input->post('uretim_tarihi'),
                'sevk_tarihi'     => $this->input->post('sevk_tarihi'),
                'tarih'             => $this->input->post('tarih'),
                'json'              => $urun,
                'aciklama'          => $this->input->post('aciklama'),
            ];

            $insert = $this->isemri_model->add($data);

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

            redirect(base_url('isemri'));
        } else {
            $viewData = new stdClass();

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'add';
            $viewData->form_error = true;
            $viewData->lot = $lot;
            $viewData->musteri = $musteri;
            $viewData->siparis_no = $siparis_no;
            $viewData->uretim_tarihi = $uretim_tarihi;
            $viewData->sevk_tarihi = $sevk_tarihi;
            $viewData->tarih = $tarih;
            $viewData->json = json_decode($urun);
            $viewData->aciklama = $aciklama;

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function update_form($id)
    {
        $viewData = new stdClass();

        /** Tablodan Verilerin Getirilmesi.. */
        $item = $this->isemri_model->get(
            [
                'id'    => $id,
            ]
        );

        // ölçüm tablosu json açılarak veri olarak al
        $viewData->json = json_decode($item->json);

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
        $lot = $this->input->post('lot');
        $musteri = $this->input->post('musteri');
        $siparis_no = $this->input->post('siparis_no');
        $uretim_tarihi = $this->input->post('uretim_tarihi');
        $sevk_tarihi = $this->input->post('sevk_tarihi');
        $tarih = $this->input->post('tarih');

        $urun = json_encode($this->input->post('form'));
        $aciklama = $this->input->post('aciklama');

        $this->form_validation->set_rules('lot', 'lot ', 'required|trim');

        $this->form_validation->set_rules('musteri', 'musteri ', 'required|trim');

        $this->form_validation->set_message(
            [
                'required'  => '<b>{field}</b> alanı doldurulmalıdır',
            ]
        );

        // Form Validation Calistirilir..
        $validate = $this->form_validation->run();

        if ($validate) {
            $data = [
                'lot'               =>  $this->input->post('lot'),
                'musteri'           => $this->input->post('musteri'),
                'siparis_no'        => $this->input->post('siparis_no'),
                'uretim_tarihi'     => $this->input->post('uretim_tarihi'),
                'sevk_tarihi'     => $this->input->post('sevk_tarihi'),
                'tarih'             => $this->input->post('tarih'),
                'json'              => $urun,
                'aciklama'          => $this->input->post('aciklama'),
            ];

            $update = $this->isemri_model->update(['id' => $id], $data);

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

            redirect(base_url('isemri'));
        } else {
            $viewData = new stdClass();

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'update';
            $viewData->form_error = true;
            $viewData->lot = $lot;
            $viewData->musteri = $musteri;
            $viewData->siparis_no = $siparis_no;
            $viewData->uretim_tarihi = $uretim_tarihi;
            $viewData->sevk_tarihi = $sevk_tarihi;
            $viewData->tarih = $tarih;
            $viewData->json = json_decode($urun);
            $viewData->aciklama = $aciklama;
            // Tablodan Verilerin Getirilmesi..
            $viewData->item = $this->isemri_model->get(
                [
                    'id'    => $id,
                ]
            );

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function delete($id)
    {
        $delete = $this->isemri_model->delete(
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
        redirect(base_url('isemri'));
    }
}
