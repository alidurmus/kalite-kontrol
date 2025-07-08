<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Arge extends MY_Controller
{
    public $viewFolder = '';

    public function __construct()
    {
        parent::__construct();

        $this->viewFolder = 'arge';

        $this->load->model('arge/arge_model');

        if (!get_active_user()) {
            redirect(base_url('login'));
        }

        if (!isAllowedViewModule()) {
            redirect(base_url());
        }
    }

    public function index()
    {
        //anasayfada linkleri tanımlama
        $data['girdi_kontrol'] = 'arge/girdi_kontrol_formu';
        $data['process_kontrol'] = 'arge/process_form';
        $data['final_kontrol'] = 'arge/final_kontrol';
        $data['kullanicilar'] = 'arge/kullanicilar';
        $data['urunler'] = 'arge/urunler';
        $data['tedarikciler'] = 'arge/tedarikciler';
        $data['malzemeler'] = 'arge/malzemeler';
        $data['is_emri_takip'] = 'arge/is_emri';
        $data['process_takip'] = 'arge/process_kontrol_cizelge';

        $this->load->view('arge', $data);
    }

    public function girdi_kontrol_formu()
    {
        $this->load->helper('xcrud');

        $xcrud = xcrud_get_instance();

        $xcrud->table('girdi_kontroller');
        $xcrud->table_name('Girdi Kontrol Formu');

        $olcumler = $xcrud->nested_table('girdi_kontroller', 'girdi_id', 'girdi_olcu_kontrol', 'girdi_form_id'); // 2nd level

        $xcrud->relation('girdi_malzeme', 'malzemeler', 'malzeme_id', 'malzeme_adi');
        $xcrud->relation('girdi_tedarikci', 'tedarikciler', 'tedarikci_id', 'tedarikci_adi');
        $xcrud->relation('girdi_kontrol_eden', 'kullanicilar', 'kullanici_id', 'kullanici_adi');
        $xcrud->change_type('girdi_darbe', 'radio', '1,0', ['values'=>['1'=>'Evet', '0'=>'Hayır']]);
        $xcrud->change_type('girdi_capak', 'radio', '1,0', ['values'=>['1'=>'Evet', '0'=>'Hayır']]);
        $xcrud->change_type('girdi_dalgalanma', 'radio', '1,0', ['values'=>['1'=>'Evet', '0'=>'Hayır']]);
        $xcrud->change_type('girdi_sivama', 'radio', '1,2,3', ['values'=>['1'=>'Uygun', '2'=>'Red', '3' => 'NA']]);
        $xcrud->change_type('girdi_en', 'float', '000', ['maxlength'=>10]);
        $xcrud->change_type('girdi_boy', 'float', '000', ['maxlength'=>10]);
        $xcrud->change_type('girdi_yukseklik', 'float', '000', ['maxlength'=>10]);
        $xcrud->change_type('girdi_kalinlik', 'float', '000', ['maxlength'=>10]);
        $xcrud->change_type('girdi_teknik_resim', 'radio', '1,2,3', ['values'=>['1'=>'Kabul', '2'=>'Red', '3' => 'Şartlı Kabul']]);
        $xcrud->change_type('girdi_kontrol_no', 'none');
        //$xcrud->links_label('<i class="icon-home"></i>');
        //$xcrud->column_pattern('girdi_darbe', '<a href="#" class="xcrud-action" data-task="edit" data-primary="{girdi_id}">{value}</a>');

        $xcrud->before_insert('kontrol_arttir');

        // var_dump(   $xcrud);
        //exit();

        $data['content'] = $xcrud->render();

        $this->load->view('xcrud_kontroller', $data);
        /*

         $DENOMS_xcrud ->relation(<strong>'Country_Id'</strong>,'countries','id','Name');
        $DENOMS_xcrud -> set_var('COUNTRY','{Country_Id}'); This doesn't work (with or wothout "'"

        $DENOMS_xcrud -> set_var('COUNTRY',(string)'{Country_Id}'); This doesn't work (with or wothout "'"
        $DENOMS_C = '{Country_Id}';  //This doesn't work
        echo " Country is " . $DENOMS_xcrud->get_var('COUNTRY') ."!";
        echo " Country is " . $DENOMS_C."!";
// I am trying to capture the selected country in Country_Id...

        $DENOMS_xcrud->relation('Denomination_Id','denominations','id','Denomination', null,null,'Name','Country_Id');  //I want this relation based on the selection in the first relation.  (Country) and it doesn't seem to work
//      $DENOMS_xcrud->relation('Denomination_Id','denominations','id','Denomination',"coins.Country_Id,{Country_Id}");
       $DENOMS_xcrud -> set_var('DENOMINATION','{Denomination_Id}');

        $DENOMS_D = '{Denomination_Idi}'; //Nothing is stored here
        echo " Denomination is " . $DENOMS_xcrud->get_var('DENOMINATION') ."!";
        echo " Denomination is " . $DENOMS_D ."!";
        echo " Country is " . $DENOMS_xcrud->get_var('COUNTRY') ."!";
        echo " Denomination is " . $DENOMS_xcrud->get_var('DENOMINATION') ."!";
        */
    }

    public function process_form()
    {
        $this->load->helper('xcrud');

        $xcrud = xcrud_get_instance();
        $xcrud->table_name('Process Kontrol Formu');
        $xcrud->table('process_kontrol');

        $olcumler = $xcrud->nested_table('process_kontrol', 'process_id', 'process_kontrol_sonuclar', 'sonuc_process_id'); // 2nd level
        $xcrud->change_type('process_kontrol_no', 'none');
        $xcrud->label('process_tarih', 'Tarih');
        $xcrud->label('process_kontrol_eden', 'Kontrol Eden Kullanıcı');
        $xcrud->label('process_malzeme', 'Malzeme');
        $xcrud->label('process_lot', 'Lot Numarası');
        $xcrud->label('process_kontrol_no', 'Kontrol Numarası');
        $xcrud->label('process_aciklama', 'Açıklama');

        $xcrud->relation('process_malzeme', 'malzemeler', 'malzeme_id', 'malzeme_adi');
        $xcrud->relation('process_urun', 'urunler', 'urun_id', 'urun_adi');
        $xcrud->relation('process_kontrol_eden', 'kullanicilar', 'kullanici_id', 'kullanici_adi');

        $xcrud->before_insert('process_kontrol_arttir');

        $data['content'] = $xcrud->render();

        $this->load->view('xcrud_kontroller', $data);
    }

    public function final_kontrol()
    {
        $this->load->helper('xcrud');

        $xcrud = xcrud_get_instance();
        $xcrud->table_name('Final Kontrol Formu');
        $xcrud->table('final_kontrol');

        $xcrud->change_type('final_kontrol_no', 'none');

        $olcumler = $xcrud->nested_table('final_kontrol', 'final_id', 'final_kontrol_sonuclar', 'sonuc_final_id'); // 2nd level

        $xcrud->label('final_urun_adi', 'Ürün Adı');
        $xcrud->label('final_tarih', 'Tarih');
        $xcrud->label('final_lot', 'Lot Numarası');
        $xcrud->label('final_barkod', 'Barkod');
        $xcrud->label('final_kontrol_1', 'K01');
        $xcrud->label('final_kontrol_2', 'K02');
        $xcrud->label('final_kontrol_3', 'K03');
        $xcrud->label('final_kontrol_no', 'Kontrol No');
        $xcrud->label('final_kontrol_eden', 'Kontrol Eden');
        $xcrud->label('final_kutu_no', 'Kutu No');
        $xcrud->label('final_aciklama', 'Açıklama');

        $xcrud->relation('final_urun_adi', 'urunler', 'urun_id', 'urun_adi');
        $xcrud->relation('final_kontrol_eden', 'kullanicilar', 'kullanici_id', 'kullanici_adi');

        $xcrud->before_insert('final_kontrol_arttir');

        $data['content'] = $xcrud->render();

        $this->load->view('xcrud_kontroller', $data);
    }

    public function process_kontrol_sonuclar()
    {
        $this->load->helper('xcrud');

        $xcrud = xcrud_get_instance();
        $xcrud->table('process_kontrol_sonuclar');
        $xcrud->relation('sonuc_process_id', 'process_kontrol', 'process_id', 'process_id');
        $data['content'] = $xcrud->render();

        $this->load->view('xcrud_kontroller', $data);
    }

    public function process_kontrol_cizelge()
    {
        $this->load->helper('xcrud');

        $xcrud = xcrud_get_instance();
        $xcrud->table('kontrol_no');
        $xcrud->relation('girdi_kontrol_no', 'girdi_kontroller', 'kontrol_no_id', 'process_isim');

        $xcrud->show_primary_ai_column(true);
        $xcrud->label('kontrol_no_id', 'Kontrol Id');
        //$xcrud->button('http://example.com/');

        $xcrud->unset_add();
        $xcrud->unset_edit();
        $xcrud->unset_remove();
        $xcrud->unset_view();
        //$xcrud->unset_csv();
        //$xcrud->unset_limitlist();
        //$xcrud->unset_numbers();
        //$xcrud->unset_pagination();
        //$xcrud->unset_print();
        //$xcrud->unset_search();
        //$xcrud->unset_title();
        //$xcrud->unset_sortable();

        $data['content'] = $xcrud->render();

        $this->load->view('xcrud_kontroller', $data);
    }

    public function malzemeler()
    {
        $this->load->helper('xcrud');

        $xcrud = xcrud_get_instance();
        $xcrud->table('malzemeler');
        $data['content'] = $xcrud->render();

        $this->load->view('xcrud_kontroller', $data);
    }

    public function roller()
    {
        $this->load->helper('xcrud');

        $xcrud = xcrud_get_instance();
        $xcrud->table('roller');
        $data['content'] = $xcrud->render();

        $this->load->view('xcrud_kontroller', $data);
    }

    public function tedarikciler()
    {
        $this->load->helper('xcrud');

        $xcrud = xcrud_get_instance();
        $xcrud->table('tedarikciler');
        $data['content'] = $xcrud->render();

        $this->load->view('xcrud_kontroller', $data);
    }

    public function is_emri()
    {
        $this->load->helper('xcrud');

        $xcrud = xcrud_get_instance();
        $xcrud->table('is_emri');
        $xcrud->relation('is_emri_musteri', 'musteriler', 'musteri_id', 'musteri_adi');
        $xcrud->relation('is_emri_urun', 'urunler', 'urun_id', 'urun_adi');
        $data['content'] = $xcrud->render();

        $this->load->view('xcrud_kontroller', $data);
    }

    public function musteriler()
    {
        $this->load->helper('xcrud');

        $xcrud = xcrud_get_instance();
        $xcrud->table('musteriler');
        $data['content'] = $xcrud->render();

        $this->load->view('xcrud_kontroller', $data);
    }

    public function urunler()
    {
        $this->load->helper('xcrud');

        $xcrud = xcrud_get_instance();
        $xcrud->table('urunler');
        $data['content'] = $xcrud->render();

        $this->load->view('xcrud_kontroller', $data);
    }

    public function kullanicilar()
    {
        $this->load->helper('xcrud');

        $xcrud = xcrud_get_instance();
        $xcrud->table('kullanicilar');

        $xcrud->relation('kullanici_rol', 'roller', 'rol_id', 'rol_adi');
        $data['content'] = $xcrud->render();

        $this->load->view('xcrud_kontroller', $data);
    }
}

// End of file welcome.php
// Location: ./application/controllers/welcome.php
