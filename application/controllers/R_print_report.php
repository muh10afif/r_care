<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
    	$tgl_awal_1     = $this->input->post('tgl_awal');
    	$tgl_akhir_1    = $this->input->post('tgl_akhir');
    	$asuransi 	    = $this->input->post('asuransi');
        $bank 		    = $this->input->post('bank');
        
        if ($tgl_awal_1 != "") {
            $tgl_awal   = nice_date($tgl_awal_1, 'Y-m-d');
        } else {
            $tgl_awal   = "";
        }

        if ($tgl_akhir_1 != "") {
            $tgl_akhir  = nice_date($tgl_akhir_1, 'Y-m-d');
        } else {
            $tgl_akhir   = "";
        }

    	$data = [
    	    'report' 		=> 'aktif',
    	    'judul'			=> 'Print Report',
    	    'asuransi'      => $this->M_print_report->get_asuransi(),
			'bank'          => $this->M_noa->get_bank(),
			'data_dokumen'	=> $this->M_print_report->get_cari_dokumen_upload($tgl_awal,$tgl_akhir,$asuransi,$bank)->result_array()
    	];

    	// tanggal awal terisi
        if (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b></div>');
        }
        // tanggal akhir terisi
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
        }
        // asuransi terisi
        elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Asuransi <b>'.$asuransi.'</b></div>');
        }
        // bank terisi
        elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Bank <b>'.$bank.'</b></div>');
        }
        // tanggal awal dengan tanggal akhir
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
        }
        // tanggal awal dengan asuransi
        elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan Asuransi <b>'.$asuransi.'</b></div>');
        }
        // tanggal awal dengan bank
        elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan <b>'.$bank.'</b></div>');
        }
        // tanggal akhir dengan asuransi
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Asuransi <b>'.$asuransi.'</b></div>');
        }
        // tanggal akhir dengan bank
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan <b>'.$bank.'</b></div>');
        }
        // asuransi dengan bank
        elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Asuransi <b>'.$asuransi.'</b> dengan <b>'.$bank.'</b></div>');
        }
        // asuransi dengan bank
        elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> Asuransi <b>'.$asuransi.'</b> dengan <b>'.$bank.'</b></div>');
        }
        // asuransi dengan bank
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_akhir).'</b> Asuransi <b>'.$asuransi.'</b> dengan <b>'.$bank.'</b></div>');
        }
        // tanggal akhir sampai tanggal akhir, asuransi
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Asuransi <b>'.$asuransi.'</b></div>');
        }
        // tanggal akhir sampai tanggal akhir, dengan bank
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dari <b>'.$bank.'</b></div>');
        }
        // tanggal akhir sampai tanggal akhir, dengan asuransi dan bank
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
             $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Asuransi <b>'.$asuransi.'</b> dari <b>'.$bank.'</b></div>');
        }

    	$this->template->load('layout/template','print_report/dokumen_upload/V_dokumen_upload',$data);
    }

    public function print_dokumen_upload()
    {
    	$debitur 	= $this->input->post('debitur');
        $no_klaim 	= $this->input->post('no_klaim');
        
        $nm_debitur = $this->M_print_report->cari_data('debitur', array('id_debitur' => $debitur))->row_array();

    	$data = [
    	    'report' 		=> 'aktif',
    	    'judul'			=> 'Print Report',
    	    'jenis'			=> 'Agunan',
    	    'asuransi'      => $this->M_print_report->get_asuransi(),
			'bank'          => $this->M_noa->get_bank(),
			'data_dokumen'	=> $this->M_print_report->get_print_dokumen_upload($debitur,$no_klaim)->result_array(),
			'debitur'       => $nm_debitur['nama_debitur'],
            'no_klaim'      => $no_klaim
        ];
    
    	$this->load->view('print_report/dokumen_upload/print', $data);
    }

    public function print_agunan()
    {
    	$debitur 	= $this->input->post('debitur');
        $no_klaim 	= $this->input->post('no_klaim');
        
        $nm_debitur = $this->M_print_report->cari_data('debitur', array('id_debitur' => $debitur))->row_array();

    	$data = [
    	    'report' 		=> 'aktif',
    	    'judul'			=> 'Print Report',
    	    'jenis'			=> 'Agunan',
    	    'asuransi'      => $this->M_print_report->get_asuransi(),
			'bank'          => $this->M_noa->get_bank(),
			'data_agunan'	=> $this->M_print_report->get_print_agunan($debitur,$no_klaim)->result_array(),
			'debitur'       => $nm_debitur['nama_debitur'],
            'no_klaim'      => $no_klaim
    	];

    	$this->load->view('print_report/agunan/print', $data);
    }

    public function agunan()
    {
    	$tgl_awal_1     = $this->input->post('tgl_awal');
    	$tgl_akhir_1    = $this->input->post('tgl_akhir');
    	$asuransi 	    = $this->input->post('asuransi');
        $bank 		    = $this->input->post('bank');
        
        if ($tgl_awal_1 != "") {
            $tgl_awal   = nice_date($tgl_awal_1, 'Y-m-d');
        } else {
            $tgl_awal   = "";
        }

        if ($tgl_akhir_1 != "") {
            $tgl_akhir  = nice_date($tgl_akhir_1, 'Y-m-d');
        } else {
            $tgl_akhir   = "";
        }

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
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b></div>');
        }
        // tanggal akhir terisi
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
        }
        // asuransi terisi
        elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Asuransi <b>'.$asuransi.'</b></div>');
        }
        // bank terisi
        elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Bank <b>'.$bank.'</b></div>');
        }
        // tanggal awal dengan tanggal akhir
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
        }
        // tanggal awal dengan asuransi
        elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan Asuransi <b>'.$asuransi.'</b></div>');
        }
        // tanggal awal dengan bank
        elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan <b>'.$bank.'</b></div>');
        }
        // tanggal akhir dengan asuransi
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Asuransi <b>'.$asuransi.'</b></div>');
        }
        // tanggal akhir dengan bank
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan <b>'.$bank.'</b></div>');
        }
        // asuransi dengan bank
        elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Asuransi <b>'.$asuransi.'</b> dengan <b>'.$bank.'</b></div>');
        }
        // asuransi dengan bank
        elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> Asuransi <b>'.$asuransi.'</b> dengan <b>'.$bank.'</b></div>');
        }
        // asuransi dengan bank
        elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_akhir).'</b> Asuransi <b>'.$asuransi.'</b> dengan <b>'.$bank.'</b></div>');
        }
        // tanggal akhir sampai tanggal akhir, asuransi
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Asuransi <b>'.$asuransi.'</b></div>');
        }
        // tanggal akhir sampai tanggal akhir, dengan bank
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dari <b>'.$bank.'</b></div>');
        }
        // tanggal akhir sampai tanggal akhir, dengan asuransi dan bank
        elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
             $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Asuransi <b>'.$asuransi.'</b> dari <b>'.$bank.'</b></div>');
        }

    	$this->template->load('layout/template','print_report/agunan/V_agunan',$data);
    }

    public function list_no_klaim()
    {
        $tgl_awal 	= $this->input->post('tgl_awal');
    	$tgl_akhir 	= $this->input->post('tgl_akhir');
    	$asuransi 	= $this->input->post('asuransi');
        $bank 		= $this->input->post('bank');
        $id_debitur = $this->input->post('id_debitur');
        

        $data_agunan = $this->M_print_report->get_cari_agunan_2($tgl_awal,$tgl_akhir,$asuransi,$bank, $id_debitur)->result_array();

        $option  = "<option value=''>-- Pilih --</option>";

        foreach ($data_agunan as $d) {
            $option = "<option value='".$d['no_klaim']."'>".$d['no_klaim']."</option>";
        }

        $data = array('list_noklaim' => $option);

        echo json_encode($data);

    }

    public function list_no_klaim_2()
    {
        $tgl_awal 	= $this->input->post('tgl_awal');
    	$tgl_akhir 	= $this->input->post('tgl_akhir');
    	$asuransi 	= $this->input->post('asuransi');
        $bank 		= $this->input->post('bank');
        $id_debitur = $this->input->post('id_debitur');
        
        $data_dokumen   = $this->M_print_report->get_cari_dokumen_upload_2($tgl_awal,$tgl_akhir,$asuransi,$bank, $id_debitur)->result_array();

        $option  = "<option value=''>-- Pilih --</option>";

        foreach ($data_dokumen as $d) {
            $option = "<option value='".$d['no_klaim']."'>".$d['no_klaim']."</option>";
        }

        $data = array('list_noklaim' => $option);

        echo json_encode($data);

    }

    public function upload_dokumen()
    {
        $data = [
            'report'        => 'aktif',
            'judul'         => 'Print Report',
            'jenis'         => 'Upload File',
            'data_upload'   => $this->M_print_report->get_data_upload()
        ];

        $this->template->load('layout/template','print_report/V_upload_file', $data);
    }

    public function hapus_file_dok()
    {
        $id_laporan = $this->input->post('id_laporan');
        $dok        = $this->input->post('dok');
        
        $this->M_print_report->hapus_data('tr_laporan', array('id_laporan' => $id_laporan));

        unlink("./assets/img/$dok");

        redirect('r_print_report/upload_dokumen');
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
        $config['allowed_types']    = "*";
        $config['encrypt_name']     = FALSE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload("file")) {

            $data = array('upload_data' => $this->upload->data());

            $judul = $this->input->post('judul');
            $image = $data['upload_data']['file_name'];

            $this->M_print_report->simpan_upload($judul, $image);

           /* echo json_encode($result);*/
            
            redirect('r_print_report/upload_dokumen');

        } else {
            echo $er = $this->upload->display_errors();
        }
     }

     public function download()
     {
         $nama_file = $this->input->post('dok');
         
         force_download("assets/img/$nama_file", NULL);
     }
}