<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Drivertopup extends CI_Controller {

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
            $this->load->model('Topup_m');
            $this->datakirim['topupdriver'] = $this->Topup_m->getDriverTopup();
            $this->datakirim['pesan'] = $this->pesan;

            $this->load->view('Drivertopup_view', $this->datakirim);
        }
    }

    public function batalTopup($nomor) {
        $this->load->model('Topup_m');
        $this->Topup_m->updateStatusTopup($nomor, 3);

        $this->pesan = "<p style=\"color:green\" class=\"text-center\">Topup berhasil digagalkan</p> <br>";
        $this->index();
    }

    public function validasiTopupForm($nomor, $iduser) {
//        echo "$nomor | $iduser";
        $this->load->model('Topup_m');
        $this->datakirim['detailtopup'] = $this->Topup_m->getDriverTopUpSpec($nomor);

        $this->load->view('Drivertopup2_view', $this->datakirim);
    }

    public function validasiTopup($nomor, $iduser) {

        $this->load->model('Topup_m');
        $pengecekan = $this->Topup_m->pengecekanTopUpDriver($nomor);
//        var_dump($pengecekan);
        if ($pengecekan[0]['status'] == 1) {
            //        PENGATURAN DATA SALDO =========================================
//        AMBIL DATA SALDO SAAT INI 
            $s = $this->Topup_m->getJumlahSaldoDriver($iduser);
            $saldoSaatIni = $s[0]['saldo'];

//        AMBIL SALDO PENAMBAHAN
            $s2 = $this->Topup_m->getJumlahPenambahan($nomor);
            $saldoPenambahan = $s2[0]['jumlah'];

//        SALDO JUMLAH 
            $saldojumlah = $saldoSaatIni + $saldoPenambahan;

//        TAMBAH TRANSAKSI DRIVER ======================================
            $this->Topup_m->addTransaksiDriver($iduser, $saldoPenambahan, $saldojumlah);

//        UPDATE SALDO =================================================
            $this->Topup_m->updateSaldo($iduser, $saldojumlah);

//        UPDATE STATUS TOPUP ===========================================
            $this->Topup_m->updateStatusTopup($nomor, 2);

//        REDIRECT + PESAN =======================================

            $this->pesan = "<p style=\"color:green\" class=\"text-center\">Topup berhasil di validasi</p> <br>";
            $this->index();
        } else {
            $this->pesan = "";
            $this->index();
        }
    }

}
