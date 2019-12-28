<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Helpcenter extends CI_Controller {

    public $datakirim;
    public $pesan = "";

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function index() {
        if ($this->session->userdata('email') == NULL) {
            //bila session user kosong balik ke 'Login'
            redirect(base_url());
        } else {
            $this->load->model('Helpcenter_m');
            $this->datakirim['help'] = $this->Helpcenter_m->getHelpcenter();
            $this->datakirim['pesan'] = $this->pesan;

            $this->load->view('Helpcenter_view', $this->datakirim);
        }
    }

}
