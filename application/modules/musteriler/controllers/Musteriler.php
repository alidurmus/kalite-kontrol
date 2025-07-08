<?php

class Musteriler extends MY_Controller
{
    public $viewFolder = '';

    public function __construct()
    {
        parent::__construct();

        $this->viewFolder = 'musteriler';

        $this->load->model('musteriler/musteriler_model');

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

        $search_title = $this->input->get('search_title');
        $search_sorumlu = $this->input->get('search_sorumlu');

        $where = [];

        if ($search_title) {
            $where['title LIKE'] = '%' . $search_title . '%';
        }

        if ($search_sorumlu) {
            $where['sorumlu LIKE'] = '%' . $search_sorumlu . '%';
        }

        $config['base_url'] = base_url('musteriler/index');
        $config['total_rows'] = $this->musteriler_model->get_count($where);
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

        $items = $this->musteriler_model->get_limit(
            $where,
            $config['per_page'],
            $page
        );

        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'list';
        $viewData->items = $items;
        $viewData->search_title = $search_title;
        $viewData->search_sorumlu = $search_sorumlu;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function listele()
    {
        $viewData = new stdClass();

        /** Tablodan Verilerin Getirilmesi.. */
        $items = $this->musteriler_model->get_all(
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

        $viewData->musteriler = $this->musteriler_model->get_all();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'add';

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function save()
    {
        $this->load->library('form_validation');

        // Kurallar yazilir..

        $adi = $this->input->post('adi');
        $kodu = $this->input->post('kodu');

        $aciklama = $this->input->post('aciklama');

        $this->form_validation->set_rules('adi', 'adi ', 'required|trim');
        // $this->form_validation->set_rules("kodu", "kodu", "required|trim");
        //$this->form_validation->set_rules("malzeme", "malzeme ", "required|trim");
        $this->form_validation->set_rules('kodu', 'kodu ', 'required|trim');

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
                'adi'      => $this->input->post('adi'),
                'kodu'     => $this->input->post('kodu'),
                'aciklama'      => $this->input->post('aciklama'),
            ];

            $insert = $this->musteriler_model->add($data);

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

            redirect(base_url('musteriler'));
        } else {
            $viewData = new stdClass();

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'add';
            $viewData->form_error = true;
            $viewData->adi = $adi;
            $viewData->kodu = $kodu;
            $viewData->aciklama = $aciklama;

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function update_form($id)
    {
        $viewData = new stdClass();

        /** Tablodan Verilerin Getirilmesi.. */
        $item = $this->musteriler_model->get(
            [
                'id'    => $id,
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

        $adi = $this->input->post('adi');
        $kodu = $this->input->post('kodu');
        $aciklama = $this->input->post('aciklama');

        $this->form_validation->set_rules('adi', 'Adı', 'required|trim');
        $this->form_validation->set_rules('kodu', 'kodu', 'required|trim');

        $this->form_validation->set_message(
            [
                'required'  => '<b>{field}</b> alanı doldurulmalıdır',
            ]
        );

        // Form Validation Calistirilir..
        $validate = $this->form_validation->run();

        if ($validate) {
            $data = [
                'adi'      => $this->input->post('adi'),
                'kodu'     => $this->input->post('kodu'),
                'aciklama'      => $this->input->post('aciklama'),
            ];

            $update = $this->musteriler_model->update(['id' => $id], $data);

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

            redirect(base_url('musteriler'));
        } else {
            $viewData = new stdClass();

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'update';
            $viewData->form_error = true;
            $viewData->adi = $adi;
            $viewData->kodu = $kodu;
            $viewData->aciklama = $aciklama;

            // Tablodan Verilerin Getirilmesi..
            $viewData->item = $this->musteriler_model->get(
                [
                    'id'    => $id,
                ]
            );

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function delete($id)
    {
        $delete = $this->musteriler_model->delete(
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
        redirect(base_url('musteriler'));
    }
}
