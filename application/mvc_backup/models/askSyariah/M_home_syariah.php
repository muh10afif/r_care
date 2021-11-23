<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_home_syariah extends CI_Model {

	public function get_cari_data_recov_cukup($bln)
	{
		$this->db->select('k.nama_lengkap,cap.capem_bank, cab.cabang_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		
		$this->db->where("CAST(r.add_time as VARCHAR) like '%$bln%' ");
		$this->db->where('asr.id_asuransi', 2);

		$this->db->group_by('k.id_karyawan')->group_by('cap.id_capem_bank')->group_by('cab.id_cabang_bank');
		$this->db->order_by('total_recoveries', 'desc');

		$angka = $this->db->count_all_results();

		$hasil = $angka - 4;

		if ($hasil <= 0) {
			$hasil = 0;
		} else {
			$hasil;
		}

		$this->db->select('k.nama_lengkap,cap.capem_bank, cab.cabang_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

		$this->db->where("CAST(r.add_time as VARCHAR) like '%$bln%' ");
		$this->db->where('asr.id_asuransi', 2);

		$this->db->group_by('k.id_karyawan')->group_by('cap.id_capem_bank')->group_by('cab.id_cabang_bank');
		$this->db->order_by('total_recoveries', 'desc');

		// limit (1,2) => artinya menampikan 1 data mulai dari data ke 2 yaitu data ke 3 ( karena mulai dari index ke 0 )
		$this->db->limit($hasil,2);

		return $this->db->get()->result_array();

	}

	public function get_data_recov_cukup()
	{
		$this->db->select('k.nama_lengkap,cap.capem_bank, cab.cabang_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

		$this->db->where('asr.id_asuransi', 2);

		$this->db->group_by('k.id_karyawan')->group_by('cap.id_capem_bank')->group_by('cab.id_cabang_bank');
		$this->db->order_by('total_recoveries', 'desc');

		$angka = $this->db->count_all_results();

		$hasil = $angka - 20;

		if ($hasil <= 0) {
			$hasil = 0;
		} else {
			$hasil;
		}

		$this->db->select('k.nama_lengkap,cap.capem_bank, cab.cabang_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

		$this->db->where('asr.id_asuransi', 2);

		$this->db->group_by('k.id_karyawan')->group_by('cap.id_capem_bank')->group_by('cab.id_cabang_bank');
		$this->db->order_by('total_recoveries', 'desc');


		$this->db->limit($hasil,10);

		return $this->db->get()->result_array();

	}

	public function get_cari_data_recov($kinerja,$bln)
	{
		$this->db->select('cap.capem_bank, cab.cabang_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

		$this->db->where("CAST(r.add_time as VARCHAR) like '%$bln%' ");
		$this->db->where('asr.id_asuransi', 2);

		$this->db->group_by('cap.id_capem_bank')->group_by('cab.id_cabang_bank');

		if ($kinerja == 'baik') {
			$this->db->order_by('total_recoveries', 'desc');
		} elseif ($kinerja == 'buruk') {
			$this->db->order_by('total_recoveries', 'asc');
		}
		
		
		$this->db->limit(2);

		return $this->db->get()->result_array();
	}

	public function get_data_recov($kinerja)
	{
		
		$this->db->select('cap.capem_bank, cab.cabang_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		
		$this->db->where('asr.id_asuransi', 2);

		$this->db->group_by('cap.id_capem_bank')->group_by('cab.id_cabang_bank');

		if ($kinerja == 'baik') {
			$this->db->order_by('total_recoveries', 'desc');
		} elseif ($kinerja == 'buruk') {
			$this->db->order_by('total_recoveries', 'asc');
		}
		
		
		$this->db->limit(10);

		return $this->db->get()->result_array();
	}

	/*

	SELECT cab.cabang_bank,  "sum"(r.nominal) as total_recoveries
	FROM debitur as d
	LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = d.id_capem_bank
	LEFT JOIN m_cabang_bank as cab ON cab.id_cabang_bank = cap.id_cabang_bank
	LEFT JOIN recoveries as r ON r.id_debitur = d.id_debitur
	GROUP BY cab.id_cabang_bank
	ORDER BY total_recoveries DESC

	*/

	public function get_recov_cabang($angka)
	{
		$this->db->select('cab.cabang_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

		$this->db->where('asr.id_asuransi', 2);

		$this->db->group_by('cab.id_cabang_bank');

		if ($angka == 'terbesar') {
			$this->db->order_by('total_recoveries', 'desc');
		} else {
			$this->db->order_by('total_recoveries', 'asc');
		}

		$this->db->limit(1);

		return $this->db->get()->row_array();
	}

	public function get_cari_recov_cabang($angka,$bln)
	{
		$this->db->select('k.nama_lengkap, cab.cabang_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
/*
		$this->db->like("CAST('r.add_time as VARCHAR')", $bln, 'BOTH');
*/
		$this->db->where("CAST(r.add_time as VARCHAR) like '%$bln%' ");
		$this->db->where('asr.id_asuransi', 2);

		$this->db->group_by('k.id_karyawan')->group_by('cab.id_cabang_bank');

		if ($angka == 'terbesar') {
			$this->db->order_by('total_recoveries', 'desc');
		} else {
			$this->db->order_by('total_recoveries', 'asc');
		}

		$this->db->limit(1);

		return $this->db->get()->row_array();
	}

	public function get_cari_recov_capem($angka,$bln)
	{
		$this->db->select('k.nama_lengkap, cap.capem_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

		$this->db->where("CAST(r.add_time as VARCHAR) like '%$bln%' ");
		$this->db->where('asr.id_asuransi', 2);

		$this->db->group_by('k.id_karyawan')->group_by('cap.id_capem_bank');

		if ($angka == 'terbesar') {
			$this->db->order_by('total_recoveries', 'desc');
		} else {
			$this->db->order_by('total_recoveries', 'asc');
		}

		$this->db->limit(1);

		return $this->db->get()->row_array();
	}

	public function get_recov_capem($angka)
	{
		$this->db->select('k.nama_lengkap, cap.capem_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

		$this->db->where('asr.id_asuransi', 2);

		$this->db->group_by('k.id_karyawan')->group_by('cap.id_capem_bank');

		if ($angka == 'terbesar') {
			$this->db->order_by('total_recoveries', 'desc');
		} else {
			$this->db->order_by('total_recoveries', 'asc');
		}

		$this->db->limit(1);

		return $this->db->get()->row_array();
	}

}

/* End of file M_home.php */
/* Location: ./application/models/M_home.php */