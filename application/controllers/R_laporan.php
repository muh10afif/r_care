<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class R_laporan extends CI_Controller {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
		$this->db_2 = $this->load->database('database_hrd', TRUE); 

		$this->load->model(array('M_laporan', 'M_all_report'));
		
	}
	
	public function tes()
	{
		$date = "2019-01";

		// With timestamp, this gets last day and first
		echo "last day ".$last_day = date('t', strtotime($date))."<br>"; 
		echo "first day ".$first_day = date('1', strtotime($date));
	}
    

    public function index()
    {
        if (isset($_POST['cari'])) {
            
            $jenis_laporan	= $this->input->post('jenis_laporan');
			$bulan_awal_as 	= $this->input->post('start');
			$bulan_akhir_as = $this->input->post('end');
			$tgl_awal 		= $this->input->post('tgl_awal');
			$tgl_akhir 		= $this->input->post('tgl_akhir');

			$dt	= [ 'tanggal_awal'	=> $tgl_awal,
					'tanggal_akhir'	=> $tgl_akhir
				  ];

			if ($tgl_awal != '' && $tgl_akhir != '') {
				$bulan_awal		= nice_date($tgl_awal, 'Y-m');
				$bulan_akhir 	= nice_date($tgl_akhir, 'Y-m');
			} else {
				$bulan_awal		= nice_date($bulan_awal_as, 'Y-m');
				$bulan_akhir 	= nice_date($bulan_akhir_as, 'Y-m');
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

			if ($bulan_awal === $bulan_akhir) {
				$bln = [$bulan_awal];
			} else {
				$bln = $bulan_filter;
			}
			
			$data 	= ['kondisi'		=> 'lihat',
					   'd_bulan_awal'	=> $bulan_awal,
					   'd_bulan_akhir'	=> $bulan_akhir,
					   'awal'			=> $b,
					   'akhir'			=> $c,
					   'bulan'			=> $bln,
					   'bulan_f'		=> $bl,
					   'jenis_laporan'	=> $jenis_laporan,
					   'tgl_awal'		=> nice_date($tgl_awal, 'Y-m-d'),
					   'tgl_akhir'		=> nice_date($tgl_akhir, 'Y-m-d')
					  ];	

			if ($jenis_laporan == 1 || $jenis_laporan == 2) {
				$data['report']			= 'laporan_keuangan '.$jenis_laporan;
				$data['data_report']	= $this->M_laporan->get_data_detail_jurnal_coa($bulan_awal_as, $bulan_akhir_as)->result_array();
			} elseif ($jenis_laporan == 3) {
				$data['report']			= 'laporan_keuangan_3';
				$data['data_report']	= $this->M_laporan->get_data_potensi_komisi($bulan_awal, $bulan_akhir)->result_array();
			} elseif ($jenis_laporan == 5) {
				$data['report']			= 'laporan_keuangan_5';
				$data['data_report']	= $this->M_laporan->get_data_pengeluaran($dt)->result_array();
			} elseif ($jenis_laporan == 4) {
				$data['report']			= 'laporan_keuangan_4';
				$data['nama_ver'] 		= $this->M_all_report->get_nama_verifikator()->result_array();
			}
            
			$halaman = "laporan/laporan_$jenis_laporan";

			$this->load->view($halaman, $data);

        } else {
            $this->template->load('layout/template','laporan/V_laporan');
        }
        
	}
	
	// aksi unduh data laporan 
	public function unduh_data()
	{
		$jenis_laporan	= $this->input->post('jenis_laporan');
		$bulan_awal_as 	= $this->input->post('start');
		$bulan_akhir_as = $this->input->post('end');
		$tgl_awal 		= $this->input->post('tgl_awal');
		$tgl_akhir 		= $this->input->post('tgl_akhir');

		$dt	= [ 'tanggal_awal'	=> $tgl_awal,
				'tanggal_akhir'	=> $tgl_akhir
			  ];

		$bulan_awal		= nice_date($bulan_awal_as, 'Y-m');
		$bulan_akhir 	= nice_date($bulan_akhir_as, 'Y-m');

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

		if ($bulan_awal === $bulan_akhir) {
			$bln = [$bulan_awal];
		} else {
			$bln = $bulan_filter;
		}

		$data 	= ['kondisi'		=> 'dok',
					'd_bulan_awal'	=> $bulan_awal,
					'd_bulan_akhir'	=> $bulan_akhir,
					'awal'			=> $b,
					'akhir'			=> $c,
					'bulan'			=> $bln,
					'bulan_f'		=> $bl,
					'jenis_laporan'	=> $jenis_laporan,
					'tgl_awal'		=> nice_date($tgl_awal, 'Y-m-d'),
					'tgl_akhir'		=> nice_date($tgl_akhir, 'Y-m-d')
					];	

		if ($jenis_laporan == 1 || $jenis_laporan == 2) {
			$data['report']			= 'laporan_keuangan '.$jenis_laporan;
			$data['data_report']	= $this->M_laporan->get_data_detail_jurnal_coa($bulan_awal_as, $bulan_akhir_as)->result_array();
		} elseif ($jenis_laporan == 3) {
			$data['report']			= 'laporan_keuangan_3';
			$data['data_report']	= $this->M_laporan->get_data_potensi_komisi($bulan_awal, $bulan_akhir)->result_array();
		} elseif ($jenis_laporan == 5) {
			$data['report']			= 'laporan_keuangan_5';
			$data['data_report']	= $this->M_laporan->get_data_pengeluaran($dt)->result_array();
		} elseif ($jenis_laporan == 4) {
			$data['report']			= 'laporan_keuangan_4';
			$data['nama_ver'] 		= $this->M_all_report->get_nama_verifikator()->result_array();
		}
		
		// $data 	= ['kondisi'		=> 'dok',
		// 			'd_bulan_awal'	=> $bulan_awal,
		// 			'd_bulan_akhir'	=> $bulan_akhir,
		// 			'awal'			=> $b,
		// 			'akhir'			=> $c,
		// 			'bulan'			=> $bln,
		// 			'bulan_f'		=> $bl,
		// 			'jenis_laporan'	=> $jenis_laporan,
		// 			'tgl_awal'		=> $tgl_awal,
		// 			'tgl_akhir'		=> $tgl_akhir
		// 			];	

		// if ($jenis_laporan == 1 || $jenis_laporan == 2) {
		// 	$data['report']			= 'laporan_keuangan '.$jenis_laporan;
		// 	$data['data_report']	= $this->M_laporan->get_data_detail_jurnal_coa($bulan_awal_as, $bulan_akhir_as)->result_array();
		// } elseif ($jenis_laporan == 3) {
		// 	$data['report']			= 'laporan_keuangan_3';
		// 	$data['data_report']	= $this->M_laporan->get_data_potensi_komisi($bulan_awal, $bulan_akhir)->result_array();
		// } elseif ($jenis_laporan == 5) {
		// 	$data['report']			= 'laporan_keuangan_5';
		// 	$data['data_report']	= $this->M_laporan->get_data_pengeluaran($dt)->result_array();
		// }
		
		$halaman = "laporan/laporan_$jenis_laporan";

		if (isset($_POST['pdf'])) {
			$this->template->load('template_pdf' ,$halaman, $data);
		} else {
			$this->template->load('template_excel' ,$halaman, $data);
		}
	}

}

/* End of file R_laporan.php */
