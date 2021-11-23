<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class R_semuaSyariah extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('askSyariah/M_ots_syariah', 'askSyariah/M_all_report_syariah'));
	}

	public function index()
	{
		if (isset($_POST['cari'])) {

			$jenis_report	= $this->input->post('jenis_report');
			$bulan_awal 	= $this->input->post('bulan_awal');
			$bulan_akhir 	= $this->input->post('bulan_akhir');
			$verifikator 	= $this->input->post('verifikator');

			// mengambil angka nilai bulan awal
			$b = substr($bulan_awal, -2);
			$b = ltrim($b, '0');

			// mengambil angka nilai bulan akhir
			$c = substr($bulan_akhir, -2);
			$c = ltrim($c, '0');
			
			$data 	= ['report' 		=> 'aktif',
					   'verifikator'   	=> $this->M_ots_syariah->get_verifikator(),
					   'jenis_report'	=> $jenis_report,
					   'kondisi'		=> 'lihat',
					   'd_bulan_awal'	=> $bulan_awal,
					   'd_bulan_akhir'	=> $bulan_akhir,
					   'd_verifikator'	=> $verifikator,
					   'awal'			=> $b,
					   'akhir'			=> $c
					  ];	

			if ($jenis_report == 4) {
				$data['nama_ver']			= $this->M_all_report_syariah->cari_nama_verifikator($verifikator)->row_array();
				$data['report']		 		= 'report_achievment';
				$data['data_report'] 		= $this->M_all_report_syariah->get_data_report_4($bulan_awal, $bulan_akhir, $verifikator)->result_array();
			} elseif ($jenis_report == 1) {
				$data['verifikator_1']   	= $this->M_ots_syariah->get_verifikator2();
				$data['report']		 		= 'report_ots_recoveries';
				$data['data_report'] 		= $this->M_all_report_syariah->get_data_report_1($bulan_awal, $bulan_akhir);
			} elseif ($jenis_report == 2) {
				$data['report'] 			= 'laporan_ots';
				$data['nama_ver_jabar'] 	= $this->M_all_report_syariah->get_data_nama_ver(1);	
				$data['nama_ver_jateng'] 	= $this->M_all_report_syariah->get_data_nama_ver(3);	
			} elseif ($jenis_report == 3) {
				$data['report'] 			= 'laporan_debitur_sdh_dikunjungi';
				$data['nama_ver_jabar'] 	= $this->M_all_report_syariah->get_data_nama_ver(1);	
				$data['nama_ver_jateng'] 	= $this->M_all_report_syariah->get_data_nama_ver(3);
			}

			$halaman = "askSyariah/semua/report_$jenis_report";

			$this->load->view($halaman, $data);

		} else {
			$data 	= ['report' 		=> 'aktif',
					   'verifikator'	=> $this->M_ots_syariah->get_verifikator2()
					  ];

			$this->template->load('template_syariah','askSyariah/semua/v_report_semua', $data);
		}
		
	}

	// fungsi untuk unduh data
	public function unduh_data()
	{
		$jenis_report	= $this->input->post('jenis_report');
		$bulan_awal 	= $this->input->post('bulan_awal');
		$bulan_akhir 	= $this->input->post('bulan_akhir');
		$verifikator 	= $this->input->post('verifikator');

		$b= substr($bulan_awal, -2);
		$b=ltrim($b, '0');

		$c= substr($bulan_akhir, -2);
		$c=ltrim($c, '0');

		$data 	= ['semua' 			=> 'aktif',
				   'verifikator'   	=> $this->M_ots_syariah->get_verifikator2(),
				   'jenis_report'	=> $jenis_report,
				   'kondisi'		=> 'dok',
				   'd_bulan_awal'	=> $bulan_awal,
				   'd_bulan_akhir'	=> $bulan_akhir,
				   'd_verifikator'	=> $verifikator,
				   'awal'			=> $b,
				   'akhir'			=> $c
				  ];

		if ($jenis_report == 4) {
			$data['nama_ver']			= $this->M_all_report_syariah->cari_nama_verifikator($verifikator)->row_array();
			$data['data_report'] 		= $this->M_all_report_syariah->get_data_report_4($bulan_awal, $bulan_akhir, $verifikator)->result_array();
			$data['report']		 		= 'report_achievment';
		} elseif ($jenis_report == 1) {
			$data['verifikator_1']   	= $this->M_ots_syariah->get_verifikator2();
			$data['data_report'] 		= $this->M_all_report_syariah->get_data_report_1($bulan_awal, $bulan_akhir);
			$data['report']		 		= 'report_ots_recoveries';
		} elseif ($jenis_report == 2) {
			$data['nama_ver_jabar'] 	= $this->M_all_report_syariah->get_data_nama_ver(1);	
			$data['nama_ver_jateng'] 	= $this->M_all_report_syariah->get_data_nama_ver(3);
			$data['report'] 			= 'laporan_ots';
		} elseif ($jenis_report == 3) {
			$data['report'] 			= 'laporan_debitur_sdh_dikunjungi';
			$data['nama_ver_jabar'] 	= $this->M_all_report_syariah->get_data_nama_ver(1);	
			$data['nama_ver_jateng'] 	= $this->M_all_report_syariah->get_data_nama_ver(3);
		}

		$halaman = "askSyariah/semua/report_$jenis_report";

		if (isset($_POST['pdf'])) {
			$this->template->load('template_pdf' ,$halaman, $data);
		} else {
			$this->template->load('template_excel' ,$halaman, $data);
		}

	}

}

/* End of file R_semua.php */
/* Location: ./application/controllers/R_semua.php */