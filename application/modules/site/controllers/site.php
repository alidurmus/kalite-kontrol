<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Description of site.
 *
 * @author https://www.roytuts.com
 */
class Site extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('home');
    }
}

// End of file Site.php
// Location: ./application/modules/site/controllers/site.php
