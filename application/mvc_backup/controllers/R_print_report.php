<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Katalog
 */
class R_print_report extends CI_controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
		$this->load->model(array('M_noa','M_print_report'));
	}
	
    public function dokumen_upload()
    {
    	$tgl_awal 	= $this->input->post('tgl_awal');
    	$tgl_akhir 	= $this->input->post('tgl_akhir');
    	$asuransi 	= $this->input->post('asuransi');
    	$bank 		= $this->input->post('bank');

    	$data = [
    	    'report' 		=> 'aktif',
    	    'judul'			=> 'Print Report',
    	    'asuransi'      => $this->M_print_report->get_asuransi(),
			'bank'          => $this->M_noa->get_bank(),
			'data_dokumen'	=> $this->M_print_report->get_cari_dokumen_upload($tgl_awal,$tgl_akhir,$asuransi,$bank)->result_array()
    	];

    	// tanggal awal terisi
        if (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b></div>');
        }
        // tanggal akhir terisi
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
        }
        // asuransi terisi
        elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Asuransi <b>'.$asuransi.'</b></div>');
        }
        // bank terisi
        elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Bank <b>'.$bank.'</b></div>');
        }
        // tanggal awal dengan tanggal akhir
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
        }
        // tanggal awal dengan asuransi
        elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan Asuransi <b>'.$asuransi.'</b></div>');
        }
        // tanggal awal dengan bank
        elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan <b>'.$bank.'</b></div>');
        }
        // tanggal akhir dengan asuransi
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Asuransi <b>'.$asuransi.'</b></div>');
        }
        // tanggal akhir dengan bank
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan <b>'.$bank.'</b></div>');
        }
        // asuransi dengan bank
        elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Asuransi <b>'.$asuransi.'</b> dengan <b>'.$bank.'</b></div>');
        }
        // asuransi dengan bank
        elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> Asuransi <b>'.$asuransi.'</b> dengan <b>'.$bank.'</b></div>');
        }
        // asuransi dengan bank
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_akhir).'</b> Asuransi <b>'.$asuransi.'</b> dengan <b>'.$bank.'</b></div>');
        }
        // tanggal akhir sampai tanggal akhir, asuransi
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Asuransi <b>'.$asuransi.'</b></div>');
        }
        // tanggal akhir sampai tanggal akhir, dengan bank
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dari <b>'.$bank.'</b></div>');
        }
        // tanggal akhir sampai tanggal akhir, dengan asuransi dan bank
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
             $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Asuransi <b>'.$asuransi.'</b> dari <b>'.$bank.'</b></div>');
        }

    	$this->template->load('template','print_report/dokumen_upload/v_dokumen_upload',$data);
    }

    public function print_dokumen_upload()
    {
    	$debitur 	= $this->input->post('debitur');
    	$no_klaim 	= $this->input->post('no_klaim');

    	$data = [
    	    'report' 		=> 'aktif',
    	    'judul'			=> 'Print Report',
    	    'jenis'			=> 'Agunan',
    	    'asuransi'      => $this->M_print_report->get_asuransi(),
			'bank'          => $this->M_noa->get_bank(),
			'data_dokumen'	=> $this->M_print_report->get_print_dokumen_upload($debitur,$no_klaim)->result_array(),
			'debitur'       => $debitur,
            'no_klaim'      => $no_klaim
    	];

    	$this->load->view('print_report/dokumen_upload/print', $data);
    }

    public function print_agunan()
    {
    	$debitur 	= $this->input->post('debitur');
    	$no_klaim 	= $this->input->post('no_klaim');

    	$data = [
    	    'report' 		=> 'aktif',
    	    'judul'			=> 'Print Report',
    	    'jenis'			=> 'Agunan',
    	    'asuransi'      => $this->M_print_report->get_asuransi(),
			'bank'          => $this->M_noa->get_bank(),
			'data_agunan'	=> $this->M_print_report->get_print_agunan($debitur,$no_klaim)->result_array(),
			'debitur'       => $debitur,
            'no_klaim'      => $no_klaim
    	];

    	$this->load->view('print_report/agunan/print', $data);
    }

    public function agunan()
    {
    	$tgl_awal 	= $this->input->post('tgl_awal');
    	$tgl_akhir 	= $this->input->post('tgl_akhir');
    	$asuransi 	= $this->input->post('asuransi');
    	$bank 		= $this->input->post('bank');

    	$data = [
    	    'report' 		=> 'aktif',
    	    'judul'			=> 'Print Report',
    	    'jenis'			=> 'Agunan',
    	    'asuransi'      => $this->M_print_report->get_asuransi(),
			'bank'          => $this->M_noa->get_bank(),
			'data_agunan'	=> $this->M_print_report->get_cari_agunan($tgl_awal,$tgl_akhir,$asuransi,$bank)->result_array()
    	];

    	// tanggal awal terisi
        if (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b></div>');
        }
        // tanggal akhir terisi
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
        }
        // asuransi terisi
        elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Asuransi <b>'.$asuransi.'</b></div>');
        }
        // bank terisi
        elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Bank <b>'.$bank.'</b></div>');
        }
        // tanggal awal dengan tanggal akhir
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
        }
        // tanggal awal dengan asuransi
        elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan Asuransi <b>'.$asuransi.'</b></div>');
        }
        // tanggal awal dengan bank
        elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan <b>'.$bank.'</b></div>');
        }
        // tanggal akhir dengan asuransi
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Asuransi <b>'.$asuransi.'</b></div>');
        }
        // tanggal akhir dengan bank
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan <b>'.$bank.'</b></div>');
        }
        // asuransi dengan bank
        elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Asuransi <b>'.$asuransi.'</b> dengan <b>'.$bank.'</b></div>');
        }
        // asuransi dengan bank
        elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> Asuransi <b>'.$asuransi.'</b> dengan <b>'.$bank.'</b></div>');
        }
        // asuransi dengan bank
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_akhir).'</b> Asuransi <b>'.$asuransi.'</b> dengan <b>'.$bank.'</b></div>');
        }
        // tanggal akhir sampai tanggal akhir, asuransi
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Asuransi <b>'.$asuransi.'</b></div>');
        }
        // tanggal akhir sampai tanggal akhir, dengan bank
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dari <b>'.$bank.'</b></div>');
        }
        // tanggal akhir sampai tanggal akhir, dengan asuransi dan bank
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
             $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Asuransi <b>'.$asuransi.'</b> dari <b>'.$bank.'</b></div>');
        }

    	$this->template->load('template','print_report/agunan/v_agunan',$data);
    }

    public function upload_dokumen()
    {
        $data = [
            'report'        => 'aktif',
            'judul'         => 'Print Report',
            'jenis'         => 'Upload File',
            'data_upload'   => $this->M_print_report->get_data_upload()
        ];

        $this->template->load('template','print_report/v_upload_file', $data);
    }

    /*
    
    function do_upload(){
        $config['upload_path']="./assets/images"; //path folder file upload
        $config['allowed_types']='gif|jpg|png'; //type file yang boleh di upload
        $config['encrypt_name'] = TRUE; //enkripsi file name upload
         
        $this->load->library('upload',$config); //call library upload 
        if($this->upload->do_upload("file")){ //upload file
            $data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
 
            $judul= $this->input->post('judul'); //get judul image
            $image= $data['upload_data']['file_name']; //set file name ke variable image
             
            $result= $this->m_upload->simpan_upload($judul,$image); //kirim value ke model m_upload
            echo json_decode($result);
        }
    }
    
    */

     public function do_upload()
     {
        $config['upload_path']      = "./assets/img";
        $config['allowed_types']    = 'jpg|png|pdf|doc|docx|xls|xlsx|pptx|ppt';
        $config['encrypt_name']     = FALSE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload("file")) {

            $data = array('upload_data' => $this->upload->data());

            $judul = $this->input->post('judul');
            $image = $data['upload_data']['file_name'];

            $this->M_print_report->simpan_upload($judul, $image);

           /* echo json_encode($result);*/
            
            redirect('r_print_report/upload_dokumen');

        }
     }

     public function download($nama_file)
     {
         force_download("assets/img/$nama_file", NULL);
     }
}