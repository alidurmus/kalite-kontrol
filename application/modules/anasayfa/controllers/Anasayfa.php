<?php

class Anasayfa extends MY_Controller
{
    public $viewFolder = '';

    public function __construct()
    {
        parent::__construct();

        $this->viewFolder = 'anasayfa';

        if (!get_active_user()) {
            redirect(base_url('login'));
        }

        $this->load->model('girdikontrol/girdi_kontrol_model');
        $this->load->model('proseskontrol/proses_kontrol_model');
        $this->load->model('finalkontrol/final_kontrol_model');

        $this->load->model('tedarikciler/tedarikciler_model');
        $this->load->model('malzemeler/malzemeler_model');
        $this->load->model('urunler/urunler_model');

        $this->load->model('kontrol_no/kontrol_no_model');

        $this->load->model('users/user_model');
        $this->load->model('sonuc_secim/sonuc_secim_model');
        $this->load->library('pagination');
        $this->load->model('hpk/hpk_model');
        $this->load->model('proses_categories/proses_category_model');
        
        // PERFORMANCE OPTIMIZATION: Load cache library
        $this->load->library('performance_cache');
    }

    public function index()
    {
        $viewData = new stdClass();

        // PERFORMANCE OPTIMIZATION: Use cached dashboard stats
        $viewData->dashboard_stats = $this->performance_cache->get_dashboard_stats();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'giris';
        //$viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function kalite()
    {
        $viewData = new stdClass();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'kalite';
        //$viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function planlama()
    {
        $viewData = new stdClass();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'planlama';
        //$viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function arge()
    {
        $viewData = new stdClass();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'arge';
        //$viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function yonetim()
    {
        $viewData = new stdClass();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'yonetim';
        //$viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function malzeme()
    {
        $viewData = new stdClass();

        $viewData->malzemeler = $this->malzemeler_model->get_all();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'malzeme';
        //$viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function urun()
    {
        $viewData = new stdClass();

        $viewData->urunler = $this->urunler_model->get_all();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'urun';
        //$viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function urun2()
    {
        $viewData = new stdClass();

        $viewData->urunler = $this->urunler_model->get_all();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'urun';
        //$viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index2", $viewData);
    }

    public function girdikontrol()
    {
        $viewData = new stdClass();

        $config = [];
        $config['base_url'] = base_url() . 'anasayfa/girdikontrol';
        $config['total_rows'] = $this->girdi_kontrol_model->get_count();
        $config['per_page'] = 100;

        $config['uri_segment'] = 2;
        $config['num_links'] = 5;

        //$config["use_page_numbers"] = TRUE;
        //$config["page_query_string"] = TRUE;
        //$config["reuse_query_string"] = FALSE;
        $config['prefix'] = '';
        $config['suffix'] = '';
        $config['use_global_url_suffix'] = true;

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

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        echo  $config['total_rows'];

        $links = $this->pagination->create_links();

        $items = $this->girdi_kontrol_model->get_limit([], $config['per_page'], $page);

        // Tablodan Verilerin Getirilmesi..
        //$items = $this->girdi_kontrol_model->listele();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'girdikontrol';
        $viewData->items = $items;
        $viewData->links = $links;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function girdikontrol_ekle()
    {
        $viewData = new stdClass();

        $viewData->sonuc_secim = $this->sonuc_secim_model->get_all();
        $viewData->tedarikciler = $this->tedarikciler_model->get_all();

        // formdan gelen bilgilere göre ölçüm tablosunu getir
        $malzeme_id = $this->input->post('malzeme');

        $malzeme = $this->malzemeler_model->get(
            [
                'id'    => $malzeme_id,
            ]
        );
        // ölçüm tablosu json açılarak veri olarak al
        $viewData->json = json_decode($malzeme->olcum);
        $viewData->malzeme = $malzeme;

        $viewData->user = get_active_user();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'girdikontrol_ekle';

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function girdikontrol_kaydet()
    {
        $this->load->library('form_validation');

        // Kurallar yazilir..

        $parti_no = $this->input->post('parti_no');
        $hpk = $this->input->post('hpk');
        $tedarikci = $this->input->post('tedarikci');
        $malzeme = $this->input->post('malzeme');
        $irsaliye = $this->input->post('irsaliye');
        $tarih = $this->input->post('tarih');
        $aciklama = $this->input->post('aciklama');
        $kullanici = $this->input->post('kullanici');
        $sonuc = $this->input->post('sonuc');
        $olcum_data = $this->input->post('form'); // Migration sonrası JSON dosyaya kaydedilecek

        $this->form_validation->set_rules('parti_no', 'Parti No', 'required|trim');
        $this->form_validation->set_rules('tedarikci', 'Tedarikçi', 'required|trim');
        $this->form_validation->set_rules('malzeme', 'Malzeme', 'required|trim');

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
                'parti_no'      => $parti_no,
                'lot_no'        => '',
                'kutu_no'       => '',
                'tarih'         => date('Y-m-d H:i:s'),
            ];

            // kontrol numarası alma işlemi
            $get_kontrol_id = get_kontrol_id($data2);

            // aktif kullanıcı bilgilerini al
            $user = get_active_user();

            $data = [
                'parti_no'      => $this->input->post('parti_no'),
                'hpk'           => $this->input->post('hpk'),
                'tedarikci'     => $this->input->post('tedarikci'),
                'malzeme'       => $this->input->post('malzeme'),
                'irsaliye'      => $this->input->post('irsaliye'),
                'aciklama'      => $this->input->post('aciklama'),
                'kullanici'     => $user->id,
                'sonuc'         => $this->input->post('sonuc'),
                'kontrol_no'    => $get_kontrol_id,
                'tarih'         => $this->input->post('tarih'),
                // olcum alanı kaldırıldı - JSON dosyaya kaydedilecek
            ];

            // Migration sonrası save_with_measurement kullanılıyor (add yerine)
            $insert = $this->girdi_kontrol_model->save_with_measurement($data, $olcum_data);

            // TODO: Alert sistemi eklenecek...
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
            $viewData->subViewFolder = 'girdikontrol_ekle';
            $viewData->form_error = true;
            $viewData->parti_no = $parti_no;
            $viewData->hpk = $hpk;
            $viewData->tedarikci = $tedarikci;
            $viewData->malzeme = $malzeme;
            $viewData->irsaliye = $irsaliye;
            $viewData->json = (object)$olcum_data; // Migration sonrası array'den object'e çevir
            $viewData->kullanici = $kullanici;
            $viewData->sonuc = $sonuc;

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function girdikontrol_duzenle($id)
    {
        $viewData = new stdClass();
        // Tablodan Verilerin Getirilmesi..
        $viewData->sonuc_secim = $this->sonuc_secim_model->get_all();

        $item = $this->girdi_kontrol_model->get_with_measurement(
            [
                'id'    => $id,
            ]
        );

        $viewData->tedarikciler = $this->tedarikciler_model->get_all();

        // ölçüm tablosu JSON dosyasından yüklenir (migration sonrası)
        $viewData->json = $item->olcum_data ? (object)$item->olcum_data : null;

        // seçilen malzemenin bilgilerini getir
        $malzeme = $this->malzemeler_model->get(
            [
                'id'    => $item->malzeme,
            ]
        );
        $viewData->malzeme = $malzeme;

        $viewData->user = get_active_user();

        //die();
        // var_dump($viewData->json);
        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'girdikontrol_duzenle';
        $viewData->item = $item;
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function girdikontrol_sil($id)
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

    public function girdikontrol_guncelle($id)
    {
        $this->load->library('form_validation');

        // Kurallar yazilir..

        $parti_no = $this->input->post('parti_no');
        $hpk = $this->input->post('hpk');
        $tedarikci = $this->input->post('tedarikci');
        $malzeme = $this->input->post('malzeme');
        $irsaliye = $this->input->post('irsaliye');
        //$tarih = $this->input->post("tarih");
        $aciklama = $this->input->post('aciklama');
        $kullanici = $this->input->post('kullanici');
        $sonuc = $this->input->post('sonuc');
        $olcum_data = $this->input->post('form'); // Migration sonrası JSON dosyaya kaydedilecek

        $this->form_validation->set_rules('parti_no', 'Parti No', 'required|trim');
        $this->form_validation->set_rules('irsaliye', 'irsaliye ', 'required|trim');

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
                'parti_no'      => $this->input->post('parti_no'),
                'hpk'      => $this->input->post('hpk'),
                'tedarikci'     => $this->input->post('tedarikci'),
                'malzeme'       => $this->input->post('malzeme'),
                'irsaliye'      => $this->input->post('irsaliye'),
                'aciklama'      => $this->input->post('aciklama'),
                'sonuc'      => $this->input->post('sonuc'),
                'kullanici'      => $user->id,
                //"tarih"         => $this->input->post("tarih"),
                // olcum alanı kaldırıldı - JSON dosyaya kaydedilecek
            ];
            
            // Migration sonrası save_with_measurement kullanılıyor
            $update = $this->girdi_kontrol_model->save_with_measurement($data, $olcum_data, $id);
            
            // TODO: Alert sistemi eklenecek...
            if ($update) {
                $alert = [
                    'title' => 'İşlem Başarılı',
                    'text' => 'Kayıt başarılı bir şekilde güncellendi',
                    'type'  => 'success',
                ];
            } else {
                $alert = [
                    'title' => 'İşlem Başarısız',
                    'text' => 'Kayıt güncellendi sırasında bir problem oluştu',
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
            $viewData->subViewFolder = 'anasayfa/girdikontrol_duzenle';
            $viewData->form_error = true;
            $viewData->parti_no = $parti_no;
            $viewData->hpk = $hpk;
            $viewData->tedarikci = $tedarikci;
            $viewData->malzeme = $malzeme;
            $viewData->irsaliye = $irsaliye;
            $viewData->json = (object)$olcum_data; // Migration sonrası array'den object'e çevir
            $viewData->kullanici = $kullanici;
            $viewData->sonuc = $sonuc;

            // Tablodan Verilerin Getirilmesi..
            $viewData->item = $this->girdi_kontrol_model->get_with_measurement(
                [
                    'id'    => $id,
                ]
            );

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function proseskontrol()
    {
        $viewData = new stdClass();
        /** Tablodan Verilerin Getirilmesi.. */
        $items = $this->proses_kontrol_model->listele();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'proseskontrol';
        $viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function proseskontrol_ekle()
    {
        $viewData = new stdClass();

        $viewData->sonuc_secim = $this->sonuc_secim_model->get_all();

        // formdan gelen bilgilere göre olcum tablosunu getir
        $urun_id = $this->input->post('urun');

        $urun = $this->urunler_model->get(
            [
                'id'    => $urun_id,
            ]
        );
        // ölçüm tablosu json açılarak veri olarak al
        $viewData->json = json_decode($urun->olcum);

        $viewData->urun = $urun;

        $viewData->user = get_active_user();

        //die();
        // var_dump($viewData->json);
        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'proseskontrol_ekle';

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function proseskontrol_kaydet()
    {
        $this->load->library('form_validation');

        // Kurallar yazilir..

        $parti_no = $this->input->post('parti_no');
        $urun = $this->input->post('urun');
        $lot = $this->input->post('lot');
        $tarih = $this->input->post('tarih');
        $aciklama = $this->input->post('aciklama');
        $kullanici = $this->input->post('kullanici');
        $sonuc = $this->input->post('sonuc');
        $olcum_data = $this->input->post('form'); // Migration sonrası JSON dosyaya kaydedilecek
        $hpk = $this->input->post('hpk');
        $klips_hpk = $this->input->post('klips_hpk');
        $buji_braket_lot = $this->input->post('buji_braket_lot');
        
        $this->form_validation->set_rules('parti_no', 'Parti No', 'required|trim');
        $this->form_validation->set_rules('lot', 'lot ', 'required|trim');
        $this->form_validation->set_message(
            [
                'required'  => '<b>{field}</b> alanı doldurulmalıdır',
            ]
        );

        // Form Validation Calistirilir..
        $validate = $this->form_validation->run();

        if ($validate) {
            $data2 = [
                'process_isim'  => 'proseskontrol',
                'parti_no'      => $parti_no,
                'lot_no'        => $lot,
                'kutu_no'       => '',
                'tarih'         => date('Y-m-d H:i:s'),
            ];

            // kontrol numarası alma işlemi
            $get_kontrol_id = get_kontrol_id($data2);

            // aktif kullanıcı bilgilerini al
            $user = get_active_user();

            $data = [
                'parti_no'          => $this->input->post('parti_no'),
                'lot'               => $this->input->post('lot'),
                'urun'              => $this->input->post('urun'),
                'aciklama'          => $this->input->post('aciklama'),
                'kullanici'         => $user->id,
                'sonuc'             => $this->input->post('sonuc'),
                'hpk'               => $this->input->post('hpk'),
                'klips_hpk'         => $this->input->post('klips_hpk'),
                'buji_braket_lot'   => $this->input->post('buji_braket_lot'),
                'kontrol_no'        => $get_kontrol_id,
                'tarih'             => $this->input->post('tarih'),
                // olcum alanı kaldırıldı - JSON dosyaya kaydedilecek
            ];

            // Migration sonrası save_with_measurement kullanılıyor (add yerine)
            $insert = $this->proses_kontrol_model->save_with_measurement($data, $olcum_data);

            // TODO: Alert sistemi eklenecek...
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

            redirect(base_url('proseskontrol'));
        } else {
            $viewData = new stdClass();

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'proseskontrol_ekle';
            $viewData->form_error = true;
            $viewData->parti_no = $parti_no;
            $viewData->lot = $lot;
            $viewData->urun = $urun;
            $viewData->json = (object)$olcum_data; // Migration sonrası array'den object'e çevir
            $viewData->kullanici = $kullanici;
            $viewData->sonuc = $sonuc;

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function proseskontrol_duzenle($id)
    {
        $viewData = new stdClass();
        /** Tablodan Verilerin Getirilmesi.. */
        $item = $this->proses_kontrol_model->get_with_measurement(
            [
                'id'    => $id,
            ]
        );

        $viewData->sonuc_secim = $this->sonuc_secim_model->get_all();

        // ölçüm tablosu JSON dosyasından yüklenir (migration sonrası)
        $viewData->json = $item->olcum_data ? (object)$item->olcum_data : null;

        // $viewData->hpk = json_decode($item->hpk);
        //var_dump(json_decode($item->hpk, true));

        //  $viewData->p_lot = json_decode($item->p_lot_no);
        //var_dump(json_decode($item->p_lot_no, true));

        // seçilen urunnin bilgilerini getir
        $urun = $this->urunler_model->get(
            [
                'id'    => $item->urun,
            ]
        );
        $viewData->urun = $urun;

        // hpk bilgilerini getir
        $hpk = $this->hpk_model->get_all(
            [
                'main_id'    => $id,
            ]
        );
        //var_dump($hpk);
        $viewData->hpk = $hpk;

        $proses_categories = $this->proses_category_model->get_id(
            [
                'main_id'    => $id,
            ]
        );

        $viewData->proses_categories = $proses_categories;

        $viewData->user = get_active_user();

        //die();
        // var_dump($viewData->json);
        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'proseskontrol_duzenle';
        $viewData->item = $item;
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function proseskontrol_sil($id)
    {
        $delete = $this->proses_kontrol_model->delete(
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
        redirect(base_url('proseskontrol'));
    }

    public function proseskontrol_guncelle($id)
    {
        $this->load->library('form_validation');

        // Kurallar yazilir..

        $parti_no = $this->input->post('parti_no');
        $lot = $this->input->post('lot');
        $urun = $this->input->post('urun');
        //$tarih = $this->input->post("tarih");
        $aciklama = $this->input->post('aciklama');
        $kullanici = $this->input->post('kullanici');
        $sonuc = $this->input->post('sonuc');
        $olcum_data = $this->input->post('form'); // Migration sonrası JSON dosyaya kaydedilecek
        $hpk = $this->input->post('hpk');
        $klips_hpk = $this->input->post('klips_hpk');
        $buji_braket_lot = $this->input->post('buji_braket_lot');

        $this->form_validation->set_rules('parti_no', 'Parti No', 'required|trim');
        $this->form_validation->set_rules('lot', 'lot ', 'required|trim');

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
                'parti_no'          => $this->input->post('parti_no'),
                'lot'               => $this->input->post('lot'),
                'urun'              => $this->input->post('urun'),
                'aciklama'          => $this->input->post('aciklama'),
                'sonuc'             => $this->input->post('sonuc'),
                'kullanici'         => $user->id,
                //"tarih"           => $thi,s->input->post("tarih"),
                // olcum alanı kaldırıldı - JSON dosyaya kaydedilecek
                'hpk'               => $this->input->post('hpk'),
                'klips_hpk'         => $this->input->post('klips_hpk'),
                'buji_braket_lot'   => $this->input->post('buji_braket_lot'),
            ];
            
            // Migration sonrası save_with_measurement kullanılıyor
            $update = $this->proses_kontrol_model->save_with_measurement($data, $olcum_data, $id);
            
            // TODO: Alert sistemi eklenecek...
            if ($update) {
                $alert = [
                    'title' => 'İşlem Başarılı',
                    'text' => 'Kayıt başarılı bir şekilde güncellendi',
                    'type'  => 'success',
                ];
            } else {
                $alert = [
                    'title' => 'İşlem Başarısız',
                    'text' => 'Kayıt güncellendi sırasında bir problem oluştu',
                    'type'  => 'error',
                ];
            }

            // İşlemin Sonucunu Session'a yazma işlemi...
            $this->session->set_flashdata('alert', $alert);

            redirect(base_url('proseskontrol'));
        } else {
            $viewData = new stdClass();

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'anasayfa/proseskontrol_duzenle';
            $viewData->form_error = true;
            $viewData->parti_no = $parti_no;
            $viewData->lot = $lot;
            $viewData->urun = $urun;
            $viewData->json = (object)$olcum_data; // Migration sonrası array'den object'e çevir
            $viewData->kullanici = $kullanici;
            $viewData->sonuc = $sonuc;
            $viewData->klips_hpk = $klips_hpk;
            $viewData->hpk = $hpk;
            // Tablodan Verilerin Getirilmesi..
            $viewData->item = $this->proses_kontrol_model->get_with_measurement(
                [
                    'id'    => $id,
                ]
            );

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function finalkontrol()
    {
        $viewData = new stdClass();
        
        /** Tablodan Verilerin Getirilmesi.. */
        $items = $this->final_kontrol_model->listele();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'finalkontrol';
        $viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    /**
     * Final kontrol için ürün seçim sayfası (GET request)
     */
    public function finalkontrol_yeni()
    {
        $viewData = new stdClass();
        
        // Ürünleri getir
        $viewData->urunler = $this->urunler_model->get_all();
        
        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'urun';

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    /**
     * Final kontrol form sayfası (POST request - ürün seçimi sonrası)
     */
    public function finalkontrol_ekle()
    {
        // CSRF kontrolü
        if (!$this->input->post()) {
            // GET request ise ürün seçim sayfasına yönlendir
            redirect(base_url('anasayfa/finalkontrol_yeni'));
            return;
        }

        $viewData = new stdClass();
        $viewData->sonuc_secim = $this->sonuc_secim_model->get_all();
        
        // formdan gelen bilgilere göre olcum tablosunu getir
        $urun_id = $this->input->post('urun');

        if (!$urun_id) {
            // Ürün seçilmemişse geri yönlendir
            $this->session->set_flashdata('alert', [
                'title' => 'Hata',
                'text' => 'Lütfen bir ürün seçiniz',
                'type'  => 'error',
            ]);
            redirect(base_url('anasayfa/finalkontrol_yeni'));
            return;
        }

        $urun = $this->urunler_model->get([
            'id' => $urun_id,
        ]);

        if (!$urun) {
            $this->session->set_flashdata('alert', [
                'title' => 'Hata', 
                'text' => 'Seçilen ürün bulunamadı',
                'type'  => 'error',
            ]);
            redirect(base_url('anasayfa/finalkontrol_yeni'));
            return;
        }
        
        // ölçüm tablosu json açılarak veri olarak al
        $viewData->json = json_decode($urun->olcum);
        $viewData->urun = $urun;
        $viewData->user = get_active_user();

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'finalkontrol_ekle';

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function finalkontrol_kaydet()
    {
        $this->load->library('form_validation');

        // Kurallar yazilir..

        $kutu_no = $this->input->post('kutu_no');
        $lot = $this->input->post('lot');
        $urun = $this->input->post('urun');
        $tarih = $this->input->post('tarih');
        $aciklama = $this->input->post('aciklama');
        $kullanici = $this->input->post('kullanici');
        $sonuc = $this->input->post('sonuc');
        $olcum_data = $this->input->post('form'); // Migration sonrası JSON dosyaya kaydedilecek

        $klips_montaj_lot = $this->input->post('klips_montaj_lot');
        $brulor_lot = $this->input->post('brulor_lot');

        $this->form_validation->set_rules('kutu_no', 'Kutu No', 'required|trim');
        $this->form_validation->set_rules('lot', 'lot ', 'required|trim');

        $this->form_validation->set_message(
            [
                'required'  => '<b>{field}</b> alanı doldurulmalıdır',
            ]
        );

        // Form Validation Calistirilir..
        $validate = $this->form_validation->run();

        if ($validate) {
            $data2 = [
                'process_isim'  => 'finalkontrol',
                'parti_no'      => '',
                'lot_no'        =>  $lot,
                'kutu_no'       => $kutu_no,
                'tarih'         => date('Y-m-d H:i:s'),
            ];

            // kontrol numarası alma işlemi
            $get_kontrol_id = get_kontrol_id($data2);

            // aktif kullanıcı bilgilerini al
            $user = get_active_user();

            $data = [
                'kutu_no'      => $this->input->post('kutu_no'),
                'lot'     => $this->input->post('lot'),
                'urun'       => $this->input->post('urun'),
                'aciklama'      => $this->input->post('aciklama'),
                'kullanici'      => $user->id,
                'sonuc'      => $this->input->post('sonuc'),
                'kontrol_no'    => $get_kontrol_id,
                'tarih'         => $this->input->post('tarih'),
                'klips_montaj_lot'      => $this->input->post('klips_montaj_lot'),
                'brulor_lot'         => $this->input->post('brulor_lot'),
                // olcum alanı kaldırıldı - JSON dosyaya kaydedilecek
            ];

            // Migration sonrası save_with_measurement kullanılıyor (add yerine)
            $insert = $this->final_kontrol_model->save_with_measurement($data, $olcum_data);

            // TODO: Alert sistemi eklenecek...
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

            redirect(base_url('finalkontrol'));
        } else {
            $viewData = new stdClass();

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'finalkontrol_ekle';
            $viewData->form_error = true;
            $viewData->kutu_no = $kutu_no;
            $viewData->lot = $lot;
            $viewData->urun = $urun;
            $viewData->json = (object)$olcum_data; // Migration sonrası array'den object'e çevir
            $viewData->kullanici = $kullanici;
            $viewData->sonuc = $sonuc;

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function finalkontrol_duzenle($id)
    {
        $viewData = new stdClass();
        /** Tablodan Verilerin Getirilmesi.. */
        $item = $this->final_kontrol_model->get_with_measurement(
            [
                'id'    => $id,
            ]
        );
        $viewData->sonuc_secim = $this->sonuc_secim_model->get_all();
        // ölçüm tablosu JSON dosyasından yüklenir (migration sonrası)
        $viewData->json = $item->olcum_data ? (object)$item->olcum_data : null;

        // seçilen urunnin bilgilerini getir
        $urun = $this->urunler_model->get(
            [
                'id'    => $item->urun,
            ]
        );
        $viewData->urun = $urun;

        $viewData->user = get_active_user();

        //die();
        // var_dump($viewData->json);
        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'finalkontrol_duzenle';
        $viewData->item = $item;
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function finalkontrol_sil($id)
    {
        $delete = $this->final_kontrol_model->delete(
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
        redirect(base_url('finalkontrol'));
    }

    public function finalkontrol_guncelle($id)
    {
        $this->load->library('form_validation');

        // Kurallar yazilir..

        $kutu_no = $this->input->post('kutu_no');
        $lot = $this->input->post('lot');
        $urun = $this->input->post('urun');
        // $tarih = $this->input->post("tarih");
        $aciklama = $this->input->post('aciklama');
        $kullanici = $this->input->post('kullanici');
        $sonuc = $this->input->post('sonuc');
        $olcum_data = $this->input->post('form'); // Migration sonrası JSON dosyaya kaydedilecek

        $klips_montaj_lot = $this->input->post('klips_montaj_lot');
        $brulor_lot = $this->input->post('brulor_lot');

        $this->form_validation->set_rules('kutu_no', 'Kutu No', 'required|trim');
        $this->form_validation->set_rules('lot', 'lot ', 'required|trim');

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
                'kutu_no'       => $this->input->post('kutu_no'),
                'lot'           => $this->input->post('lot'),
                'urun'          => $this->input->post('urun'),
                'aciklama'      => $this->input->post('aciklama'),
                'sonuc'         => $this->input->post('sonuc'),
                'kullanici'     => $user->id,

                'klips_montaj_lot'      => $this->input->post('klips_montaj_lot'),
                'brulor_lot'         => $this->input->post('brulor_lot'),

                // "tarih"         => $this->input->post("tarih"),
                // olcum alanı kaldırıldı - JSON dosyaya kaydedilecek
            ];
            
            // Migration sonrası save_with_measurement kullanılıyor
            $update = $this->final_kontrol_model->save_with_measurement($data, $olcum_data, $id);
            
            // TODO: Alert sistemi eklenecek...
            if ($update) {
                $alert = [
                    'title' => 'İşlem Başarılı',
                    'text' => 'Kayıt başarılı bir şekilde güncellendi',
                    'type'  => 'success',
                ];
            } else {
                $alert = [
                    'title' => 'İşlem Başarısız',
                    'text' => 'Kayıt güncellendi sırasında bir problem oluştu',
                    'type'  => 'error',
                ];
            }

            // İşlemin Sonucunu Session'a yazma işlemi...
            $this->session->set_flashdata('alert', $alert);

            redirect(base_url('finalkontrol'));
        } else {
            $viewData = new stdClass();

            // View'e gönderilecek Değişkenlerin Set Edilmesi..
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = 'anasayfa/finalkontrol_duzenle';
            $viewData->form_error = true;
            $viewData->kutu_no = $kutu_no;
            $viewData->lot = $lot;
            $viewData->json = (object)$olcum_data; // Migration sonrası array'den object'e çevir
            $viewData->kullanici = $kullanici;
            $viewData->sonuc = $sonuc;

            // Tablodan Verilerin Getirilmesi..
            $viewData->item = $this->final_kontrol_model->get_with_measurement(
                [
                    'id'    => $id,
                ]
            );

            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        }
    }

    public function new_form()
    {
        $viewData = new stdClass();

        $viewData->tedarikciler = $this->tedarikciler_model->get_all();
        $viewData->malzemeler = $this->malzemeler_model->get_all();

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
        $olcum = json_encode($this->input->post('form'));

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
            // $insert2 = $this->kontrol_no_model->add($data2);
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
                'olcum'         =>  json_encode($this->input->post('form')),
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

        $viewData->tedarikciler = $this->tedarikciler_model->get_all();

        $viewData->malzemeler = $this->malzemeler_model->get_all();

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
