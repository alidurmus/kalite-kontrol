<?php

class Etiket extends MY_Controller
{
    public $viewFolder = '';

    public function __construct()
    {
        parent::__construct();

        $this->viewFolder = 'etiket';

        $this->load->model('girdikontrol/girdi_kontrol_model');
        $this->load->model('proseskontrol/proses_kontrol_model');
        $this->load->model('finalkontrol/final_kontrol_model');
        $this->load->model('tedarikciler/tedarikciler_model');
        $this->load->model('malzemeler/malzemeler_model');
        $this->load->model('urunler/urunler_model');
        $this->load->model('kontrol_no/kontrol_no_model');

        // $this->load->model("excel/excel_model");

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

        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'list';
        // $viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function girdi_kontrol()
    {
        $viewData = new stdClass();

        //End Function index
    }

    public function proses_kontrol()
    {
        $viewData = new stdClass();

        //End Function index
    }

    public function kutu_no()
    {
        $this->load->library('form_validation');

        // Kurallar yazilir..

        $lot_no = $this->input->post('lot_no');
        $adet = $this->input->post('adet');

        for ($i = 0; $i < $adet; ++$i) {
            $qrname = 'kutu_no-' . $i;
            $this->load->library('ciqrcode');
            $params['data'] = $lot_no . '-' . $adet;
            $params['level'] = 'H';
            $params['size'] = 3;
            $params['savename'] = FCPATH . $qrname . '.png';
            $this->ciqrcode->generate($params);
        }

        //echo '<img src="'.base_url().'tes.png" />';

        // header("Content-Type: image/png");
        // $params['data'] = 'This is a text to encode become QR Code';
        // $this->ciqrcode->generate($params);
        // var_dump($params);
        // die();

        $viewData = new stdClass();

        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'kutu_no';
        $viewData->items = $params;

        $viewData->qrname = $qrname;
        $viewData->adet = $adet;
        $viewData->lot_no = $lot_no;
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        //End Function index
    }

    public function final_kontrol()
    {
        $qrname = 'final_kontrol';
        $this->load->library('ciqrcode');
        $params['data'] = 'This is a text to encode become QR Code';
        $params['level'] = 'H';
        $params['size'] = 3;
        $params['savename'] = FCPATH . $qrname . '.png';
        $this->ciqrcode->generate($params);

        //echo '<img src="'.base_url().'tes.png" />';

        // header("Content-Type: image/png");
        // $params['data'] = 'This is a text to encode become QR Code';
        // $this->ciqrcode->generate($params);
        // var_dump($params);
        // die();

        $viewData = new stdClass();

        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'final_onay';
        $viewData->items = $params;

        $viewData->qrname = $qrname;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        //End Function index
    }

    public function hammadde_kabul()
    {
        $viewData = new stdClass();

        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'hammadde_kabul';

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        //End Function index
    }

    public function hammadde_beklet()
    {
        $viewData = new stdClass();

        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'hammadde_beklet';

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        //End Function index
    }

    public function hammadde_red()
    {
        $viewData = new stdClass();

        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'hammadde_red';

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        //End Function index
    }

    public function malzeme_tanitim_karti()
    {
        $viewData = new stdClass();

        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'malzeme_tanitim_karti';
        // $viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        //End Function index
    }

    public function kontrol_no()
    {
        $viewData = new stdClass();

        //End Function index
    }
    //End Class Welcome
}
