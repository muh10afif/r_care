<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class R_semua extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
		$this->load->model(array('M_ots', 'M_all_report', 'M_home'));
		$this->cabang_as = $this->session->userdata('cabang_as');
	}

	public function tambah_bulan($bulan_awal, $tambah_bln)
	{
        $new_tgl_skrg    = strtotime($bulan_awal);

        $jml_hari        = $tambah_bln;
        $new_jml_hari    = 2678400 * $jml_hari;

        $hasil_jml       = $new_tgl_skrg + $new_jml_hari;
        $bulan_lanjut    = date('Y-m', $hasil_jml);

        return $bulan_lanjut;
	}

	public function tes()
	{
		/*$d = date('Y-m-d');

		echo substr($d,0, 4);*/

		/*$tgl_skrg       = date('Y-m-d H:i:s', now('Asia/Jakarta'));
        $new_tgl_skrg   = strtotime($tgl_skrg);

        $jml_hari       = 1;
        $new_jml_hari   = 2678400 * $jml_hari;

        $hasil_jml      = $new_tgl_skrg + $new_jml_hari;
        echo $batas_bayar    = date('Y-m-d H:i:s', $hasil_jml);*/

        $a = '2019-03';

        echo ($a+1);

	}

	public function tes_bulan()
    {
        $bulan_awal = '2011-09';
        $from= $bulan_awal."-01";
        // $from = date('Y-m-d', strtotime("-1 day", strtotime($from)));
        $to ='2012-03-07';

        $ar = array();
        $i=1;
        while (strtotime($from)<strtotime($to)){
        $from = mktime(0,0,0,date('m',strtotime($from)),date("d",strtotime($from))+1,date("Y",strtotime($from)));
        $from=date("Y-m-d", $from);
        array_push($ar, nice_date($from, 'Y-m'));

        $i++;
        }

		$w = array_unique($ar);

		$xa = array();

		foreach ($w as $x) {
			array_push($xa, $x);
		}

		print_r($xa);
    }


	public function index()
	{
		if (isset($_POST['cari'])) {

			$jenis_report	= $this->input->post('jenis_report');
			$bulan_awal_as 	= $this->input->post('start');
			$bulan_akhir_as = $this->input->post('end');
			$verifikator 	= $this->input->post('verifikator');
			$spk 			= $this->input->post('spk');

			$bulan_awal		= nice_date($bulan_awal_as, 'Y-m');
			$bulan_akhir 	= nice_date($bulan_akhir_as, 'Y-m');

			$id_spk         = $this->input->post('id_spk');
			$id_cabang_as   = $this->input->post('id_cabang_as');

			$akses          = $this->session->userdata('akses');

			if ($akses == 'kanwil_asuransi') {
				$array = array(
					'id_spk'    => $id_spk,
					'cabang_as' => $id_cabang_as
				);
				
				$this->session->set_userdata( $array );
			}

			// mengambil angka nilai bulan awal
			$b = substr($bulan_awal, -2);
			$b = ltrim($b, '0');

			// mengambil angka nilai bulan akhir
			$c = substr($bulan_akhir, -2);
			$c = ltrim($c, '0');

			// buat list bulan
			$from	= $bulan_awal."-01";
			$to 	= $bulan_akhir."-01";
	
			$ar = array();

			$i=1;
			while (strtotime($from)<strtotime($to)){

				$from	= mktime(0,0,0,date('m',strtotime($from)),date("d",strtotime($from))+1,date("Y",strtotime($from)));
				$from	= date("Y-m-d", $from);

				array_push($ar, nice_date($from, 'Y-m'));
		
				$i++;
			}
	
			$bulan_filter = array_unique($ar);

			$bl = array();

			foreach ($bulan_filter as $b) {
				array_push($bl, $b);
			}
			
			$data 	= ['report' 		=> 'aktif',
					   'id_cabang_as'	=> $this->cabang_as,
					   'verifikator'   	=> $this->M_ots->get_verifikator(),
					   'jenis_report'	=> $jenis_report,
					   'kondisi'		=> 'lihat',
					   'd_bulan_awal'	=> $bulan_awal,
					   'd_bulan_akhir'	=> $bulan_akhir,
					   'd_verifikator'	=> $verifikator,
					   'awal'			=> $b,
					   'akhir'			=> $c,
					   'bulan'			=> $bulan_filter,
					   'bulan_f'		=> $bl,
					   'nama_ver'		=> $this->M_all_report->get_nama_verifikator()->result_array(),
					   'id_spk'			=> $id_spk,
					   'id_cabang_as'	=> $id_cabang_as,
					   'spk_manager'	=> $spk
					  ];	

			if ($jenis_report == 4) {
				$data['nama_ver']			= $this->M_all_report->cari_nama_verifikator($verifikator)->row_array();
				$data['report']		 		= 'report_achievment';
				$data['data_report'] 		= $this->M_all_report->get_data_report_4($bulan_awal, $bulan_akhir, $verifikator)->result_array();
			} elseif ($jenis_report == 1) {
				$data['verifikator_1']   	= $this->M_all_report->get_verifikator();
				$data['report']		 		= 'report_ots_recoveries';
				// $data['data_report'] 		= $this->M_all_report->get_data_report_1($bulan_awal, $bulan_akhir);
			} elseif ($jenis_report == 2) {
				$data['report'] 			= 'laporan_ots';
				$data['nama_ver_jabar'] 	= $this->M_all_report->get_data_nama_ver(1);	
				$data['nama_ver_jateng'] 	= $this->M_all_report->get_data_nama_ver(3);	
			} elseif ($jenis_report == 3) {
				$data['report'] 			= 'laporan_debitur_sdh_dikunjungi';
				$data['nama_ver_jabar'] 	= $this->M_all_report->get_data_nama_ver(1);	
				$data['nama_ver_jateng'] 	= $this->M_all_report->get_data_nama_ver(3);
			} elseif ($jenis_report == 5) {
				$data['report']				= 'laporan_recoveries';
				$data['data_recov']			= $this->M_all_report->get_data_report_5($bulan_awal, $bulan_akhir)->result_array();
			}
 
			/*echo '<pre>';
			print_r($data['data_report']);
			echo '</pre>';
			exit();*/

			$halaman = "semua/report_$jenis_report";

			$this->load->view($halaman, $data);

		} else {

			$level          = $this->uri->segment(3);

			$id_spk         = $this->uri->segment(4);
			$id_cabang_as   = $this->uri->segment(5);

			$akses          = $this->session->userdata('akses');

			if ($akses == 'kanwil_asuransi') {
				$array = array(
					'id_spk'    => $id_spk,
					'cabang_as' => $id_cabang_as
				);
				
				$this->session->set_userdata( $array );
			}
		
			$data 	= [ 'report' 		=> 'aktif',
					    'id_cabang_as'	=> $this->session->userdata('cabang_as'),
					    'verifikator'	=> $this->M_all_report->get_verifikator(),
					    'akses'         => $akses,
						'nm_cabang'     => $this->M_home->cari_data('m_cabang_asuransi', array('id_cabang_asuransi' => $id_cabang_as))->row_array(),
						'no_spk'        => $this->M_home->cari_data('spk', array('id_spk' => $id_spk))->row_array(),
						'id_spk'		=> $id_spk,
						'id_cabang_as2'	=> $id_cabang_as,
						'spk'			=> $this->M_home->cari_data('spk', array('status' => 1))->result_array()
					  ];

			$this->template->load('layout/template','semua/V_report_semua', $data);
		}
		
	}

	public function aksi_report()
	{
		$jenis_report	= $this->input->post('jenis');
		$bulan_awal_as 	= $this->input->post('tgl_awal');
		$bulan_akhir_as = $this->input->post('tgl_akhir');
		$verifikator 	= $this->input->post('verifikator');

		$bulan_awal		= nice_date($bulan_awal_as, 'Y-m');
		$bulan_akhir 	= nice_date($bulan_akhir_as, 'Y-m');

		// mengambil angka nilai bulan awal
		$b = substr($bulan_awal, -2);
		$b = ltrim($b, '0');

		// mengambil angka nilai bulan akhir
		$c = substr($bulan_akhir, -2);
		$c = ltrim($c, '0');
		
		$data 	= ['report' 		=> 'aktif',
					'verifikator'   	=> $this->M_ots->get_verifikator(),
					'jenis_report'	=> $jenis_report,
					'kondisi'		=> 'lihat',
					'd_bulan_awal'	=> $bulan_awal,
					'd_bulan_akhir'	=> $bulan_akhir,
					'd_verifikator'	=> $verifikator,
					'awal'			=> $b,
					'akhir'			=> $c
					];	

		if ($jenis_report == 4) {
			$data['nama_ver']			= $this->M_all_report->cari_nama_verifikator($verifikator)->row_array();
			$data['report']		 		= 'report_achievment';
			$data['data_report'] 		= $this->M_all_report->get_data_report_4($bulan_awal, $bulan_akhir, $verifikator)->result_array();
		} elseif ($jenis_report == 1) {
			$data['verifikator_1']   	= $this->M_all_report->get_verifikator();
			$data['report']		 		= 'report_ots_recoveries';
			// $data['data_report'] 		= $this->M_all_report->get_data_report_1($bulan_awal, $bulan_akhir);
		} elseif ($jenis_report == 2) {
			$data['report'] 			= 'laporan_ots';
			$data['nama_ver_jabar'] 	= $this->M_all_report->get_data_nama_ver(1);	
			$data['nama_ver_jateng'] 	= $this->M_all_report->get_data_nama_ver(3);	
		} elseif ($jenis_report == 3) {
			$data['report'] 			= 'laporan_debitur_sdh_dikunjungi';
			$data['nama_ver_jabar'] 	= $this->M_all_report->get_data_nama_ver(1);	
			$data['nama_ver_jateng'] 	= $this->M_all_report->get_data_nama_ver(3);
		}

		/*echo '<pre>';
		print_r($data['data_report']);
		echo '</pre>';
		exit();*/

		$halaman = "semua/report_$jenis_report";

		$this->load->view($halaman, $data);
	}

	// fungsi untuk unduh data
	public function unduh_data()
	{
		// $jns_unduh = $this->uri->segment(3);
		
		$jenis_report	= $this->input->post('jenis_report');
		$bulan_awal_as 	= $this->input->post('bulan_awal');
		$bulan_akhir_as = $this->input->post('bulan_akhir');
		$verifikator 	= $this->input->post('verifikator');
		$spk 			= $this->input->post('spk');

		$bulan_awal		= nice_date($bulan_awal_as, 'Y-m');
		$bulan_akhir 	= nice_date($bulan_akhir_as, 'Y-m');

		$b= substr($bulan_awal, -2);
		$b=ltrim($b, '0');

		$c= substr($bulan_akhir, -2);
		$c=ltrim($c, '0');

		// buat list bulan
		$from	= $bulan_awal."-01";
		$to 	= $bulan_akhir."-01";

		$ar = array();

		$i=1;
		while (strtotime($from)<strtotime($to)){

			$from	= mktime(0,0,0,date('m',strtotime($from)),date("d",strtotime($from))+1,date("Y",strtotime($from)));
			$from	= date("Y-m-d", $from);

			array_push($ar, nice_date($from, 'Y-m'));
	
			$i++;
		}

		$bulan_filter = array_unique($ar);

		$bl = array();

		foreach ($bulan_filter as $b) {
			array_push($bl, $b);
		}
		
		$data 	= ['report' 		=> 'aktif',
				   'id_cabang_as'	=> $this->cabang_as,
				   'verifikator'   	=> $this->M_ots->get_verifikator(),
				   'jenis_report'	=> $jenis_report,
				   'kondisi'		=> 'dok',
				   'd_bulan_awal'	=> $bulan_awal,
				   'd_bulan_akhir'	=> $bulan_akhir,
				   'd_verifikator'	=> $verifikator,
				   'awal'			=> $b,
				   'akhir'			=> $c,
				   'bulan'			=> $bulan_filter,
				   'bulan_f'		=> $bl,
				   'nama_ver'		=> $this->M_all_report->get_nama_verifikator()->result_array(),
				   'spk_manager'	=> $spk
				  ];	

		if ($jenis_report == 4) {
			$data['nama_ver']			= $this->M_all_report->cari_nama_verifikator($verifikator)->row_array();
			$data['report']		 		= 'report_achievment';
			$data['data_report'] 		= $this->M_all_report->get_data_report_4($bulan_awal, $bulan_akhir, $verifikator)->result_array();
		} elseif ($jenis_report == 1) {
			$data['verifikator_1']   	= $this->M_all_report->get_verifikator();
			$data['report']		 		= 'report_ots_recoveries';
			// $data['data_report'] 		= $this->M_all_report->get_data_report_1($bulan_awal, $bulan_akhir);
		} elseif ($jenis_report == 2) {
			$data['report'] 			= 'laporan_ots';
			$data['nama_ver_jabar'] 	= $this->M_all_report->get_data_nama_ver(1);	
			$data['nama_ver_jateng'] 	= $this->M_all_report->get_data_nama_ver(3);	
		} elseif ($jenis_report == 3) {
			$data['report'] 			= 'laporan_debitur_sdh_dikunjungi';
			$data['nama_ver_jabar'] 	= $this->M_all_report->get_data_nama_ver(1);	
			$data['nama_ver_jateng'] 	= $this->M_all_report->get_data_nama_ver(3);
		} elseif ($jenis_report == 5) {
			$data['report']				= 'laporan_recoveries';
			$data['data_recov']			= $this->M_all_report->get_data_report_5($bulan_awal, $bulan_akhir)->result_array();
		}

		// $data 	= ['semua' 			=> 'aktif',
		// 		   'verifikator'   	=> $this->M_ots->get_verifikator(),
		// 		   'jenis_report'	=> $jenis_report,
		// 		   'kondisi'		=> 'dok',
		// 		   'd_bulan_awal'	=> $bulan_awal,
		// 		   'd_bulan_akhir'	=> $bulan_akhir,
		// 		   'd_verifikator'	=> $verifikator,
		// 		   'awal'			=> $b,
		// 		   'akhir'			=> $c
		// 		  ];

		// if ($jenis_report == 4) {
		// 	$data['nama_ver']			= $this->M_all_report->cari_nama_verifikator($verifikator)->row_array();
		// 	$data['data_report'] 		= $this->M_all_report->get_data_report_4($bulan_awal, $bulan_akhir, $verifikator)->result_array();
		// 	$data['report']		 		= 'report_achievment';
		// } elseif ($jenis_report == 1) {
		// 	$data['verifikator_1']   	= $this->M_all_report->get_verifikator();
		// 	//$data['data_report'] 		= $this->M_all_report->get_data_report_1($bulan_awal, $bulan_akhir);
		// 	$data['report']		 		= 'report_ots_recoveries';
		// } elseif ($jenis_report == 2) {
		// 	$data['nama_ver_jabar'] 	= $this->M_all_report->get_data_nama_ver(1);	
		// 	$data['nama_ver_jateng'] 	= $this->M_all_report->get_data_nama_ver(3);
		// 	$data['report'] 			= 'laporan_ots';
		// } elseif ($jenis_report == 3) {
		// 	$data['report'] 			= 'laporan_debitur_sdh_dikunjungi';
		// 	$data['nama_ver_jabar'] 	= $this->M_all_report->get_data_nama_ver(1);	
		// 	$data['nama_ver_jateng'] 	= $this->M_all_report->get_data_nama_ver(3);
		// } elseif ($jenis_report == 5) {
		// 	$data['report']				= 'laporan_recoveries';
		// }

		$halaman = "semua/report_$jenis_report";

		// if ($jns_unduh == 'pdf') {
		// 	$this->template->load('template_pdf' ,$halaman, $data);
		// } else {
		// 	$this->template->load('template_excel' ,$halaman, $data);
		// }

		if (isset($_POST['pdf'])) {
			$this->template->load('template_pdf' ,$halaman, $data);
		} else {
			$this->template->load('template_excel' ,$halaman, $data);
		}

	}

}

/* End of file R_semua.php */
/* Location: ./application/controllers/R_semua.php */