<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_all_report extends CI_Model {

	// report 5, laporan recoveries
	public function get_data_report_5($bulan_awal, $bulan_akhir)
	{
		$level 			= $this->session->userdata('level');
		$id_cabang_as 	= $this->session->userdata('cabang_as');
		$id_spk   	 	= $this->session->userdata('id_spk');

		$this->db->select('d.id_care, d.no_reff, d.no_klaim, d.nama_debitur, cap.capem_bank, t.nominal as jml_bayar, t.tgl_bayar, b.bank, asu.cabang_asuransi, (SELECT sum(nominal) as tot_recov FROM tr_recov_as WHERE id_debitur = d.id_debitur)');

		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('tr_recov_as as t', 't.id_debitur = d.id_debitur', 'inner');
		$this->db->where("CAST(t.tgl_bayar AS VARCHAR) BETWEEN '$bulan_awal' AND '$bulan_akhir+1'");

		if ($level == 10) {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			$this->db->where('asr.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}
		
		$this->db->order_by('t.tgl_bayar', 'desc');

		return $this->db->get();
	}

	// get verifikator
	public function get_verifikator()
	{
		// $this->db->select('k.nama_lengkap, k.id_karyawan');
		// $this->db->from('karyawan as k');
		// $this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'inner');
		// $this->db->group_by('k.nama_lengkap');
		// $this->db->group_by('k.id_karyawan');
		// $this->db->order_by('k.nama_lengkap', 'asc');

		$level = $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('k.id_karyawan, k.nama_lengkap');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as k', 'k.id_karyawan = p.id_karyawan');

		$this->db->group_by('k.nama_lengkap');
		$this->db->group_by('k.id_karyawan');
		$this->db->order_by('k.nama_lengkap', 'asc');

		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		return $this->db->get();
	}

	// cari nama verifikator
	public function cari_nama_verifikator($verifikator)
	{
		return $this->db->get_where('karyawan', array('id_karyawan' => $verifikator));
	}

	/*SELECT w.nama_lengkap
	FROM debitur as d
	JOIN m_capem_bank as c ON c.id_capem_bank = d.id_capem_bank
	JOIN m_cabang_bank as b ON b.id_cabang_bank = c.id_cabang_bank
	JOIN m_bank as n ON n.id_bank = b.id_bank
	JOIN m_cabang_asuransi as ca ON ca.id_cabang_asuransi = d.id_cabang_as
	JOIN m_korwil_asuransi as ka ON ka.id_korwil_asuransi = ca.id_korwil_asuransi
	JOIN m_asuransi as ma ON ma.id_asuransi = ka.id_asuransi
	JOIN penempatan as p ON p.id_capem_bank = d.id_capem_bank
	JOIN karyawan as w ON w.id_karyawan = p.id_karyawan
	JOIN ots as o ON o.id_debitur = d.id_debitur
	JOIN recoveries as r ON r.id_debitur = d.id_debitur
	JOIN periode as pe ON pe.id_cabang_bank = b.id_cabang_bank
	JOIN rekonsiliasi as re ON re.id_periode = pe.id_periode
	JOIN invoice as i ON i.id_invoice = re.id_invoice
	WHERE ma.id_asuransi = 1 and n.id_bank = 1 -- and o.add_time::VARCHAR like '%2019-05%'
	GROUP BY w.nama_lengkap
	ORDER BY w.nama_lengkap asc*/

	public function get_tot_noa_recov_report1($bulan_awal, $bulan_akhir, $id_karyawan)
	{
		$this->db->select('d.nama_debitur, d.subrogasi_as as subrogasi, sum(r.nominal) as tot_recov');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('ots as o','o.id_debitur = d.id_debitur')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur')
				 ;
		$this->db->where('ma.id_asuransi', 1);
		$this->db->where('w.id_karyawan', $id_karyawan);
		$this->db->where("CAST(r.add_time AS VARCHAR) BETWEEN '$bulan_awal' AND '$bulan_akhir+1'");
		$this->db->where('r.nominal !=', 0);
		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.subrogasi_as');

		$hasil = $this->db->get();

		$recov = 0;
		$value = array();

		foreach ($hasil->result_array() as $h) {
			$recov += $h['tot_recov'];
		}

		$value = ['tot_recov' => $recov, 'tot_noa' => $hasil->num_rows()];

		return $value;
	}


	// report 1
	public function get_noa_recov_report1($bulan, $id_karyawan)
	{
		$level = $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('d.nama_debitur, COALESCE(d.recoveries_awal_asuransi, 0) + COALESCE((SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur=d.id_debitur and r.nominal != 0), 0) as tot_recov_as');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur')
				 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur')
	  			 ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan');
		$this->db->where('w.id_karyawan', $id_karyawan);
		// $this->db->where('d.ots', 1);
		// $this->db->where('tp.status', 1);
		$this->db->where("CAST(kj.add_time AS VARCHAR) like '%$bulan%' ");

		

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$hasil = $this->db->get();

		$recov = 0;
		$value = array();

		foreach ($hasil->result_array() as $h) {
			$recov += $h['tot_recov_as'];
		}

		$value = ['tot_recov' => $recov, 'tot_noa' => $hasil->num_rows()];

		return $value;
	}

	public function get_recov_invo_t_report1($bulan_awal, $bulan_akhir, $id_karyawan)
	{
		$this->db->select('d.nama_debitur, d.subrogasi_as as subrogasi, sum(r.nominal) as tot_recov');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('ots as o','o.id_debitur = d.id_debitur')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur')
				 ->join('periode as pe', 'pe.id_cabang_bank = b.id_cabang_bank')
				 ->join('rekonsiliasi as re', 're.id_periode = pe.id_periode')
				 ->join('invoice as i', 'i.id_invoice = re.id_invoice')
				 ;
		$this->db->where('ma.id_asuransi', 1);
		$this->db->where('w.id_karyawan', $id_karyawan);
		$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$bulan_awal' AND '$bulan_akhir+1'");
		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.subrogasi_as');

		$hasil = $this->db->get()->result_array();

		$recov = 0;
		$value = array();

		foreach ($hasil as $h) {
			$recov += $h['tot_recov'];
		}

		$value = ['tot_recov' => $recov ];

		return $value;
	}

	public function get_recov_invo_report1($bulan, $id_karyawan)
	{
		// $this->db->select('d.nama_debitur, (COALESCE(d.subrogasi_as, 0) - COALESCE(d.recoveries_awal_asuransi, 0)) - COALESCE((SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur=d.id_debitur), 0) as shs');
		// $this->db->from('debitur as d');
		// $this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
		// 		 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
		// 		 ->join('m_bank as n', 'n.id_bank = b.id_bank')
		// 		 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
		// 		 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
		// 		 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
		// 		 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
		// 		 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
		// 		 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur')
		// 		 ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan');
		// $this->db->where('ma.id_asuransi', 1);
		// $this->db->where('w.id_karyawan', $id_karyawan);
		// $this->db->where('tp.ots', 1);
		// $this->db->where('tp.status', 1);
		
		// $this->db->where("CAST(kj.add_time AS VARCHAR) like '%$bulan%' ");

		$level = $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('d.nama_debitur, d.subrogasi_as as subrogasi, COALESCE(d.recoveries_awal_asuransi, 0) + COALESCE((SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur=d.id_debitur), 0) as tot_recov_as');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur')
				 ->join('periode as pe', 'pe.id_cabang_bank = b.id_cabang_bank')
				 ->join('rekonsiliasi as re', 're.id_periode = pe.id_periode')
				 ->join('invoice as i', 'i.id_invoice = re.id_invoice')
				 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur')
		 		 ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan');
		$this->db->where('w.id_karyawan', $id_karyawan);
		// $this->db->where('d.ots', 1);
		// $this->db->where('tp.status', 1);
		$this->db->where("CAST(kj.add_time AS VARCHAR) like '%$bulan%' ");

		

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$hasil = $this->db->get()->result_array();

		$recov = 0;
		$value = array();

		foreach ($hasil as $h) {
			$recov += $h['tot_recov_as'];
		}

		$value = ['tot_recov' => $recov ];

		return $value;

	}

	public function get_total_noa_r1($bulan_awal, $bulan_akhir, $id_karyawan)
	{
		$this->db->select('d.nama_debitur, d.subrogasi_as as subrogasi, sum(r.nominal) as tot_recov');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('ots as o','o.id_debitur = d.id_debitur')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur');
		$this->db->where('ma.id_asuransi', 1);
		$this->db->where('w.id_karyawan', $id_karyawan);
		$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$bulan_awal' AND '$bulan_akhir+1'");
		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.subrogasi_as');

		$hasil = $this->db->get();

		$value 	= array();
		$s 		= 0;
		$r 		= 0;
		$shs 	= 0;

		foreach ($hasil->result_array() as $h) {
			$s += $h['subrogasi'];
			$r += $h['tot_recov'];

		}
		
		$shs = $s-$r;

		$value 	= [ 'jml_tot_noa_r1'	=> $hasil->num_rows(),
					'shs_tot_r1'		=> $shs
					];

		return $value;
	}

	// report 1, menampilkan jumlah noa
	public function get_jumlah_noa_ots($bulan, $id_karyawan)
	{
		$level = $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('d.nama_debitur, d.id_debitur');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank');
		$this->db->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank');
		$this->db->join('m_bank as n', 'n.id_bank = b.id_bank');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi');
		$this->db->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank');
		$this->db->join('karyawan as w', 'w.id_karyawan = p.id_karyawan');
		$this->db->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur');
		$this->db->where('w.id_karyawan', $id_karyawan);

		
		
		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}

		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}
		
		$this->db->where("CAST(kj.add_time AS VARCHAR) LIKE '%$bulan%' ");
		
		$this->db->group_by('d.id_debitur');
	
		return $this->db->get();
		
	}

	public function get_jml_noa_report1($bulan, $id_karyawan)
	{
		// SELECT d.nama_debitur, kj.add_time as tgl_ots,n.bank, c.capem_bank as kantorcabangbjb, ca.cabang_asuransi, r.tgl_bayar, d.subrogasi_as, d.recoveries_awal_asuransi, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur=d.id_debitur), COALESCE((SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur=d.id_debitur), 0) + COALESCE((d.recoveries_awal_asuransi), 0) as tot_recov, (COALESCE(d.subrogasi_as, 0) - COALESCE(d.recoveries_awal_asuransi, 0)) - COALESCE((SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur=d.id_debitur), 0) as SHS
		// FROM debitur as d
		// JOIN m_capem_bank as c ON c.id_capem_bank = d.id_capem_bank
		// JOIN m_cabang_bank as b ON b.id_cabang_bank = c.id_cabang_bank
		// JOIN m_bank as n ON n.id_bank = b.id_bank
		// JOIN m_cabang_asuransi as ca ON ca.id_cabang_asuransi = d.id_cabang_as
		// JOIN m_korwil_asuransi as ka ON ka.id_korwil_asuransi = ca.id_korwil_asuransi
		// JOIN m_asuransi as ma ON ma.id_asuransi = ka.id_asuransi
		// JOIN penempatan as p ON p.id_capem_bank = d.id_capem_bank
		// JOIN karyawan as w ON w.id_karyawan = p.id_karyawan
		// JOIN kunjungan as kj ON kj.id_debitur = d.id_debitur
		// JOIN tr_potensial as tp ON tp.id_kunjungan = kj.id_kunjungan
		// JOIN tr_recov_as as r ON r.id_debitur = d.id_debitur
		// WHERE w.id_karyawan = 1 and r.tgl_bayar::VARCHAR BETWEEN '2019-03' AND '2019-12' and ma.id_asuransi = 1 and tp.status = 1 and tp.ots = 1
		// ORDER BY nama_debitur asc

		$level = $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('d.nama_debitur, (COALESCE(d.subrogasi_as, 0) - COALESCE(d.recoveries_awal_asuransi, 0)) - COALESCE((SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur=d.id_debitur), 0) as shs');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur');
				//  ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan');
		$this->db->where('w.id_karyawan', $id_karyawan);
		// $this->db->where('d.ots', 1);
		// $this->db->where('tp.status', 1);

		

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}

		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}
		
		$this->db->where("CAST(kj.add_time AS VARCHAR) like '%$bulan%' ");
		

		$hasil = $this->db->get();

		$value 	= array();
		$shs 	= 0;

		foreach ($hasil->result_array() as $h) {
			$shs += $h['shs'];
		}

		$value 	= [ 'jml_noa_r1'	=> $hasil->num_rows(),
					'shs_r1'		=> $shs
					];

		return $value;
	}

	public function get_data_tot_noa_sdh($id_bank, $bulan_awal, $bulan_akhir)
	{
		$level = $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('d.nama_debitur, d.subrogasi_as as subrogasi, sum(r.nominal) as tot_recov');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur')
				 ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur');
		$this->db->where('n.id_bank', $id_bank);
		// $this->db->where('tp.status', 1);
		// $this->db->where('d.ots', 1);
		$this->db->where("CAST(kj.add_time AS VARCHAR) BETWEEN '$bulan_awal' AND '$bulan_akhir+1'");

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.subrogasi_as');

		$hasil = $this->db->get();

		$s 		= 0;
		$r 		= 0;
		$shs 	= 0;

		foreach ($hasil->result_array() as $h) {
			$s += $h['subrogasi'];
			$r += $h['tot_recov'];

		}
		
		$shs = $s-$r;

		$value 	= [ 'jml_noa_tot_sdh'	=> $hasil->num_rows(),
					'shs_tot_sdh'		=> $shs
					];

		return $value;
	}

	// menampilkan jumlah noa yang sudah di ots menurut verifikator
	public function get_data_jml_noa_sdh($id_bank, $id_karyawan)
	{
		$level = $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('d.nama_debitur, (COALESCE(d.subrogasi_as, 0) - COALESCE(d.recoveries_awal_asuransi, 0)) - COALESCE((SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur=d.id_debitur), 0) as shs');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur')
				 ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur');
		$this->db->where('n.id_bank', $id_bank);
		$this->db->where('w.id_karyawan', $id_karyawan);
		// $this->db->where('tp.status', 1);
		// $this->db->where('d.ots', 1);

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$hasil = $this->db->get();

		$shs 	= 0;

		foreach ($hasil->result_array() as $h) {
			$shs += $h['shs'];
		}

		$value 	= [ 'jml_noa_sdh'	=> $hasil->num_rows(),
					'shs_sdh'		=> $shs
					];

		return $value;
	}

	public function get_data_jml_noa_tot($bulan)
	{
		$level = $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('d.nama_debitur, d.subrogasi_as as subrogasi, sum(r.nominal) as tot_recov');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				//  ->join('ots as o','o.id_debitur = d.id_debitur')
				 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur')
				 ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur');
		$this->db->where('ma.id_asuransi', 1);
		$this->db->where('n.id_bank', 1);
		// $this->db->where('tp.status', 1);
		// $this->db->where('d.ots', 1);
		$this->db->where("CAST(kj.add_time AS VARCHAR) like '%$bulan%' ");

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.subrogasi_as');

		$hasil_2 = $this->db->get();

		$s 		= 0;
		$r 		= 0;
		$shs 	= 0;

		foreach ($hasil_2->result_array() as $h) {
			$s += $h['subrogasi'];
			$r += $h['tot_recov'];

		}
		
		$shs = $s-$r;

		$this->db->select('d.nama_debitur, d.subrogasi_as as subrogasi, sum(r.nominal) as tot_recov');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				//  ->join('ots as o','o.id_debitur = d.id_debitur')
				 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur')
				 ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur');
		$this->db->where('ma.id_asuransi', 1);
		$this->db->where('n.id_bank', 3);
		$this->db->where('tp.status', 1);
		$this->db->where('tp.ots', 1);
		$this->db->where("CAST(kj.add_time AS VARCHAR) like '%$bulan%' ");

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.subrogasi_as');

		$hasil_3 = $this->db->get();

		$s 		= 0;
		$r 		= 0;
		$shs_j 	= 0;

		foreach ($hasil_3->result_array() as $h) {
			$s += $h['subrogasi'];
			$r += $h['tot_recov'];

		}
		
		$shs_j = $s-$r;

		$value 	= [ 'jml_noa_t'	=> $hasil_3->num_rows() + $hasil_2->num_rows(),
					'shs_t'		=> $shs_j + $shs
					];

		return $value;
	}

	public function get_data_jml_noa_s($id_bank, $bulan)
	{
		$level = $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('d.nama_debitur, d.subrogasi_as as subrogasi, sum(r.nominal) as tot_recov');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				//  ->join('ots as o','o.id_debitur = d.id_debitur')
				 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur')
				 ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur');
		$this->db->where('n.id_bank', $id_bank);
		// $this->db->where('tp.status', 1);
		// $this->db->where('d.ots', 1);
		$this->db->where("CAST(kj.add_time AS VARCHAR) like '%$bulan%' ");

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.subrogasi_as');

		$hasil_2 = $this->db->get();

		$s 		= 0;
		$r 		= 0;
		$shs 	= 0;

		foreach ($hasil_2->result_array() as $h) {
			$s += $h['subrogasi'];
			$r += $h['tot_recov'];

		}
		
		$shs = $s-$r;

		$value 	= [ 'jml_noa_s'	=> $hasil_2->num_rows(),
					'shs_s'		=> $shs
					];

		return $value;
	}

	// menampilkan jumlah noa sesuai bulan 
	public function get_data_jml_noa_2($id_bank, $bulan, $id_karyawan)
	{
		$this->db->select('d.nama_debitur, d.subrogasi_as as subrogasi, sum(r.nominal) as tot_recov');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur')
				 ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur');
		$this->db->where('ma.id_asuransi', 1);
		$this->db->where('n.id_bank', $id_bank);
		$this->db->where('w.id_karyawan', $id_karyawan);
		$this->db->where('tp.status', 1);
		$this->db->where('tp.ots', 1);
		$this->db->where("CAST(kj.add_time AS VARCHAR) like '%$bulan%' ");

		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.subrogasi_as');

		$hasil_2 = $this->db->get();

		$s 		= 0;
		$r 		= 0;
		$shs 	= 0;

		foreach ($hasil_2->result_array() as $h) {
			$s += $h['subrogasi'];
			$r += $h['tot_recov'];
		}

		$shs = $s-$r;
		
		$value 	= [ 'jml_noa_jbr'	=> $hasil_2->num_rows(),
					'shs_jbr'		=> $shs
					];

		return $value;
	}

	// menampilkan jumlah noa sesuai bulan 
	public function get_data_jml_noa($id_bank, $bulan, $id_karyawan)
	{
		$level = $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('d.nama_debitur');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				//  ->join('ots as o','o.id_debitur = d.id_debitur')
				 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur')
				 ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur');
		$this->db->where('n.id_bank', $id_bank);
		$this->db->where('w.id_karyawan', $id_karyawan);
		// $this->db->where('tp.status', 1);
		// $this->db->where('d.ots', 1);

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$this->db->where("CAST(kj.add_time AS VARCHAR) like '%$bulan%' ");

		$hasil_1 = $this->db->get();

		$this->db->select('d.nama_debitur, (COALESCE(d.subrogasi_as, 0) - COALESCE(d.recoveries_awal_asuransi, 0)) - COALESCE((SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur=d.id_debitur), 0) as shs');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur')
				 ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur');
		$this->db->where('n.id_bank', $id_bank);
		$this->db->where('w.id_karyawan', $id_karyawan);
		$this->db->where('tp.status', 1);
		$this->db->where('tp.ots', 1);
		$this->db->where("CAST(kj.add_time AS VARCHAR) like '%$bulan%' ");

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$hasil_2 = $this->db->get();

		$shs 	= 0;

		foreach ($hasil_2->result_array() as $h) {
			$shs += $h['shs'];
		}
		
		$value 	= [ 'jml_noa_jbr'	=> $hasil_2->num_rows(),
					'shs_jbr'		=> $shs
					];

		return $value;
	}

	// menampilkan report jenis report 2
	public function get_data_nama_ver_2($id_bank)
	{
		$this->db->select('w.nama_lengkap, w.id_karyawan');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan');
		$this->db->where('ma.id_asuransi', 1);
		$this->db->where('n.id_bank', $id_bank);
		$this->db->group_by('w.nama_lengkap');
		$this->db->group_by('w.id_karyawan');
		$this->db->order_by('w.nama_lengkap', 'asc');

		$hasil = $this->db->get()->result_array();

		if ($id_bank == 1) {
			$bank = "Jawa Barat";
		} elseif ($id_bank == 3) {
			$bank = "Jawa Tengah";
		}

		$value 	= array();

		foreach ($hasil as $h) {
			$nama 	= $h['nama_lengkap'];
			$id_kar = $h['id_karyawan'];

			$this->db->select('d.nama_debitur, d.subrogasi_as as subrogasi, sum(r.nominal) as tot_recov');
			$this->db->from('debitur as d');
			$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
					 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
					 ->join('m_bank as n', 'n.id_bank = b.id_bank')
					 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
					 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
					 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
					 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner')
					 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')

					 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur');
			$this->db->where('ma.id_asuransi', 1);
			$this->db->where('n.id_bank', $id_bank);
			$this->db->where('w.id_karyawan', $id_kar);

			$this->db->group_by('d.nama_debitur');
			$this->db->group_by('d.subrogasi_as');

			$hasil_2 = $this->db->get();

			$s 		= 0;
			$r 		= 0;
			$shs 	= 0;

			foreach ($hasil_2->result_array() as $h) {
				$s += $h['subrogasi'];
				$r += $h['tot_recov'];

			}

			$shs = $s-$r;

			$value[] = ['nama_lengkap'	=> $nama,
						'id_karyawan'	=> $id_kar,
						'area'			=> $bank,
						'jml_noa'		=> $hasil_2->num_rows(),
						'shs'			=> $shs
						];

		}

		return $value;
	}

	// menampilkan total kelolaan recov 
	public function get_total_kelolaan_recov($id_karyawan)
	{
		$level 			= $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('d.id_debitur, d.nama_debitur, d.subrogasi_as, d.recoveries_awal_asuransi, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where d.id_debitur = id_debitur)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan');
		$this->db->where('w.id_karyawan', $id_karyawan);

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$this->db->order_by('d.nama_debitur', 'asc');

		$hasil = $this->db->get();

		$subro 		 = 0;
		$recov_awal	 = 0;
		$tot_nominal = 0;

		$value = array();

		foreach ($hasil->result_array() as $h) {
			$subro 			+= $h['subrogasi_as'];
			$recov_awal		+= $h['recoveries_awal_asuransi'];
			$tot_nominal	+= $h['tot_nominal_as'];
		}

		$recov = $recov_awal + $tot_nominal;

		$value = ['recov'	=> $recov,
				  'noa'		=> $hasil->num_rows()
				 ];

		return $value;
	}

	// menampilkan total kelolaan 
	public function get_total_kelolaan($id_karyawan)
	{
		// SELECT d.id_debitur, d.nama_debitur, d.subrogasi_as, d.recoveries_awal_asuransi
		// FROM debitur as d
		// JOIN m_capem_bank as c ON c.id_capem_bank = d.id_capem_bank
		// JOIN m_cabang_bank as b ON b.id_cabang_bank = c.id_cabang_bank
		// JOIN m_bank as n ON n.id_bank = b.id_bank
		// JOIN m_cabang_asuransi as ca ON ca.id_cabang_asuransi = d.id_cabang_as
		// JOIN m_korwil_asuransi as ka ON ka.id_korwil_asuransi = ca.id_korwil_asuransi
		// JOIN m_asuransi as ma ON ma.id_asuransi = ka.id_asuransi
		// JOIN penempatan as p ON p.id_capem_bank = d.id_capem_bank
		// JOIN karyawan as w ON w.id_karyawan = p.id_karyawan
		// WHERE w.id_karyawan = 5 and ca.id_cabang_asuransi = 4

		// ORDER BY nama_debitur asc

		$level 			= $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('d.id_debitur, d.nama_debitur, d.subrogasi_as, d.recoveries_awal_asuransi, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where d.id_debitur = id_debitur)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan');
		$this->db->where('w.id_karyawan', $id_karyawan);
		// $this->db->where('d.ots', 1);

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$this->db->order_by('d.nama_debitur', 'asc');

		$hasil = $this->db->get();

		$subro 		 = 0;
		$recov_awal	 = 0;
		$tot_nominal = 0;

		$value = array();

		foreach ($hasil->result_array() as $h) {
			$subro 			+= $h['subrogasi_as'];
			$recov_awal		+= $h['recoveries_awal_asuransi'];
			$tot_nominal	+= $h['tot_nominal_as'];
		}

		$shs = ($subro - $recov_awal) - $tot_nominal;

		$value = ['shs'	=> $shs,
				  'noa'	=> $hasil->num_rows()
				 ];

		return $value;
	}

	// menampilkan data recoveries bulan 
	public function get_recov_bulan($bulan, $id_karyawan)
	{
		$level 			= $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('d.id_debitur, d.nama_debitur, d.subrogasi_as, d.recoveries_awal_asuransi, k.nominal');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('tr_recov_as as k', 'k.id_debitur = d.id_debitur');

		if ($id_karyawan != '') {
			$this->db->where('w.id_karyawan', $id_karyawan);
		}	 

		$this->db->where("CAST(k.tgl_bayar as VARCHAR) LIKE '%$bulan%'");

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$this->db->order_by('d.nama_debitur', 'asc');

		$hasil = $this->db->get();

		$tot_nominal = 0;

		$value = array();

		foreach ($hasil->result_array() as $h) {
			$tot_nominal	+= $h['nominal'];
		}

		// mencari jumlah noa 
		$this->db->select('d.id_debitur, d.nama_debitur');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('tr_recov_as as k', 'k.id_debitur = d.id_debitur');

		if ($id_karyawan != '') {
			$this->db->where('w.id_karyawan', $id_karyawan);
		}	 

		$this->db->where("CAST(k.tgl_bayar as VARCHAR) LIKE '%$bulan%'");

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$this->db->group_by('d.id_debitur');
		$this->db->group_by('d.nama_debitur');

		$this->db->order_by('d.nama_debitur', 'asc');

		$hasil2 = $this->db->get()->num_rows();
		
		$value = ['recov'	=> $tot_nominal,
				  'noa'		=> $hasil2
				 ];

		return $value;
	}


	public function get_validasi_agunan($bulan, $id_karyawan, $jenis)
	{
		$level 			= $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('d.id_debitur, d.nama_debitur, d.subrogasi_as, d.recoveries_awal_asuransi, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where d.id_debitur = id_debitur)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('kunjungan as k', 'k.id_debitur = d.id_debitur');

		if ($id_karyawan != '') {
			$this->db->where('w.id_karyawan', $id_karyawan);
		}	 

		if ($jenis == 'validasi') {
			$this->db->where('d.ots', 1);
		}

		$this->db->where("CAST(k.add_time as VARCHAR) LIKE '%$bulan%'");

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		$this->db->group_by('d.id_debitur');
		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.subrogasi_as');
		$this->db->group_by('d.recoveries_awal_asuransi');

		$this->db->order_by('d.nama_debitur', 'asc');

		$hasil = $this->db->get();

		$subro 		 = 0;
		$recov_awal	 = 0;
		$tot_nominal = 0;

		$value = array();

		foreach ($hasil->result_array() as $h) {
			$subro 			+= $h['subrogasi_as'];
			$recov_awal		+= $h['recoveries_awal_asuransi'];
			$tot_nominal	+= $h['tot_nominal_as'];
		}

		$shs = ($subro - $recov_awal) - $tot_nominal;

		$value = ['shs'	=> $shs,
				  'noa'	=> $hasil->num_rows()
				 ];

		return $value;
	}

	public function get_nama_verifikator()
	{
		$level = $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		$this->db->select('w.nama_lengkap, w.id_karyawan, w.nik');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan');
		
		$this->db->group_by('w.nama_lengkap');
		$this->db->group_by('w.id_karyawan');
		$this->db->order_by('w.nama_lengkap', 'asc');

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}

		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}

		return $this->db->get();
		
	}

	// menampilkan report jenis report 2
	public function get_data_nama_ver($id_bank)
	{
		$this->db->select('w.nama_lengkap, w.id_karyawan');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan');
		$this->db->where('ma.id_asuransi', 1);
		$this->db->where('n.id_bank', $id_bank);
		$this->db->group_by('w.nama_lengkap');
		$this->db->group_by('w.id_karyawan');
		$this->db->order_by('w.nama_lengkap', 'asc');

		$hasil = $this->db->get()->result_array();

		if ($id_bank == 1) {
			$bank = "Jawa Barat";
		} elseif ($id_bank == 3) {
			$bank = "Jawa Tengah";
		}

		$value 	= array();

		foreach ($hasil as $h) {
			$nama 	= $h['nama_lengkap'];
			$id_kar = $h['id_karyawan'];


			$this->db->select('d.nama_debitur');
			$this->db->from('debitur as d');
			$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
					 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
					 ->join('m_bank as n', 'n.id_bank = b.id_bank')
					 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
					 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
					 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
					 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner')
					 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan');
			$this->db->where('ma.id_asuransi', 1);
			$this->db->where('n.id_bank', $id_bank);
			$this->db->where('w.id_karyawan', $id_kar);

			$hasil_1 = $this->db->get();

			$this->db->select('d.nama_debitur, (COALESCE(d.subrogasi_as, 0) - COALESCE(d.recoveries_awal_asuransi, 0)) - COALESCE((SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur=d.id_debitur), 0) as shs');
			$this->db->from('debitur as d');
			$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
					 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
					 ->join('m_bank as n', 'n.id_bank = b.id_bank')
					 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
					 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
					 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
					 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner')
					 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
					 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur')
					 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur')
            		 ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan');
			$this->db->where('ma.id_asuransi', 1);
			$this->db->where('tp.ots', 1);
			$this->db->where('tp.status', 1);
			$this->db->where('n.id_bank', $id_bank);
			$this->db->where('w.id_karyawan', $id_kar);

			$hasil_2 = $this->db->get();

			$shs 	= 0;

			foreach ($hasil_2->result_array() as $h) {
				$shs += $h['shs'];

			}

			$value[] = ['nama_lengkap'	=> $nama,
						'id_karyawan'	=> $id_kar,
						'area'			=> $bank,
						'jml_noa'		=> $hasil_1->num_rows(),
						'shs'			=> $shs
						];

		}

		return $value;
	}

	public function get_data_jml_noa_s_baru($id_bank, $bulan)
	{
			$this->db->select('d.nama_debitur, d.subrogasi_as as subrogasi, sum(r.nominal) as tot_recov');
			$this->db->from('debitur as d');
			$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
					 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
					 ->join('m_bank as n', 'n.id_bank = b.id_bank')
					 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
					 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
					 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
					 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner')
					 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
					 ->join('ots as o','o.id_debitur = d.id_debitur')
					 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur');
			$this->db->where('ma.id_asuransi', 1);
			$this->db->where('n.id_bank', $id_bank);
			$this->db->where("CAST(o.add_time AS VARCHAR) like '%$bulan%' ");

			$this->db->group_by('d.nama_debitur');
			$this->db->group_by('d.subrogasi_as');

			$hasil_2 = $this->db->get();

			$s 		= 0;
			$r 		= 0;
			$shs 	= 0;

			foreach ($hasil_2->result_array() as $h) {
				$s += $h['subrogasi'];
				$r += $h['tot_recov'];
			}

			$shs = $s-$r;

			$value = ['jml_noa_s'		=> $hasil_2->num_rows(),
						'shs_s'			=> $shs
						];
		return $value;
	}

	public function get_data_report_4($tgl_awal, $tgl_akhir, $verifikator)
	{
		$level = $this->session->userdata('level');

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		// SELECT d.nama_debitur, kj.add_time as tgl_ots,n.bank, c.capem_bank as kantorcabangbjb, ma.asuransi, r.tgl_bayar, COALESCE((SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur=d.id_debitur), 0) + COALESCE((d.recoveries_awal_asuransi), 0) as tot_recov
		// FROM debitur as d
		// JOIN m_capem_bank as c ON c.id_capem_bank = d.id_capem_bank
		// JOIN m_cabang_bank as b ON b.id_cabang_bank = c.id_cabang_bank
		// JOIN m_bank as n ON n.id_bank = b.id_bank
		// JOIN m_cabang_asuransi as ca ON ca.id_cabang_asuransi = d.id_cabang_as
		// JOIN m_korwil_asuransi as ka ON ka.id_korwil_asuransi = ca.id_korwil_asuransi
		// JOIN m_asuransi as ma ON ma.id_asuransi = ka.id_asuransi
		// JOIN penempatan as p ON p.id_capem_bank = d.id_capem_bank
		// JOIN karyawan as w ON w.id_karyawan = p.id_karyawan
		// JOIN kunjungan as kj ON kj.id_debitur = d.id_debitur
		// JOIN tr_potensial as tp ON tp.id_kunjungan = kj.id_kunjungan
		// JOIN tr_recov_as as r ON r.id_debitur = d.id_debitur
		// WHERE w.id_karyawan = 1 and r.tgl_bayar::VARCHAR BETWEEN '2019-03' AND '2019-12' and ma.id_asuransi = 1 and tp.status = 1 and tp.ots = 1
		// ORDER BY nama_debitur asc

		$select = [	'kj.add_time as tgl_ots', 
					'r.tgl_bayar',
					'd.nama_debitur', 
					'n.bank', 
					'b.cabang_bank',
					'c.capem_bank',
					'ca.cabang_asuransi',
					'COALESCE((SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur=d.id_debitur), 0) + COALESCE((d.recoveries_awal_asuransi), 0) as tot_recov_as'
				];

		$this->db->select($select);
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur')
				 ->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan')
				 ->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur');
		$this->db->where('w.id_karyawan', $verifikator);
		// $this->db->where('tp.status', 1);
		// $this->db->where('d.ots', 1);
		$this->db->where("CAST(r.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");

		if ($level == 10) {
			$this->db->where('ma.id_asuransi', 2);
		} else {
			$this->db->where('ma.id_asuransi', 1);
		}
		
		if ($level == 13 || $level == 14) {
			//$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		}
		
		$this->db->order_by('r.tgl_bayar', 'asc');

		return $this->db->get();

	}

	public function get_total_noa($bulan_awal, $bulan_akhir, $id_karyawan)
	{
		$this->db->select("d.nama_debitur,d.subrogasi_as as subrogasi, sum(r.nominal) as tot_recov");
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('ots as o','o.id_debitur = d.id_debitur')
				 ->join('tr_recov_as as r', 'r.id_debitur = o.id_debitur');
		$this->db->where('ma.id_asuransi', 1);
		$this->db->where('w.id_karyawan', $id_karyawan);
		$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$bulan_awal' AND '$bulan_akhir+1'");
		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.subrogasi_as');

		return $this->db->count_all_results();
	}

	public function get_tot_shs($bulan_awal, $bulan_akhir, $id_karyawan)
	{
		$this->db->select("d.nama_debitur, d.subrogasi_as as subrogasi, sum(r.nominal) as tot_recov");
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('ots as o','o.id_debitur = d.id_debitur')
				 ->join('tr_recov_as as r', 'r.id_debitur = o.id_debitur');
		$this->db->where('ma.id_asuransi', 1);
		$this->db->where('w.id_karyawan', $id_karyawan);
		$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$bulan_awal' AND '$bulan_akhir+1'");
		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.subrogasi_as');

		$tot_re = $this->db->get()->result_array();

		$tot_recov = 0;

		foreach ($tot_re as $a) {
			$tot_recov += $a['tot_recov'];
		}

		$this->db->select("d.nama_debitur, d.subrogasi_as as subrogasi");
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
				 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
				 ->join('m_bank as n', 'n.id_bank = b.id_bank')
				 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
				 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
				 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
				 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
				 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan')
				 ->join('ots as o','o.id_debitur = d.id_debitur')
				 ->join('tr_recov_as as r', 'r.id_debitur = o.id_debitur');
		$this->db->where('ma.id_asuransi', 1);
		$this->db->where('w.id_karyawan', $id_karyawan);
		$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$bulan_awal' AND '$bulan_akhir+1'");
		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.subrogasi_as');

		$tot_su 	= $this->db->get()->result_array();

		$tot_subro = 0;
		foreach ($tot_su as $b) {
			$tot_subro += $b['subrogasi'];
		}

		$value 		= array();
		$tot_shs 	= 0;

		$tot_shs 	= $tot_subro - $tot_recov;

		$value 		= ['total_shs'	=> $tot_shs ];

		return $value;
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

	// report 2
	public function get_data_report_1($bulan_awal, $bulan_akhir)
	{
		$b= substr($bulan_awal, -2);
		$z=ltrim($b, '0');

		$c= substr($bulan_akhir, -2);
		$c=ltrim($c, '0');

		$tahun = substr($bulan_awal, 0, 4);

		$bulan = ['bulan','Januari', 'Februari', 'Maret' ,'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November' ,'Desember'];

		$this->db->order_by('nama_lengkap', 'asc');
		$karyawan = $this->db->get('karyawan')->result_array();

		$value 		= array();
		$bulan_r 	= array();

		for ($i = $z; $i <= $c ; $i++) {
			array_push($bulan_r, $bulan[$i]);
		}

		foreach ($karyawan as $k) {
			$nama_karyawan 	= $k['nama_lengkap'];
			$id_karyawan 	= $k['id_karyawan'];

			$i=0; $x=$z;
			foreach ($bulan_r as $b) {
				$bu = $b;

				$bl 	= $this->tambah_bulan($bulan_awal,$i);

				$this->db->select("d.nama_debitur, d.subrogasi_as as subrogasi, sum(r.nominal) as tot_recov");
				$this->db->from('debitur as d');
				$this->db->join('m_capem_bank as c', 'c.id_capem_bank = d.id_capem_bank')
						 ->join('m_cabang_bank as b', 'b.id_cabang_bank = c.id_cabang_bank')
						 ->join('m_bank as n', 'n.id_bank = b.id_bank')
						 ->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as')
						 ->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi')
						 ->join('m_asuransi as ma', 'ma.id_asuransi = ka.id_asuransi')
						 ->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank')
						 ->join('karyawan as w', 'w.id_karyawan = p.id_karyawan','inner')
						 ->join('ots as o','o.id_debitur = d.id_debitur','inner')
						 ->join('tr_recov_as as r', 'r.id_debitur = o.id_debitur','inner');
				$this->db->where('ma.id_asuransi', 1);
				$this->db->where('w.id_karyawan', $id_karyawan);
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$bl%' ");
				$this->db->group_by('d.nama_debitur');
				$this->db->group_by('d.subrogasi_as');

				$noa = $this->db->get();	

				$shs 	= 0;
				$tot_r 	= 0;

				foreach ($noa->result_array() as $h) {

					$subrogasi = $h['subrogasi'];
					$tot_r 	   = $h['tot_recov'];

					$shs = $subrogasi - $tot_r;

				}

				if (($noa->num_rows()) == 0) {
					$s = 0;
				} else {
					$s = $shs;
				}

				$value[] = ['index'		   	=> $x,
							'nama_lengkap' 	=> $nama_karyawan,
							'id_karyawan'	=> $id_karyawan,
							'bulan'		   	=> $bu,
							'jml_noa_ots'  	=> $noa->num_rows(),
							'shs'			=> $s,
							'noa_bayar'		=> $tot_r
							];

				
			$i++; $x++;
			}

			
		}

		return $value;
	}

}

/* End of file M_all_report.php */
/* Location: ./application/models/M_all_report.php */