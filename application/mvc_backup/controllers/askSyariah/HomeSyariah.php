<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * HOME A-Care
 */
class HomeSyariah extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
        $this->load->model(array('askSyariah/M_home_syariah'));
	}

    public function index()
    {
        if (isset($_POST['cari'])) {
            $bln = $this->input->post('bulan');
            
            $data = [
                'judul'             => 'Dashboard',
                'home'              => 'aktif',
                'recov_cabang_b'    => $this->M_home_syariah->get_cari_recov_cabang('terbesar',$bln),
                'recov_cabang_k'    => $this->M_home_syariah->get_cari_recov_cabang('terkecil',$bln),
                'recov_capem_b'     => $this->M_home_syariah->get_cari_recov_capem('terbesar',$bln),
                'recov_capem_k'     => $this->M_home_syariah->get_cari_recov_capem('terkecil',$bln),
                'data_recov_buruk'  => $this->M_home_syariah->get_cari_data_recov('buruk',$bln),
                'data_recov_baik'   => $this->M_home_syariah->get_cari_data_recov('baik',$bln),
                'data_recov_cukup'  => $this->M_home_syariah->get_cari_data_recov_cukup($bln)
            ];

            if (!empty($bln)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Recoveries <b>'.bln_indo($bln).'</b></div>');
            }
            

        } else {
            $data = [
                'judul'             => 'Dashboard',
                'home'              => 'aktif',
                'recov_cabang_b'    => $this->M_home_syariah->get_recov_cabang('terbesar'),
                'recov_cabang_k'    => $this->M_home_syariah->get_recov_cabang('terkecil'),
                'recov_capem_b'     => $this->M_home_syariah->get_recov_capem('terbesar'),
                'recov_capem_k'     => $this->M_home_syariah->get_recov_capem('terkecil'),
                'data_recov_buruk'  => $this->M_home_syariah->get_data_recov('buruk'),
                'data_recov_baik'   => $this->M_home_syariah->get_data_recov('baik'),
                'data_recov_cukup'  => $this->M_home_syariah->get_data_recov_cukup()
            ];
        }

    	

    	$this->template->load('template_syariah', 'askSyariah/v_homeSyariah', $data);
    }


    /*public function get_data_cabang()
    {
        $kinerja = $this->input->post('kinerja');

         $data = $this->M_home->get_data_cabang_1($kinerja);

        echo json_encode($data);
    }*/
}