<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_ots_syariah extends CI_Model {

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
		$this->db->SELECT('m_cabang_bank.*');
		$this->db->FROM('m_cabang_bank');
		$this->db->JOIN('m_capem_bank','m_capem_bank.id_cabang_bank = m_cabang_bank.id_cabang_bank','inner');
		$this->db->JOIN('debitur','debitur.id_capem_bank = m_capem_bank.id_capem_bank','inner');
		$this->db->JOIN('m_cabang_asuransi','m_cabang_asuransi.id_cabang_asuransi = debitur.id_cabang_as','inner');
		$this->db->JOIN('m_korwil_asuransi','m_korwil_asuransi.id_korwil_asuransi = m_cabang_asuransi.id_korwil_asuransi','inner');
		$this->db->JOIN('m_asuransi','m_asuransi.id_asuransi = m_korwil_asuransi.id_asuransi','inner');
		$this->db->WHERE('m_asuransi.id_asuransi',2);
		$this->db->GROUP_BY('m_cabang_bank.id_cabang_bank');

		return $this->db->get();
	}

	public function get_verifikator()
	{
		return $this->db->query('SELECT karyawan.* FROM penempatan INNER JOIN karyawan on (karyawan.id_karyawan = penempatan.id_karyawan) INNER JOIN debitur ON (debitur.id_capem_bank = penempatan.id_capem_bank) INNER JOIN m_cabang_asuransi ON(m_cabang_asuransi.id_cabang_asuransi = debitur.id_cabang_as) INNER JOIN m_korwil_asuransi on (m_korwil_asuransi.id_korwil_asuransi = m_cabang_asuransi.id_korwil_asuransi) INNER JOIN m_asuransi on (m_asuransi.id_asuransi = m_korwil_asuransi.id_asuransi) WHERE m_asuransi.id_asuransi = 2 GROUP BY karyawan.id_karyawan');
	}

	public function get_data_r_ots()
	{
		$this->db->select('d.nama_debitur, d.no_klaim, cab.cabang_bank, b.bank, o.alamat as alamat_deb, o.keterangan, msd.status_deb');
		$this->db->from('ots as o');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as msd', 'msd.id_status_deb = osd.id_status_deb', 'left');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->where('asr.id_asuransi', 2);
		$this->db->where('o.status', 1);

		return $this->db->get();
	}

	// fungsi untuk lihat pdf dan unduh pdf
	public function get_cari_data_unduh_r_ots($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank)
	{
		$this->db->select('d.id_debitur, d.nama_debitur, b.bank, sp.status_proses as potensi,  mh.tindakan_hukum as proses, cab.cabang_bank, o.keterangan,o.narasumber,o.add_time,o.alamat as alamat_deb,ca.cabang_asuransi, o.telp, a.asuransi');
		$this->db->from('ots as o');
		$this->db->join('tr_ots_p as op', 'op.id_ots = o.id_ots', 'left');
		$this->db->join('status_proses as sp', 'sp.id_status_proses = op.status_proses', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_ots_p = op.id_tr_ots_p', 'left');
		$this->db->join('m_tindakan_hukum as mh', 'mh.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->where('a.id_asuransi', 2);
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
				$this->db->where('a.id_asuransi', 2);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		$hasil = $this->db->get()->result_array(); 	
		$value = array();
		
		foreach ($hasil as $h) {
			$id_debitur 		= $h['id_debitur'];
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

			$this->db->select('d.id_debitur, jenis_asset');
			$this->db->from('dokumen_asset as d');
			$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = d.id_jenis_asset', 'left');
			$this->db->where('d.id_debitur', $id_debitur);
			$this->db->where('d.id_jenis_dok', '2');
			$this->db->where('d.status', '1');

			$hasil_2 = $this->db->get()->row_array();

			$jenis = $hasil_2['jenis_asset'];

			$value[] = [
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
				'bank'				=> $bank
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
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as msd', 'msd.id_status_deb = osd.id_status_deb', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->where('a.id_asuransi', 2);
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
				$this->db->where('a.id_asuransi', 2);
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
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'inner');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
		$this->db->where('o.status', 1);
		$this->db->where('a.id_asuransi', 2);

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
				$this->db->where('a.id_asuransi', 2);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($ver)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

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
			$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'inner');
			$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'inner');
			$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'inner');
			$this->db->where('status', 1);
			$this->db->where('a.id_asuransi',2);
		} else {
			$this->db->distinct();
			$this->db->select('id_debitur');
			$this->db->from($tabel);
			$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = debitur.id_cabang_as', 'inner');
			$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'inner');
			$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'inner');
			$this->db->where('a.id_asuransi',2);
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
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->where('o.status', 1);
		$this->db->where('a.id_asuransi', 2);

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
				$this->db->where('a.id_asuransi', 2);
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
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'inner');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->where('a.id_asuransi', 2);

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
				$this->db->where('a.id_asuransi', 2);
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