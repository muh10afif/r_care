<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_ots extends CI_Model {

	// get_shs_sudah
	public function get_shs_sudah()
	{
		$this->db->select('d.id_debitur, d.subrogasi, sum(r.nominal) as recoveries');
		$this->db->from('debitur as d');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'inner');

		$this->db->group_by('d.id_debitur');

		$hasil = $this->db->get()->result_array();
		$value = array();

		$tot_shs 	= 0;
		$tot_subro 	= 0;
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

	// get shs semua noa
	public function get_shs_noa()
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

	public function get_asuransi($id)
	{
		if (!empty($id)) {
			return $this->db->get_where('m_asuransi',array('id_asuransi' => $id));
		} else {
			return $this->db->get('m_asuransi');
		}
		
	}

	public function get_wilayah_capem()
	{
		return $this->db->get('m_cabang_bank');
	}

	public function get_verifikator()
	{
		$this->db->order_by('nama_lengkap', 'asc');
		return $this->db->get('karyawan');
	}

	public function get_data_r_ots()
	{
		$this->db->select('d.*,d.nama_debitur, d.no_klaim, cab.cabang_bank, b.bank, o.alamat as alamat_deb, o.keterangan, msd.status_deb');
		$this->db->from('ots as o');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as msd', 'msd.id_status_deb = osd.id_status_deb', 'left');
		$this->db->where('o.status', 1);

		return $this->db->get();
	}

	// fungsi untuk lihat pdf dan unduh pdf
	public function get_cari_data_unduh_r_ots($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank)
	{
		$this->db->select('d.*, d.id_debitur, d.nama_debitur, b.bank, sp.status_proses as potensi,  mh.tindakan_hukum as proses, cab.cabang_bank, o.keterangan,o.narasumber,o.add_time,o.alamat as alamat_deb,ca.cabang_asuransi, o.telp, a.asuransi,tf.fu');
		$this->db->from('ots as o');
		$this->db->join('tr_ots_p as op', 'op.id_ots = o.id_ots', 'left');
		$this->db->join('status_proses as sp', 'sp.id_status_proses = op.status_proses', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_ots_p = op.id_tr_ots_p', 'left');
		$this->db->join('m_tindakan_hukum as mh', 'mh.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('tr_ots_p as to', 'to.id_ots = o.id_ots', 'left');
		$this->db->join('tr_ots_fu as tf', 'tf.id_tr_ots_p = to.id_tr_ots_p', 'left');
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($wilayah) || !empty($ver) || !empty($bank) || !empty($asuransi)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (!empty($tgl_akhir) && empty($tgl_awal) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($wilayah)) {
				$this->db->where('cab.cabang_bank', $wilayah);
			}
			if (!empty($ver)) {
				$this->db->where('k.nama_lengkap', $ver);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($asuransi)) {
				$this->db->where('a.id_asuransi', $asuransi);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		$hasil = $this->db->get()->result_array(); 	
		$value = array();
		
		foreach ($hasil as $h) {
			$id_debitur 		= $h['id_debitur'];
			$deal_reff 			= $h['no_reff'];
			$nama 	 			= $h['nama_debitur'];
			$potensi 			= $h['potensi'];
			$proses 			= $h['proses'];
			$cabang_bank		= $h['cabang_bank'];
			$alamat 			= $h['alamat_deb'];
			$keterangan			= $h['keterangan'];
			$narasumber			= $h['narasumber'];
			$add_time 			= $h['add_time'];
			$cabang_asuransi 	= $h['cabang_asuransi'];
			$telp 				= $h['telp'];
			$bank 				= $h['bank'];
			$fu 				= $h['fu'];

			$this->db->select('d.id_debitur, jenis_asset');
			$this->db->from('dokumen_asset as d');
			$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = d.id_jenis_asset', 'left');
			$this->db->where('d.id_debitur', $id_debitur);
			$this->db->where('d.id_jenis_dok', '2');
			$this->db->where('d.status', '1');

			$hasil_2 = $this->db->get()->row_array();

			$jenis = $hasil_2['jenis_asset'];

			$value[] = [
				'no_reff'			=> $deal_reff,
				'nama_debitur' 		=> $nama,
				'potensi'			=> $potensi,
				'proses'			=> $proses,
				'cabang_bank'		=> $cabang_bank,
				'alamat'			=> $alamat,
				'keterangan'		=> $keterangan,
				'narasumber'		=> $narasumber,
				'add_time'			=> $add_time,
				'cabang_asuransi'	=> $cabang_asuransi,
				'telp'				=> $telp,
				'jenis_asset'		=> $jenis,
				'bank'				=> $bank,
				'fu'				=> $fu
			];
			
		}

		return $value;
	}

	public function get_cari_data_r_ots($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank)
	{
		$this->db->select('d.nama_debitur, d.no_klaim, b.bank, sp.status_proses as potensi, mh.tindakan_hukum as proses, cab.cabang_bank, o.keterangan,o.narasumber,o.add_time,o.alamat as alamat_deb, msd.status_deb, ca.cabang_asuransi, o.telp, a.asuransi');
		$this->db->from('ots as o');
		$this->db->join('tr_ots_p as op', 'op.id_ots = o.id_ots', 'left');
		$this->db->join('status_proses as sp', 'sp.id_status_proses = op.status_proses', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_ots_p = op.id_tr_ots_p', 'left');
		$this->db->join('m_tindakan_hukum as mh', 'mh.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as msd', 'msd.id_status_deb = osd.id_status_deb', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($wilayah) || !empty($ver) || !empty($bank) || !empty($asuransi)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($wilayah) && empty($ver)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (!empty($tgl_akhir) && empty($tgl_awal) && empty($wilayah) && empty($ver)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($wilayah)) {
				$this->db->where('cab.cabang_bank', $wilayah);
			}
			if (!empty($ver)) {
				$this->db->where('k.nama_lengkap', $ver);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($asuransi)) {
				$this->db->where('a.id_asuransi', $asuransi);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($ver)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		return $this->db->get(); 	
	}

	public function get_peringkat_ver($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank, $asuransi)
	{
		$select = [
			'distinct (k.id_karyawan)',
		    'k.nama_lengkap',
		    'sum(r.nominal) as total_recoveries'
		];

		$this->db->select($select);
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($wilayah) || !empty($ver) || !empty($bank) || !empty($asuransi)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($wilayah) && empty($ver)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (!empty($tgl_akhir) && empty($tgl_awal) && empty($wilayah) && empty($ver)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($wilayah)) {
				$this->db->where('cab.cabang_bank', $wilayah);
			}
			if (!empty($ver)) {
				$this->db->where('k.nama_lengkap', $ver);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($asuransi)) {
				$this->db->where('a.id_asuransi', $asuransi);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($ver)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		$this->db->order_by('total_recoveries', 'desc');
		$this->db->group_by('k.id_karyawan');
		$this->db->limit(10);

		return $this->db->get();
	}

	public function get_total($tabel)
	{
		if ($tabel == 'ots') { 
			$this->db->distinct();
			$this->db->select("$tabel.id_debitur");
			$this->db->from($tabel);
			$this->db->join('debitur as d', "d.id_debitur = $tabel.id_debitur", 'left');
			$this->db->where('status', 1);
		} else {
			$this->db->distinct();
			$this->db->select('id_debitur');
			$this->db->from($tabel);
		}
		
		return $this->db->count_all_results();
	}

	public function get_total_ots($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank, $asuransi)
	{
		$this->db->select('d.id_debitur, d.nama_debitur, b.bank, sp.status_proses as potensi,  mh.tindakan_hukum as proses, cab.cabang_bank, o.keterangan,o.narasumber,o.add_time,o.alamat as alamat_deb,ca.cabang_asuransi, o.telp, a.asuransi');
		$this->db->from('ots as o');
		$this->db->join('tr_ots_p as op', 'op.id_ots = o.id_ots', 'left');
		$this->db->join('status_proses as sp', 'sp.id_status_proses = op.status_proses', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_ots_p = op.id_tr_ots_p', 'left');
		$this->db->join('m_tindakan_hukum as mh', 'mh.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($wilayah) || !empty($ver) || !empty($bank) || !empty($asuransi)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (!empty($tgl_akhir) && empty($tgl_awal) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($wilayah)) {
				$this->db->where('cab.cabang_bank', $wilayah);
			}
			if (!empty($ver)) {
				$this->db->where('k.nama_lengkap', $ver);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($asuransi)) {
				$this->db->where('a.id_asuransi', $asuransi);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		return $this->db->count_all_results();
	}

	public function get_total_noa($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank, $asuransi)
	{
		$this->db->select('d.id_debitur');
		$this->db->from('ots as o');
		$this->db->join('tr_ots_p as op', 'op.id_ots = o.id_ots', 'left');
		$this->db->join('status_proses as sp', 'sp.id_status_proses = op.status_proses', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_ots_p = op.id_tr_ots_p', 'left');
		$this->db->join('m_tindakan_hukum as mh', 'mh.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($wilayah) || !empty($ver) || !empty($bank) || !empty($asuransi)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (!empty($tgl_akhir) && empty($tgl_awal) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($wilayah)) {
				$this->db->where('cab.cabang_bank', $wilayah);
			}
			if (!empty($ver)) {
				$this->db->where('k.nama_lengkap', $ver);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($asuransi)) {
				$this->db->where('a.id_asuransi', $asuransi);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		$this->db->group_by('d.id_debitur');

		return $this->db->count_all_results();
	}

}

/* End of file M_ots.php */
/* Location: ./application/models/M_ots.php */