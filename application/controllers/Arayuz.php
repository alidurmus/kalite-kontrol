<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Arayuz extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [];

        $this->load->view('arayuz/formlar/girdi', $data);
    }

    public function girdi()
    {
        $data = [];
        $this->load->helper(['form', 'url']);
        $this->load->model('Arayuz_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('parti_no', 'Parti No', 'required|trim');
        $this->form_validation->set_rules('irsaliye', 'irsaliye ', 'required|trim');

        if ($this->form_validation->run() != false) {
            $this->Arayuz_model->saveGirdiForm($this->input->post());
        }

        $this->load->view('arayuz/formlar/girdi', $data);
    }

    public function girdi_listele()
    {
        $data = [];
        $this->load->model('Arayuz_model');

        $data['formlar'] = $this->Arayuz_model->girdiFormlar();

        $this->load->view('arayuz/listele/girdi', $data);
    }

    public function girdi_guncelle()
    {
        $data = [];
        $this->load->helper(['form', 'url']);
        $this->load->model('Arayuz_model');
        $this->load->library('form_validation');

        $data['form'] = $this->Arayuz_model->girdiForm($this->input->get('form_id'));

        $data['json'] = json_decode($data['form'][0]->olcum);
        $this->load->view('arayuz/formlar/girdi_guncelle', $data);
    }

    public function process()
    {
        $data = [];
        $this->load->view('arayuz/formlar/process', $data);
    }

    public function finalform()
    {
        $data = [];
        $this->load->view('arayuz/formlar/final', $data);
    }
}

// End of file welcome.php
// Location: ./application/controllers/welcome.php
