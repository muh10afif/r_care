<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class R_ots extends CI_controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
        $this->load->model(array('M_ots','M_noa'));
	}

    public function index()
    {
        if (isset($_POST['cari'])) {
            $tgl_awal   = $this->input->post('tgl_awal');
            $tgl_akhir  = $this->input->post('tgl_akhir');
            $wilayah    = $this->input->post('wilayah');
            $ver        = $this->input->post('verifikator');
            $bank       = $this->input->post('bank');
            $asuransi   = $this->input->post('asuransi');

            $data = [
                'wilayah'       => $this->M_ots->get_wilayah_capem(),
                'verifikator'   => $this->M_ots->get_verifikator(),
                'bank'          => $this->M_noa->get_bank(),
                'asuransi'      => $this->M_ots->get_asuransi($id = null),
                'data_r_ots'    => $this->M_ots->get_cari_data_r_ots($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank, $asuransi),
                'peringkat_ver' => $this->M_ots->get_peringkat_ver($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank, $asuransi),
                'total_noa'     => $this->M_ots->get_total_noa($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank, $asuransi),
                'total_ots'     => $this->M_ots->get_total_ots($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank, $asuransi),
                'judul'         => 'OTS',
                'ots'           => 'aktif',
                'p_tgl_awal'    => $tgl_awal,
                'p_tgl_akhir'   => $tgl_akhir,
                'p_wilayah'     => $wilayah,
                'p_ver'         => $ver,
                'p_bank'        => $bank,
                'p_asuransi'    => $asuransi
            ];

            if (!empty($tgl_awal) && empty($tgl_akhir) && empty($wilayah) && empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data tgl_awal <b>'.tgl_indo($tgl_awal).'</b></div>');
            } elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data tgl_akhir <b>'.tgl_indo($tgl_akhir).'</b></div>');
            } elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($wilayah) && empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data cabang <b>'.$wilayah.'</b></div>');
            } elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($wilayah) && !empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Verifikator oleh <b>'.$ver.'</b></div>');
            } elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');     
            } elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($wilayah) && empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data tgl_awal <b>'.tgl_indo($tgl_awal).'</b> cabang <b>'.$wilayah.'</b></div>');     
            } elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($wilayah) && !empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data tgl_awal <b>'.tgl_indo($tgl_awal).'</b> verifikator <b>'.$ver.'</b></div>');     
            } elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($wilayah) && empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data tgl_akhir <b>'.tgl_indo($tgl_akhir).'</b> cabang <b>'.$wilayah.'</b></div>');     
            } elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && !empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data tgl_akhir <b>'.tgl_indo($tgl_akhir).'</b> verifikator <b>'.$ver.'</b></div>');
            }// tanggal wilayah dengan verifikator
            elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($wilayah) && !empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data cabang <b>'.$wilayah.'</b>, verifikator <b>'.$ver.'</b></div>');   
            } elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($wilayah) && !empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data tgl_awal <b>'.tgl_indo($tgl_awal).'</b> cabang <b>'.$wilayah.'</b>, verifikator <b>'.$ver.'</b></div>');     
            } elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($wilayah) && !empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data tgl_awal <b>'.tgl_indo($tgl_akhir).'</b> cabang <b>'.$wilayah.'</b>, verifikator <b>'.$ver.'</b></div>');     
            } elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($wilayah) && !empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> cabang <b>'.$wilayah.'</b>, verifikator <b>'.$ver.'</b></div>');     
            } elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($wilayah) && empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> cabang <b>'.$wilayah.'</b></div>');
            } elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && !empty($ver)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> Verifikator <b>'.$ver.'</b></div>');
            }

            $this->template->load('template','ots/v_ots', $data);

        } elseif (isset($_POST['unduh'])) {
            
            $tgl_awal   = $this->input->post('tgl_awal');
            $tgl_akhir  = $this->input->post('tgl_akhir');
            $wilayah    = $this->input->post('wilayah');
            $ver        = $this->input->post('verifikator');
            $bank       = $this->input->post('bank');
            $asuransi   = $this->input->post('asuransi');

            $as = $this->M_ots->get_asuransi($asuransi)->row_array();

            $asr = $as['asuransi'];

            $nama_wil   = strtoupper($wilayah);

             if ((empty($tgl_awal)) && (empty($tgl_akhir)) && (empty($wilayah)) && (empty($ver)) && (empty($bank)) && (empty($asuransi)) ) {
                $all = "LAPORAN KESELURUHAN OTS DEBITUR";
            } else {
                $all = "LAPORAN OTS DEBITUR $nama_wil";
            }

            $data = [
                'Judul'         => $all,
                'data_r_ots'    => $this->M_ots->get_cari_data_unduh_r_ots($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank, $asuransi),
                'tgl_awal'      => $tgl_awal,
                'tgl_akhir'     => $tgl_akhir,
                'wilayah'       => $wilayah,
                'ver'           => $ver,
                'bank'          => $bank,
                'asuransi'      => $asr,
                'id_asuransi'   => $asuransi,
                'kondisi'       => 'lihat'
            ];

            $this->load->view('ots/lihat_print', $data);
            
        } else {
            $data = [
                'wilayah'       => $this->M_ots->get_wilayah_capem(),
                'verifikator'   => $this->M_ots->get_verifikator(),
                'bank'          => $this->M_noa->get_bank(),
                'asuransi'      => $this->M_ots->get_asuransi($id = null),
                'data_r_ots'    => $this->M_ots->get_data_r_ots(),
                'peringkat_ver' => $this->M_ots->get_peringkat_ver($tgl_awal=null, $tgl_akhir=null, $wilayah=null, $ver=null, $bank=null, $asuransi=null),
                'total_noa'     => $this->M_ots->get_total('debitur'),
                'total_ots'     => $this->M_ots->get_total('ots'),
                'judul'         => 'OTS',
                'ots'           => 'aktif',
                'p_tgl_awal'    => '',
                'p_tgl_akhir'   => '',
                'p_wilayah'     => '',
                'p_ver'         => '',
                'p_bank'        => '',
                'p_asuransi'    => ''
            ];

            $this->template->load('template','ots/v_ots', $data);
        }
        
    }

    public function unduh_pdf()
    {
        $tgl_awal   = $this->input->post('tgl_awal');
        $tgl_akhir  = $this->input->post('tgl_akhir');
        $wilayah    = $this->input->post('wilayah');
        $ver        = $this->input->post('verifikator');
        $bank       = $this->input->post('bank');
        $asuransi   = $this->input->post('asuransi');

        $as = $this->M_ots->get_asuransi($asuransi)->row_array();

        $asr = $as['asuransi'];

        $nama_wil   = strtoupper($wilayah);

         if ((empty($tgl_awal)) && (empty($tgl_akhir)) && (empty($wilayah)) && (empty($ver)) && (empty($bank)) && (empty($asuransi)) ) {
            $all = "LAPORAN KESELURUHAN OTS DEBITUR";
        } else {
            $all = "LAPORAN OTS DEBITUR $nama_wil";
        }

        $data = [
            'Judul'         => $all,
            'data_r_ots'    => $this->M_ots->get_cari_data_unduh_r_ots($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank, $asuransi),
            'tgl_awal'      => $tgl_awal,
            'tgl_akhir'     => $tgl_akhir,
            'wilayah'       => $wilayah,
            'ver'           => $ver,
            'bank'          => $bank,
            'id_asuransi'   => $asuransi,
            'asuransi'      => $asr,
            'report'        => 'OTS'
        ];

        if (isset($_POST['pdf'])) {
            $data['kondisi'] = 'pdf';
            $this->template->load('template_pdf', 'ots/lihat_print', $data);
        } else {
            $data['kondisi'] = 'excel';
            $this->template->load('template_excel', 'ots/lihat_print', $data);
        }

        
    }

}