<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public $pesanerror = array("pesan" => "");

    //Session 
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Manageadmin_model');
    }

    public function index() {
        if ($this->session->userdata('email') == NULL) {
            $this->load->view('login_view', $this->pesanerror);
        } else {
            //bila belum logout
            header('Location: ' . base_url() . "index.php/dashboard");
        }
        
    }

    public function pengecekan() {
        $email = $_POST['email'];
        $pass = $_POST['pass'];

//        echo "$email | $pass";
        $this->load->database();
        $data = $this->db->query("select * from admin where email = '$email'");


        if ($d = $data->result_array()) {
            $emailDB = $d[0]['email'];
            $passDB = $d[0]['password'];
//            var_dump($d);
            if ($passDB != $pass) {
                $this->pesanerror = array(
                    "pesan" => "Password Salah"
                );
                $this->load->view('login_view', $this->pesanerror);
            } else {
                $this->session->set_userdata('id', $d[0]['id']);
                $this->session->set_userdata('nik', $d[0]['nik']);
                $this->session->set_userdata('email', $d[0]['email']);
                header('Location: ' . base_url() . "index.php/dashboard");
            }
        } else {
//            echo 'tidak ada data';
            $this->pesanerror = array(
                "pesan" => "User Admin tidak terdaftar"
            );
            $this->load->view('login_view', $this->pesanerror);
        }
    }
    public function login(){
       $username = $this->input->post('email'); // Ambil isi dari inputan username pada form login
       $password = md5($this->input->post('pass')); // Ambil isi dari inputan password pada form login dan encrypt dengan md5
       $user = $this->Manageadmin_model->get($username); // Panggil fungsi get yang ada di UserModel.php
       if(empty($user)){ // Jika hasilnya kosong / user tidak ditemukan
         $this->session->set_flashdata('message', 'Username tidak ditemukan'); // Buat session flashdata
         redirect('auth'); // Redirect ke halaman login
       }else{
         if($password == $user->password){ // Jika password yang diinput sama dengan password yang didatabase
           $session = array(
             'authenticated'=>true, // Buat session authenticated dengan value true
             'id'=>$user->email, // Buat session authenticated dengan value true
             'email'=>$user->email,  // Buat session username
             'nik'=>$user->nik // Buat session authenticated
           );
           $this->session->set_userdata($session); // Buat session sesuai $session
           redirect('dashboard'); // Redirect ke halaman welcome
         }else{
                $this->pesanerror = array(
                    "pesan" => "Password Salah"
                );
                $this->load->view('login_view', $this->pesanerror);
         }
       }
     }

}

