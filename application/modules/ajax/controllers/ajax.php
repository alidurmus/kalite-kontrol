<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ajax extends MY_Controller
{
    public $viewFolder = '';

    public function __construct()
    {
        parent::__construct();
        $this->viewFolder = 'ajax';

        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation']);
        $this->load->model('ajax/ajax_model');

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
        /** Tablodan Verilerin Getirilmesi.. */
        $items = $this->ajax_model->get_entries(
            [],
            'id ASC'
        );
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'list';
        $viewData->items = $items;
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function insert()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

            if ($this->form_validation->run() == false) {
                $data = ['response' => 'error', 'message' => validation_errors()];
            } else {
                $ajax_data = $this->input->post();

                if ($this->ajax_model->insert_entry($ajax_data)) {
                    $data = ['response' => 'success', 'message' => 'Data added successfully'];
                } else {
                    $data = ['response' => 'error', 'message' => 'failed'];
                }
            }

            echo json_encode($data);
        } else {
            echo "'No direct script access allowed'";
        }
    }

    public function fetch()
    {
        if ($this->input->is_ajax_request()) {
            $posts = $this->ajax_model->get_entries();
            echo json_encode($posts);
        } else {
            echo "'No direct script access allowed'";
        }
    }

    public function delete()
    {
        if ($this->input->is_ajax_request()) {
            $del_id = $this->input->post('del_id');

            if ($this->ajax_model->delete_entry($del_id)) {
                $data = ['response' => 'success'];
            } else {
                $data = ['response' => 'error'];
            }

            echo json_encode($data);
        }
    }

    public function edit()
    {
        if ($this->input->is_ajax_request()) {
            $this->input->post('edit_id');

            $edit_id = $this->input->post('edit_id');

            if ($post = $this->ajax_model->single_entry($edit_id)) {
                $data = ['response' => 'success', 'post' => $post];
            } else {
                $data = ['response' => 'error', 'message' => 'failed'];
            }

            echo json_encode($data);
        }
    }

    public function update()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('edit_name', 'Name', 'required');
            $this->form_validation->set_rules('edit_email', 'Email', 'required|valid_email');

            if ($this->form_validation->run() == false) {
                $data = ['response' => 'error', 'message' => validation_errors()];
            } else {
                $data['id'] = $this->input->post('edit_id');
                $data['name'] = $this->input->post('edit_name');
                $data['email'] = $this->input->post('edit_email');

                if ($this->ajax_model->update_entry($data)) {
                    $data = ['response' => 'success', 'message' => 'Data update successfully'];
                } else {
                    $data = ['response' => 'error', 'message' => 'failed'];
                }
            }

            echo json_encode($data);
        } else {
            echo "'No direct script access allowed'";
        }
    }
}
