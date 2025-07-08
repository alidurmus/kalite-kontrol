<?php

class Urunler extends MY_Controller
{
    public $viewFolder = '';

    public function __construct()
    {
        parent::__construct();

        $this->viewFolder = 'urunler';

        $this->load->model('urunler/urunler_model');

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
        $search_desc = $this->input->get('search_desc');

        $where = [];

        if ($search_title) {
            $where['title LIKE'] = '%' . $search_title . '%';
        }

        if ($search_desc) {
            $where['description LIKE'] = '%' . $search_desc . '%';
        }

        $config['base_url'] = base_url('urunler/index');
        $config['total_rows'] = $this->urunler_model->get_count($where);
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

        $items = $this->urunler_model->get_limit(
            $where,
            $config['per_page'],
            $page
        );

        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'list';
        $viewData->items = $items;
        $viewData->search_title = $search_title;
        $viewData->search_desc = $search_desc;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function listele()
    {
        $viewData = new stdClass();

        /** Tablodan Verilerin Getirilmesi.. */
        $items = $this->urunler_model->get_all(
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

        $viewData->urunler = $this->urunler_model->get_all();

        $json = '
        {"olcum":
            {
                "k1":{"adi":"K1","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G1","gorsel":"3"},
                "k2":{"adi":"K2","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G2","gorsel":"1"},
                "k3":{"adi":"K3","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G3","gorsel":"2"},
                "k4":{"adi":"K4","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G4","gorsel":"3"},
                "k5":{"adi":"K5","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G5","gorsel":"3"},
                "k6":{"adi":"K6","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G6","gorsel":"3"},
                "k7":{"adi":"K7","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G7","gorsel":"2"},
                "k8":{"adi":"K8","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G8","gorsel":"3"},
                "k9":{"adi":"K9","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G9","gorsel":"2"},
                "k10":{"adi":"K10","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G10","gorsel":"1"},
                "k11":{"adi":"K11","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G11","gorsel":"1"},
                "k12":{"adi":"K12","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G12","gorsel":"3"},
                "k13":{"adi":"K13","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G13","gorsel":"1"},
                "k14":{"adi":"K14","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G14","gorsel":"3"},
                "k15":{"adi":"K15","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G15","gorsel":"2"},
                "k16":{"adi":"K16","olcu":"0","tolerans":"0","alt_limit":"0","ust_limit":"0","olcum":"0","sonuc":"","kontrol_noktasi":"G16","gorsel":"3"}
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

        $adi = $this->input->post('adi');
        $kodu = $this->input->post('kodu');
        $olcum = json_encode($this->input->post('form'));
        $aciklama = $this->input->post('aciklama');

        $input_name = 'pdf';
        $path = './uploads/pdf/';

        $upload = fn_upload_file($input_name, $path, $types = 'gif|jpg|jpeg|png|pdf');

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
                'olcum'         => $olcum,
                'aciklama'      => $this->input->post('aciklama'),
                'isActive'     => 1,
                'pdf'      => $upload['file_name'],
            ];

            $insert = $this->urunler_model->add($data);

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

            redirect(base_url('urunler'));
        } else {
            $viewData = new stdClass();

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'add';
            $viewData->form_error = true;
            $viewData->adi = $adi;
            $viewData->kodu = $kodu;
            $viewData->json = json_decode($olcum);
            $viewData->aciklama = $aciklama;

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function update_form($id)
    {
        $viewData = new stdClass();

        /** Tablodan Verilerin Getirilmesi.. */
        $item = $this->urunler_model->get(
            [
                'id'    => $id,
            ]
        );

        // ölçüm tablosu json açılarak veri olarak al
        $viewData->json = json_decode($item->olcum);

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
        $olcum = json_encode($this->input->post('form'));
        $pdf_eski = $this->input->post('pdf_eski');

        $input_name = 'pdf';
        $path = './uploads/pdf/';

        $upload = fn_upload_file($input_name, $path, $types = 'gif|jpg|jpeg|png|pdf');

        if ($upload == false) {
            $upload['file_name'] = $pdf_eski;
        }

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
                'adi'           => $this->input->post('adi'),
                'kodu'          => $this->input->post('kodu'),
                'olcum'         => $olcum,
                'aciklama'      => $this->input->post('aciklama'),
                'pdf'      => $upload['file_name'],
            ];

            $update = $this->urunler_model->update(['id' => $id], $data);

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

            redirect(base_url('urunler'));
        } else {
            $viewData = new stdClass();

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'update';
            $viewData->form_error = true;
            $viewData->adi = $adi;
            $viewData->kodu = $kodu;
            $viewData->json = json_decode($olcum);
            $viewData->aciklama = $aciklama;

            // Tablodan Verilerin Getirilmesi..
            $viewData->item = $this->urunler_model->get(
                [
                    'id'    => $id,
                ]
            );

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function delete($id)
    {
        $delete = $this->urunler_model->delete(
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
        redirect(base_url('urunler'));
    }
}
