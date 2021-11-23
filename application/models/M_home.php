<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_home extends CI_Model {

	public function get_data($tabel)
	{
		return $this->db->get($tabel);
	}

	public function get_data_order($tabel, $field, $urut)
	{
		$this->db->order_by($field, $urut);
		
		return $this->db->get($tabel);
	}

	public function cari_data($tabel, $where)
	{
		return $this->db->get_where($tabel, $where);
		
	}

	// mencari bank menurut user asuransi 
	public function get_data_bank($tabel, $id_cabang_as, $level)
	{
		$this->db->select('b.bank, b.id_bank');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

		if ($level == 'asuransi') {
			$this->db->where('d.id_cabang_as', $id_cabang_as);
		}
		if ($level == 'syariah') {
			$this->db->where('asr.id_asuransi', 2);
		}
		
		$this->db->group_by('b.id_bank');
		$this->db->group_by('b.bank');
		
		return $this->db->get();
		
	}

	// mencari id asuransi
	public function cari_id_asuransi($id_cabang_as)
	{
		$this->db->select('ka.id_asuransi');
		$this->db->from('m_cabang_asuransi as ca');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'inner');
		$this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
	
		return $this->db->get();
		
	}

	// Untuk ambil data 

	public function cari_cab_asuransi($id_asuransi, $id_cabang_as)
    {
        $this->db->select('c.id_cabang_asuransi, c.cabang_asuransi');
        $this->db->from('m_asuransi as s');
        $this->db->join('m_korwil_asuransi as k', 'k.id_asuransi = s.id_asuransi', 'inner');
        $this->db->join('m_cabang_asuransi as c', 'c.id_korwil_asuransi = k.id_korwil_asuransi', 'inner');
		$this->db->where('s.id_asuransi', $id_asuransi);
		
		if ($id_cabang_as != '') {
			$this->db->where('c.id_cabang_asuransi', $id_cabang_as);
		}
        
        return $this->db->get();
        
    }

    // mencari cabang bank
    public function cari_cab_bank($id_bank)
    {
		$id_cabang_as = $this->session->userdata('cabang_as');

		if ($id_cabang_as != '') {

			$this->db->select('cab.cabang_bank, cab.id_cabang_bank');
			$this->db->from('debitur as d');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
			$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
			$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
			$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

			$this->db->where('d.id_cabang_as', $id_cabang_as);
			$this->db->where('b.id_bank', $id_bank);

			$this->db->group_by('cab.cabang_bank');
			$this->db->group_by('cab.id_cabang_bank');
		
		} else {
			$this->db->select('cb.cabang_bank, cb.id_cabang_bank');
			$this->db->from('m_bank as b');
			$this->db->join('m_cabang_bank as cb', 'cb.id_bank = b.id_bank', 'inner');
			$this->db->where('b.id_bank', $id_bank);
		}
        
        return $this->db->get();
        
    }

    // mencari capem bank
    public function cari_cap_bank($id_cabang_bank)
    {
		$id_cabang_as = $this->session->userdata('cabang_as');

		if ($id_cabang_as != '') {

			$this->db->select('cap.capem_bank, cap.id_capem_bank');
			$this->db->from('debitur as d');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
			$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
			$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
			$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

			$this->db->where('d.id_cabang_as', $id_cabang_as);
			$this->db->where('cab.id_cabang_bank', $id_cabang_bank);

			$this->db->group_by('cap.capem_bank');
			$this->db->group_by('cap.id_capem_bank');
		
		} else {
			$this->db->select('c.id_capem_bank, c.capem_bank');
			$this->db->from('m_cabang_bank as cb');
			$this->db->join('m_capem_bank as c', 'c.id_cabang_bank = cb.id_cabang_bank', 'inner');
			$this->db->where('cb.id_cabang_bank', $id_cabang_bank);
		}
        
        return $this->db->get();
	}
	
	// mencari verfikator
	public function cari_ver($id_capem_bank)
	{
		$this->db->select('k.id_karyawan, k.nama_lengkap');
		$this->db->from('penempatan p');
		$this->db->join('karyawan k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->where('p.id_capem_bank', $id_capem_bank);
		
		$this->db->group_by('k.id_karyawan')->group_by('k.nama_lengkap');
		
		return $this->db->get();
	}
	
	// Akhir untuk ambil data

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

	// tagihan dan recov bank 
	public function get_tagihan_recov_bank($dt, $aksi)
	{
		// if ($aksi == 'tagihan') {
		// 	$this->db->select('(COALESCE((select sum(besar_klaim) as tot_besar_klaim FROM debitur), 0) - COALESCE((SELECT sum(recoveries_awal_bank) as tot_recov_bank FROM debitur),0)) - COALESCE((select sum(nominal) as tot_nominal_bank FROM tr_recov_bank),0)  as total_tagihan ');
		// } else {
		// 	$this->db->select('COALESCE((SELECT sum(recoveries_awal_bank) as tot_recov_bank FROM debitur),0) + COALESCE((select sum(nominal) as tot_nominal_bank FROM tr_recov_bank),0) as total_recoveries ');
		// }

		if ($aksi == 'besar_klaim') {
			$this->db->select('sum(d.besar_klaim) as tot_besar_klaim');
		} elseif ($aksi == 'recov_awal') {
			$this->db->select('sum(d.recoveries_awal_bank) as tot_recov_awal_bank');
		} else {
			$this->db->select('sum(r.nominal) as tot_nominal_bank');
		}
		
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('tr_recov_bank as r', 'r.id_debitur = d.id_debitur', 'inner');
		$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan AS p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
		$this->db->join('karyawan AS k', 'k.id_karyawan = p.id_karyawan ', 'inner');

		if ($dt['level'] == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($dt['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $dt['asuransi']);
			}
		}

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}
		
		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');
		
		if ($dt['level'] == 'asuransi') {
			//$this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		} else {
			if ($dt['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
			}
		}

		if ($dt['bank'] != 'a') {
			$this->db->where('b.id_bank', $dt['bank']);
		}
		if ($dt['cabang_bank'] != 'a') {
			$this->db->where('cab.id_cabang_bank', $dt['cabang_bank']);
		}
		if ($dt['capem_bank'] != 'a') {
			$this->db->where('cap.id_capem_bank', $dt['capem_bank']);
		}
		if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

			$tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
			$tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

			$this->db->where("CAST(r.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		return $this->db->get();
	}

	// shs dan recov asuransi
	public function get_shs_recov_asuransi($dt, $aksi)
	{
		// if ($aksi == 'shs') {
		// 	$this->db->select('(COALESCE((select sum(subrogasi_as) as tot_subrogasi FROM debitur), 0) - COALESCE((SELECT sum(recoveries_awal_asuransi) as tot_recov_as FROM debitur),0)) - COALESCE((select sum(nominal) as tot_nominal_as FROM tr_recov_as),0)  as total_shs ');
		// } else {
		// 	$this->db->select('COALESCE((SELECT sum(recoveries_awal_asuransi) as tot_recov_as FROM debitur),0) + COALESCE((select sum(nominal) as tot_nominal_as FROM tr_recov_as),0) as total_recoveries ');
		// }

		if ($aksi == 'subro') {
			$this->db->select('sum(d.subrogasi_as) as tot_subrogasi');
		} elseif ($aksi == 'recov_awal') {
			$this->db->select('sum(d.recoveries_awal_asuransi) as tot_recov_awal_as');
		} else {
			$this->db->select('sum(r.nominal) as tot_nominal_as');
		}
		
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur', 'inner');
		$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan AS p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
		$this->db->join('karyawan AS k', 'k.id_karyawan = p.id_karyawan ', 'inner');

		if ($dt['level'] == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($dt['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $dt['asuransi']);
			}
		}

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');
		
		if ($dt['level'] == 'asuransi') {
			//$this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		} else {
			if ($dt['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
			}
		}

		if ($dt['bank'] != 'a') {
			$this->db->where('b.id_bank', $dt['bank']);
		}
		if ($dt['cabang_bank'] != 'a') {
			$this->db->where('cab.id_cabang_bank', $dt['cabang_bank']);
		}
		if ($dt['capem_bank'] != 'a') {
			$this->db->where('cap.id_capem_bank', $dt['capem_bank']);
		}
		if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

			$tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
			$tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

			$this->db->where("CAST(r.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		return $this->db->get();
	}

	public function get_karyawan_default($dt)
	{
		// SELECT k.nama_lengkap, k.file_foto, (select sum(t.nominal) as total_recoveries FROM tr_recov_as as t join debitur as de ON de.id_debitur = t.id_debitur JOIN m_capem_bank as cp ON cp.id_capem_bank = de.id_capem_bank JOIN penempatan AS pe ON pe.id_capem_bank = cp.id_capem_bank WHERE pe.id_karyawan = p.id_karyawan)
		// FROM debitur as d
		// JOIN m_capem_bank as cap ON cap.id_capem_bank = d.id_capem_bank
		// JOIN m_cabang_bank as cab ON cab.id_cabang_bank = cap.id_cabang_bank
		// JOIN m_bank as b ON b.id_bank = cab.id_bank
		// JOIN m_cabang_asuransi AS asu ON asu.id_cabang_asuransi = d.id_cabang_as
		// JOIN m_korwil_asuransi AS kor ON kor.id_korwil_asuransi = asu.id_korwil_asuransi
		// JOIN m_asuransi AS asr ON asr.id_asuransi = kor.id_asuransi
		// JOIN penempatan AS p ON p.id_capem_bank = cap.id_capem_bank
		// JOIN karyawan AS k ON k.id_karyawan = p.id_karyawan

		// GROUP BY k.nama_lengkap, k.file_foto, p.id_karyawan

		// ORDER BY total_recoveries desc

		$this->db->select('k.nama_lengkap as nama, k.file_foto, (select sum(t.nominal) as total_recoveries FROM tr_recov_as as t join debitur as de ON de.id_debitur = t.id_debitur JOIN m_capem_bank as cp ON cp.id_capem_bank = de.id_capem_bank JOIN penempatan AS pe ON pe.id_capem_bank = cp.id_capem_bank WHERE pe.id_karyawan = p.id_karyawan)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan AS p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
		$this->db->join('karyawan AS k', 'k.id_karyawan = p.id_karyawan ', 'inner');

		if ($dt['level'] == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($dt['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $dt['asuransi']);
			}
		}

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}
		
		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');
		
		if ($dt['level'] == 'asuransi') {
			//$this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		} else {
			if ($dt['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
			}
		}

		if ($dt['bank'] != 'a') {
			$this->db->where('b.id_bank', $dt['bank']);
		}
		if ($dt['cabang_bank'] != 'a') {
			$this->db->where('cab.id_cabang_bank', $dt['cabang_bank']);
		}
		if ($dt['capem_bank'] != 'a') {
			$this->db->where('cap.id_capem_bank', $dt['capem_bank']);
		}
		if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

			$tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
			$tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

			$this->db->where("CAST(r.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$this->db->group_by('k.file_foto')->group_by('k.nama_lengkap')->group_by('p.id_karyawan');

		$this->db->order_by('total_recoveries', 'desc');

		$this->db->limit(1);

		return $this->db->get();
		

		// $this->db->select('sum(r.nominal) as total_recoveries, k.file_foto, k.id_karyawan, k.nama_lengkap');
		// $this->db->from('karyawan as k');
		// $this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		// $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		// $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		// $this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		// $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		// $this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'INNER');
		// $this->db->group_by('k.file_foto');
		// $this->db->group_by('k.id_karyawan');
		// $this->db->group_by('k.nama_lengkap');
		// $this->db->order_by('total_recoveries', 'desc');

		// $b = $this->db->get()->row_array();

		// $this->db->select('k.nama_lengkap,cap.capem_bank, cab.cabang_bank');
		// $this->db->from('karyawan as k');
		// $this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		// $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		// $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		// $this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		// $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		// // $this->db->join('ots as r', 'r.id_debitur = d.id_debitur', 'INNER');
		// $this->db->join('kunjungan as ku', 'ku.id_debitur = d.id_debitur', 'inner');

		// $c = $this->db->get()->num_rows();

		// $value = array();

		// $value = ['file_foto'	=> $b['file_foto'],
		// 		  'nama'		=> $b['nama_lengkap'],
		// 		  'id_karyawan'	=> $b['id_karyawan'],
		// 		  'jml_recov'	=> $b['total_recoveries'],
		// 		  'jml_ots'		=> $c
		// 		];

		// return $value;
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

	public function get_data_recov($dt, $kinerja)
	{
		$this->db->select('cab.cabang_bank, cap.capem_bank, (select sum(t.nominal) as total_recoveries FROM tr_recov_as as t join debitur as de ON de.id_debitur = t.id_debitur JOIN m_capem_bank as cp ON cp.id_capem_bank = de.id_capem_bank where cp.id_cabang_bank = cab.id_cabang_bank)');

		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan AS p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
		$this->db->join('karyawan AS k', 'k.id_karyawan = p.id_karyawan ', 'inner');

		if ($dt['level'] == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($dt['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $dt['asuransi']);
			}
		}

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}
		
		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');
		
		if ($dt['level'] == 'asuransi') {
			//$this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		} else {
			if ($dt['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
			}
		}

        if ($dt['bank'] != 'a') {
            $this->db->where('b.id_bank', $dt['bank']);
        }
        if ($dt['cabang_bank'] != 'a') {
            $this->db->where('cab.id_cabang_bank', $dt['cabang_bank']);
        }
        if ($dt['capem_bank'] != 'a') {
            $this->db->where('cap.id_capem_bank', $dt['capem_bank']);
        }
        if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

            $tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
            $tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

            $this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$this->db->group_by('cap.id_capem_bank');
		$this->db->group_by('cab.id_cabang_bank');

		if ($kinerja == 'baik') {
			$this->db->order_by('total_recoveries', 'desc');
		} elseif ($kinerja == 'kurang') {
			$this->db->order_by('total_recoveries', 'asc');
		}
		
		$this->db->limit(5);

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

	// menampilkan cabang asuransi recoveries
	public function get_recov_asuransi_pie($dt)
	{
		$this->db->select('asu.cabang_asuransi, (select sum(t.nominal) as total_recoveries FROM tr_recov_as as t join debitur as de ON de.id_debitur = t.id_debitur where de.id_cabang_as = asu.id_cabang_asuransi)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan AS p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
		$this->db->join('karyawan AS k', 'k.id_karyawan = p.id_karyawan ', 'inner');

		if ($dt['level'] == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($dt['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $dt['asuransi']);
			}
		}

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}
		
		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');
		
		if ($dt['level'] == 'asuransi') {
			//$this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		} else {
			if ($dt['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
			}
		}

		if ($dt['bank'] != 'a') {
			$this->db->where('b.id_bank', $dt['bank']);
		}
		if ($dt['cabang_bank'] != 'a') {
			$this->db->where('cab.id_cabang_bank', $dt['cabang_bank']);
		}
		if ($dt['capem_bank'] != 'a') {
			$this->db->where('cap.id_capem_bank', $dt['capem_bank']);
		}
		if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

			$tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
			$tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

			$this->db->where("CAST(r.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
		}

		$this->db->group_by('asu.id_cabang_asuransi');

		return $this->db->get();
	}

	public function get_recov_bank_pie($dt)
	{
		$this->db->select('cab.cabang_bank, (select sum(t.nominal) as total_recoveries FROM tr_recov_as as t join debitur as de ON de.id_debitur = t.id_debitur JOIN m_capem_bank as cp ON cp.id_capem_bank = de.id_capem_bank where cp.id_cabang_bank = cab.id_cabang_bank)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan AS p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
		$this->db->join('karyawan AS k', 'k.id_karyawan = p.id_karyawan ', 'inner');

		if ($dt['level'] == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($dt['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $dt['asuransi']);
			}
		}

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');
		
		if ($dt['level'] == 'asuransi') {
			//$this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		} else {
			if ($dt['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
			}
		}

		if ($dt['bank'] != 'a') {
			$this->db->where('b.id_bank', $dt['bank']);
		}
		if ($dt['cabang_bank'] != 'a') {
			$this->db->where('cab.id_cabang_bank', $dt['cabang_bank']);
		}
		if ($dt['capem_bank'] != 'a') {
			$this->db->where('cap.id_capem_bank', $dt['capem_bank']);
		}
		if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

			$tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
			$tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

			$this->db->where("CAST(r.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}
		
		$this->db->group_by('cab.id_cabang_bank');

		return $this->db->get();
	}

	public function get_recov_bank_area($dt, $bulan)
	{
		$this->db->select("sum(r.nominal) as total_recoveries");
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan AS p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
		$this->db->join('karyawan AS k', 'k.id_karyawan = p.id_karyawan ', 'inner');
		$this->db->join('tr_recov_bank as r', 'r.id_debitur = d.id_debitur', 'inner');
		
		if ($dt['level'] == 10) {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($dt['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $dt['asuransi']);
			}
		}

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');
		
		if ($dt['level'] == 13) {
			//$this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		} else {
			if ($dt['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
			}
		}

		if ($dt['bank'] != 'a') {
			$this->db->where('b.id_bank', $dt['bank']);
		}
		if ($dt['cabang_bank'] != 'a') {
			$this->db->where('cab.id_cabang_bank', $dt['cabang_bank']);
		}
		if ($dt['capem_bank'] != 'a') {
			$this->db->where('cap.id_capem_bank', $dt['capem_bank']);
		}
		if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

			$tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
			$tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

			$this->db->where("CAST(r.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}
		$this->db->where("CAST(r.tgl_bayar AS VARCHAR) like '%$bulan%'");

		return $this->db->get();
	}

	public function get_recov_asuransi_area($dt, $bulan)
	{
		$this->db->select('sum(r.nominal) as total_recoveries');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur ', 'inner');
		$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan AS p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
		$this->db->join('karyawan AS k', 'k.id_karyawan = p.id_karyawan ', 'inner');

		if ($dt['level'] == 10) {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($dt['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $dt['asuransi']);
			}
		}

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');
		
		if ($dt['level'] == 13) {
			//$this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		} else {
			if ($dt['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
			}
		}

		if ($dt['bank'] != 'a') {
			$this->db->where('b.id_bank', $dt['bank']);
		}
		if ($dt['cabang_bank'] != 'a') {
			$this->db->where('cab.id_cabang_bank', $dt['cabang_bank']);
		}
		if ($dt['capem_bank'] != 'a') {
			$this->db->where('cap.id_capem_bank', $dt['capem_bank']);
		}
		if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

			$tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
			$tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

			$this->db->where("CAST(r.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}
		
		// $this->db->where('asr.id_asuransi', 1);

		$this->db->where("CAST(r.tgl_bayar AS VARCHAR) like '%$bulan%'");

		return $this->db->get();
	}

	public function get_karyawan_filter($dt)
	{
		$this->db->select('k.nama_lengkap, k.file_foto');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur ', 'inner');
		$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan AS p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan AS k', 'k.id_karyawan = p.id_karyawan ', 'left');

		if ($dt['level'] == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($dt['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $dt['asuransi']);
			}
		}
		
		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');
		
		if ($dt['level'] == 'asuransi') {
			//$this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		} else {
			if ($dt['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
			}
		}

		if ($dt['bank'] != 'a') {
			$this->db->where('b.id_bank', $dt['bank']);
		}
		if ($dt['cabang_bank'] != 'a') {
			$this->db->where('cab.id_cabang_bank', $dt['cabang_bank']);
		}
		if ($dt['capem_bank'] != 'a') {
			$this->db->where('cap.id_capem_bank', $dt['capem_bank']);
		}
		if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

			$tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
			$tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

			$this->db->where("CAST(r.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		return $this->db->get();
	}

	// total noa yang sudah dikunjungi
	public function get_total_noa_2($dt)
	{
		$this->db->select('d.id_debitur');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan AS p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan AS k', 'k.id_karyawan = p.id_karyawan ', 'left');
		$this->db->join('kunjungan as j', 'j.id_debitur = d.id_debitur', 'inner');

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}

		if ($dt['level'] == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($dt['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $dt['asuransi']);
			}
		}

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');
		
		if ($dt['level'] == 'asuransi') {
			//$this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		} else {
			if ($dt['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
			}
		}

		if ($dt['bank'] != 'a') {
			$this->db->where('b.id_bank', $dt['bank']);
		}
		if ($dt['cabang_bank'] != 'a') {
			$this->db->where('cab.id_cabang_bank', $dt['cabang_bank']);
		}
		if ($dt['capem_bank'] != 'a') {
			$this->db->where('cap.id_capem_bank', $dt['capem_bank']);
		}
		if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

			$tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
			$tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

			$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$this->db->group_by('d.id_debitur');

		return $this->db->get();
	}

	// total noa
	public function get_total_noa($dt, $aksi)
	{
		$this->db->select('d.nama_debitur');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan AS p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan AS k', 'k.id_karyawan = p.id_karyawan ', 'left');

		if ($aksi == 'sdh_ots') {
			$this->db->where('d.ots', 1);	
		}

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}

		if ($dt['level'] == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($dt['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $dt['asuransi']);
			}
		}

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');
		
		if ($dt['level'] == 'asuransi') {
			//$this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		} else {
			if ($dt['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
			}
		}

		if ($dt['bank'] != 'a') {
			$this->db->where('b.id_bank', $dt['bank']);
		}
		if ($dt['cabang_bank'] != 'a') {
			$this->db->where('cab.id_cabang_bank', $dt['cabang_bank']);
		}
		if ($dt['capem_bank'] != 'a') {
			$this->db->where('cap.id_capem_bank', $dt['capem_bank']);
		}
		if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

			$tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
			$tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

			$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$this->db->group_by('d.id_debitur');
		
		return $this->db->get();
		
	}

	public function get_recov_cabang($dt, $angka)
	{
		// SELECT cab.cabang_bank, (select sum(t.nominal) as tot_nominal_as FROM tr_recov_as as t join debitur as de ON de.id_debitur = t.id_debitur JOIN m_capem_bank as cp ON cp.id_capem_bank = de.id_capem_bank where cp.id_cabang_bank = cab.id_cabang_bank)
		// FROM debitur as d
		// JOIN m_capem_bank as cap ON cap.id_capem_bank = d.id_capem_bank
		// JOIN m_cabang_bank as cab ON cab.id_cabang_bank = cap.id_cabang_bank
		// JOIN m_bank as b ON b.id_bank = cab.id_bank
		// JOIN m_cabang_asuransi AS asu ON asu.id_cabang_asuransi = d.id_cabang_as
		// JOIN m_korwil_asuransi AS kor ON kor.id_korwil_asuransi = asu.id_korwil_asuransi
		// JOIN m_asuransi AS asr ON asr.id_asuransi = kor.id_asuransi
		// JOIN penempatan AS p ON p.id_capem_bank = cap.id_capem_bank
		// JOIN karyawan AS k ON k.id_karyawan = p.id_karyawan

		// GROUP BY cab.id_cabang_bank

		// ORDER BY	tot_nominal_as desc

		$this->db->select('cab.cabang_bank, (select sum(t.nominal) as tot_nominal_as FROM tr_recov_as as t join debitur as de ON de.id_debitur = t.id_debitur JOIN m_capem_bank as cp ON cp.id_capem_bank = de.id_capem_bank where cp.id_cabang_bank = cab.id_cabang_bank)');

		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi AS asu', 'asu.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi AS kor', 'kor.id_korwil_asuransi = asu.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi AS asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan AS p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
		$this->db->join('karyawan AS k', 'k.id_karyawan = p.id_karyawan ', 'inner');

		if ($dt['level'] == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($dt['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $dt['asuransi']);
			}
		}

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}
		
		// $id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');
		
		if ($dt['level'] == 'asuransi') {
			//$this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		} else {
			if ($dt['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
			}
		}

		if ($dt['bank'] != 'a') {
			$this->db->where('b.id_bank', $dt['bank']);
		}
		if ($dt['cabang_bank'] != 'a') {
			$this->db->where('cab.id_cabang_bank', $dt['cabang_bank']);
		}
		if ($dt['capem_bank'] != 'a') {
			$this->db->where('cap.id_capem_bank', $dt['capem_bank']);
		}
		if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

			$tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
			$tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

			$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		// GROUP BY cab.id_cabang_bank

		// ORDER BY	tot_nominal_as desc

		$this->db->group_by('cab.id_cabang_bank');

		if ($angka === 'terbesar') {
			$this->db->order_by('tot_nominal_as', 'DESC');
		} else {
			$this->db->order_by('tot_nominal_as', 'ASC');
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

	// *************** HOME KANWIL SPK ******************** //

	public function get_kanwil_spk($korwil)
	{
		$this->db->select('s.*, ca.*, (select count(id_debitur) as tot_noa from debitur where id_spk = s.id_spk), (select sum(subrogasi_as) as tot_subro from debitur where id_spk = s.id_spk), (select sum(recoveries_awal_asuransi) as tot_recov_awal_as from debitur where id_spk = s.id_spk), (select sum(nominal) as tot_nominal_as from debitur as d join tr_recov_as as t ON t.id_debitur = d.id_debitur where d.id_spk = s.id_spk)');
		$this->db->from('spk as s');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = s.id_cabang_asuransi', 'inner');
		$this->db->where('s.status', 1);
		$this->db->where('ca.id_korwil_asuransi', $korwil);
		
		return $this->db->get();
		
	}

}

/* End of file M_home.php */
/* Location: ./application/models/M_home.php */