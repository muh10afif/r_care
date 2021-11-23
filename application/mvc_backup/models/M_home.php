<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_home extends CI_Model {

	// get_shs_sudah
	public function get_cari_shs_sudah($bank)
	{
		$this->db->select('d.id_debitur, d.subrogasi, sum(r.nominal) as recoveries');
		$this->db->from('debitur as d');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');

		if (!empty($bank)) {
			$this->db->where('b.id_bank', $bank);
		}

		$this->db->group_by('d.id_debitur');

		$hasil = $this->db->get()->result_array();
		$value = array();

		$tot_shs = 0;
		$tot_subro = 0;
		$tot_rec 	= 0;

		foreach ($hasil as $h) {
			$subrogasi 		 = $h['subrogasi'];
			$recoveries 	 = $h['recoveries'];

			$tot_shs += ($subrogasi-$recoveries);

			$tot_subro 	+= $subrogasi;

			$tot_rec += $recoveries;
		}

		$value = [
			'tot_shs_noa'	=> $tot_shs,
			'tot_subro'		=> $tot_subro,
			'tot_rec'		=> $tot_rec
			];

		return $value;
	}


	//total shs semua noa
	public function get_cari_shs_noa($bank)
	{
		$this->db->select('d.id_debitur,d.nama_debitur,d.bunga, ca.cabang_asuransi, d.no_klaim, cab.cabang_bank, d.subrogasi, sum(r.nominal) as recoveries, ms.status_deb, p.nama_proses');
		$this->db->from('debitur as d');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
		$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
		$this->db->join('tr_ots_p as op', 'op.id_ots = o.id_ots', 'left');
		$this->db->join('tr_ots_fu as of', 'of.id_tr_ots_p = op.id_tr_ots_p', 'left');
		$this->db->join('proses as p', 'p.id_proses = of.id_proses', 'left');
		$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
		$this->db->where('o.status', 1);

		if (!empty($bank)) {
			$this->db->where('b.id_bank', $bank);
		}

		$this->db->group_by('d.id_debitur');
		$this->db->group_by('cab.id_cabang_bank');
		$this->db->group_by('ca.id_cabang_asuransi');
		$this->db->group_by('ms.id_status_deb');
		$this->db->group_by('p.id_proses');
		$this->db->order_by('d.nama_debitur', 'asc');

		$hasil = $this->db->get()->result_array();
		$value = array();

		$tot_shs 		= 0;
		$tot_subro 		= 0;
		$tot_subro_deb 	= 0;
		foreach ($hasil as $h) {
			$subrogasi 		 = $h['subrogasi'];
			$recoveries 	 = $h['recoveries'];
			$id_deb 		 = $h['id_debitur'];

			$this->db->select('sum(subrogasi) as total_subro');
			$this->db->from('debitur');
			$this->db->where('id_debitur', $id_deb);

			$av = $this->db->get()->row_array();

			$tot_subro_deb += $av['total_subro'];

			$tot_shs 	+= ($subrogasi-$recoveries);

			$tot_subro 	+= $subrogasi;
		}

		$value = [
			'tot_shs'		=> $tot_shs,
			'tot_subro'		=> $tot_subro,
			'tot_subro_deb'	=> $tot_subro_deb
			];

		return $value;
	}

	// GET TOTAL 
	public function get_cari_total($tabel,$bank)
	{
		if ($tabel == 'ots') { 
			$this->db->distinct();
			$this->db->select("x.id_debitur");
			$this->db->from("$tabel as x");
			$this->db->join('debitur as d', "d.id_debitur = x.id_debitur", 'left');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
			$this->db->where('x.status', 1);

			if (!empty($bank)) {
				$this->db->where('b.id_bank', $bank);
			}

		} else {
			$this->db->distinct();
			$this->db->select('d.id_debitur');
			$this->db->from("$tabel as d");
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');

			if (!empty($bank)) {
				$this->db->where('b.id_bank', $bank);
			}

		}
		
		return $this->db->count_all_results();
	}

	public function get_karyawan_default()
	{
		$this->db->select('sum(r.nominal) as total_recoveries, k.file_foto, k.id_karyawan, k.nama_lengkap');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->group_by('k.file_foto');
		$this->db->group_by('k.id_karyawan');
		$this->db->group_by('k.nama_lengkap');
		$this->db->order_by('total_recoveries', 'desc');

		$b = $this->db->get()->row_array();

		$this->db->select('k.nama_lengkap,cap.capem_bank, cab.cabang_bank');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots as r', 'r.id_debitur = d.id_debitur', 'INNER');

		$c = $this->db->get()->num_rows();

		$value = array();

		$value = ['file_foto'	=> $b['file_foto'],
				  'nama'		=> $b['nama_lengkap'],
				  'id_karyawan'	=> $b['id_karyawan'],
				  'jml_recov'	=> $b['total_recoveries'],
				  'jml_ots'		=> $c
				];

		return $value;
	}

	public function get_karyawan($verifikator, $bank)
	{
		$this->db->from('karyawan');		
		$this->db->where('id_karyawan', $verifikator);

		$hasil = $this->db->get()->result_array();

		$value = array();

		foreach ($hasil as $h) {

			$file_foto 		= $h['file_foto'];
			$id_karyawan	= $h['id_karyawan'];
			$nama 			= $h['nama_lengkap'];

			$this->db->select('sum(r.nominal) as total_recoveries');
			$this->db->from('karyawan as k');
			$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
			$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
			$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
			$this->db->where('k.id_karyawan', $id_karyawan);

			if (!empty($bank)) {
				$this->db->where('b.id_bank', $bank);
			}

			$b = $this->db->get()->row_array();

			$this->db->select('k.nama_lengkap,cap.capem_bank, cab.cabang_bank');
			$this->db->from('karyawan as k');
			$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
			$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
			$this->db->join('ots as r', 'r.id_debitur = d.id_debitur', 'INNER');
			$this->db->where('k.id_karyawan', $id_karyawan);

			if (!empty($bank)) {
				$this->db->where('b.id_bank', $bank);
			}

			$c = $this->db->get()->num_rows();

			$value = ['file_foto'	=> $file_foto,
					  'nama'		=> $nama,
					  'jml_recov'	=> $b['total_recoveries'],
					  'jml_ots'		=> $c
					];

		}

		return $value;
	}

	// menampikan list verifikator menurut bank
	public function get_cari_verifikator($bank)
	{
		$this->db->select('k.*');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->group_by('k.id_karyawan');

		if (!empty($bank)) {
			$this->db->where('b.id_bank', $bank);
		}

		return $this->db->get();
	}

	// menampilkan list bank
	public function get_bank()
	{
		return $this->db->get('m_bank');
	}

	public function get_cari_data_recov_cukup($bank)
	{
		$this->db->select('k.nama_lengkap,cap.capem_bank, cab.cabang_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');

		if (!empty($bank)) {
			$this->db->where('b.id_bank', $bank);
		}

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
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');

		$this->db->where('b.id_bank', $bank);

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

		$this->db->group_by('k.id_karyawan')->group_by('cap.id_capem_bank')->group_by('cab.id_cabang_bank');
		$this->db->order_by('total_recoveries', 'desc');

		$angka = $this->db->count_all_results();

		$hasil = $angka - 20;

		$this->db->select('k.nama_lengkap,cap.capem_bank, cab.cabang_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');

		$this->db->group_by('k.id_karyawan')->group_by('cap.id_capem_bank')->group_by('cab.id_cabang_bank');
		$this->db->order_by('total_recoveries', 'desc');


		$this->db->limit($hasil,10);

		return $this->db->get()->result_array();

	}

	public function get_cari_data_recov($kinerja,$bank)
	{
		$this->db->select('cap.capem_bank, cab.cabang_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');

		if (!empty($bank)) {
			$this->db->where('b.id_bank', $bank);
		}

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

	public function get_cari_recov_cabang_pie($bank, $tgl_awal, $tgl_akhir)
	{
		$this->db->select('c.cabang_asuransi, sum(r.nominal) as total_recoveries');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_cabang_asuransi as c', 'c.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as i', 'i.id_korwil_asuransi = c.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as m', 'm.id_asuransi = i.id_asuransi', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->where('m.id_asuransi', 1);

		if (!empty($bank)) {
			$this->db->where('b.id_bank', $bank);
		}
		if (!empty($tgl_awal) && !empty($tgl_akhir)) {
			$this->db->where("CAST(r.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
		}


		
		$this->db->group_by('c.cabang_asuransi');

		return $this->db->get();
	}

	// menampilkan cabang recoveries
	public function get_recov_cabang_pie()
	{
		$this->db->select('c.cabang_asuransi, sum(r.nominal) as total_recoveries');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_cabang_asuransi as c', 'c.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as i', 'i.id_korwil_asuransi = c.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as m', 'm.id_asuransi = i.id_asuransi', 'left');
		$this->db->where('m.id_asuransi', 1);
		$this->db->group_by('c.cabang_asuransi');

		return $this->db->get();
	}

	public function get_recov_cabang($angka)
	{
		$this->db->select('cab.cabang_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->group_by('cab.id_cabang_bank');

		if ($angka == 'terbesar') {
			$this->db->order_by('total_recoveries', 'desc');
		} else {
			$this->db->order_by('total_recoveries', 'asc');
		}

		$this->db->limit(1);

		return $this->db->get()->row_array();
	}

	public function get_cari_recov_cabang($angka,$bank)
	{
		$this->db->select('k.nama_lengkap, cab.cabang_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');

		if (!empty($bank)) {
			$this->db->where('b.id_bank', $bank);
		}
		
		$this->db->group_by('k.id_karyawan')->group_by('cab.id_cabang_bank');

		if ($angka == 'terbesar') {
			$this->db->order_by('total_recoveries', 'desc');
		} else {
			$this->db->order_by('total_recoveries', 'asc');
		}

		$this->db->limit(1);

		return $this->db->get()->row_array();
	}

	public function get_cari_recov_capem($angka,$bank)
	{
		$this->db->select('k.nama_lengkap, cap.capem_bank, sum(r.nominal) as total_recoveries');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');

		if (!empty($bank)) {
			$this->db->where('b.id_bank', $bank);
		}


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