<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_proses extends CI_Model {

	public function get_tindakan()
	{
		return $this->db->get('m_tindakan_hukum');
	}

	public function get_cari_data_wilayah_ver($ver,$capem)
	{
		// TOTAL TINDAKAN HUKUM SOMASI
		$this->db->select('k.nama_lengkap, cap.capem_bank, count(mh.tindakan_hukum) as tot_tindakan_somasi');
		$this->db->from('penempatan as p');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
		$this->db->join('tr_ots_p as op', 'op.id_ots = o.id_ots', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_ots_p = op.id_tr_ots_p', 'left');
		$this->db->join('m_tindakan_hukum as mh', 'mh.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		$this->db->where('o.status', 1);

		$this->db->where('k.nama_lengkap', $ver)->where('cap.capem_bank', $capem);
		$this->db->like('mh.tindakan_hukum', 'Somasi', 'BOTH');

		$this->db->group_by('k.nama_lengkap')->group_by('cap.capem_bank');

		$hasil = $this->db->get()->result_array();

		$tot_tindakan_somasi = 0;
		foreach ($hasil as $h) {
			$tot_tindakan_somasi = $h['tot_tindakan_somasi'];
		}

		// TOTAL TINDAKAN HUKUM
		$this->db->select('k.nama_lengkap, cap.capem_bank, count(mh.tindakan_hukum) as tot_tindakan');
		$this->db->from('penempatan as p');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
		$this->db->join('tr_ots_p as op', 'op.id_ots = o.id_ots', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_ots_p = op.id_tr_ots_p', 'left');
		$this->db->join('m_tindakan_hukum as mh', 'mh.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		$this->db->where('o.status', 1);

		$this->db->where('k.nama_lengkap', $ver)->where('cap.capem_bank', $capem);

		$this->db->group_by('k.nama_lengkap')->group_by('cap.capem_bank');

		$hasil = $this->db->get()->result_array();

		foreach ($hasil as $h) {
			$tot_tindakan = $h['tot_tindakan'];
		}

		// NOMINAL RECOVERIES
		$this->db->select('k.nama_lengkap, cap.capem_bank, sum(r.nominal) as tot_nominal');
		$this->db->from('penempatan as p');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->where('k.nama_lengkap', $ver)->where('cap.capem_bank', $capem);

		$this->db->group_by('k.nama_lengkap, cap.capem_bank');
		$this->db->order_by('k.nama_lengkap', 'asc');
		$hasil = $this->db->get()->result_array();

		foreach ($hasil as $h) {
			$tot_nominal = $h['tot_nominal'];
 		}

 		// TOTAL HARGA ASSET
 		$this->db->select('k.nama_lengkap, cap.capem_bank, sum(t.harga) as tot_harga');
		$this->db->from('penempatan as p');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('dokumen_asset as t', 't.id_debitur = d.id_debitur', 'left');
		$this->db->where('k.nama_lengkap', $ver)->where('cap.capem_bank', $capem);
		$this->db->where('t.id_jenis_dok', 2)->where('t.status', 1);

		$this->db->group_by('k.nama_lengkap, cap.capem_bank');
		$this->db->order_by('k.nama_lengkap', 'asc');
		$asset = $this->db->get()->result_array();

		foreach ($asset as $s) {
			$tot_harga = $s['tot_harga'];
		}

		$potensi_recov = $tot_nominal + $tot_harga;

		$value[] = [
			'potensi_recov'		  => $potensi_recov,
			'tot_tindakan_somasi' => $tot_tindakan_somasi,
			'tot_tindakan'		  => $tot_tindakan
		];

		return $value;

	}

	public function get_data_r_proses()
	{
		$this->db->select('d.id_debitur, d.nama_debitur, b.bank, cab.cabang_bank, fu.tgl_fu, mth.tindakan_hukum');
		$this->db->from('tr_tindakan_hukum as tth');
		$this->db->join('m_tindakan_hukum as mth', 'mth.id_tindakan_hukum = tth.id_tindakan_hukum', 'left');
		$this->db->join('tr_ots_p as op', 'op.id_tr_ots_p = tth.id_tr_ots_p', 'left');
		$this->db->join('tr_ots_fu as fu', 'fu.id_tr_ots_p = tth.id_tr_ots_p', 'left');
		$this->db->join('ots as o', 'o.id_ots = op.id_ots', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->where('o.status', 1);

		$hasil = $this->db->get()->result_array();

		foreach ($hasil as $h) {
			$id_debitur 	= $h['id_debitur'];
			$nama_debitur 	= $h['nama_debitur'];
			$cabang_bank 	= $h['cabang_bank'];
			$tgl_fu 		= $h['tgl_fu'];
			$tindakan_hukum = $h['tindakan_hukum'];
			$bank 			= $h['bank'];

			$this->db->select('a.status_asset');
			$this->db->from('dokumen_asset as d');
			$this->db->join('status_asset as a', 'a.id_status_asset = d.id_status_asset', 'left');
			$this->db->where('d.id_jenis_dok', 2);
			$this->db->where('d.status', 1);
			$this->db->where('d.id_debitur', $id_debitur);

			$hasil_2 = $this->db->get()->row_array();

			$status_asset = $hasil_2['status_asset'];

			$data_r_proses[] = [
				'nama_debitur'	=> $nama_debitur,
				'cabang_bank'	=> $cabang_bank,
				'tgl_fu' 		=> $tgl_fu,
				'tindakan_hukum'=> $tindakan_hukum,
				'status_asset'	=> $status_asset,
				'bank' 			=> $bank
			];

		}

		return $data_r_proses;


	}

	public function get_cari_data_r_proses($tgl_awal,$tgl_akhir,$tindakan,$bank)
	{
		$this->db->select('d.*, d.id_debitur, d.nama_debitur, cab.cabang_bank, b.bank, d.penyebab_klaim, fu.tgl_fu, mth.tindakan_hukum, d.no_klaim,mca.cabang_asuransi');
		$this->db->from('tr_tindakan_hukum as tth');
		$this->db->join('m_tindakan_hukum as mth', 'mth.id_tindakan_hukum = tth.id_tindakan_hukum', 'left');
		$this->db->join('tr_ots_p as op', 'op.id_tr_ots_p = tth.id_tr_ots_p', 'left');
		$this->db->join('tr_ots_fu as fu', 'fu.id_tr_ots_p = tth.id_tr_ots_p', 'left');
		$this->db->join('ots as o', 'o.id_ots = op.id_ots', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_cabang_asuransi as mca', 'mca.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->where('o.status', 1);

		// tanggal awal terisi
		if (!empty($tgl_awal) && empty($tgl_akhir) && empty($tindakan) && empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_awal%' ");
		}
		// tanggal akhir terisi
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($tindakan) && empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_akhir%' ");
		}
		// tindakan terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($tindakan) && empty($bank)) {
			$this->db->where('mth.tindakan_hukum', $tindakan);
		}
		// bank terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($tindakan) && !empty($bank)) {
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal dengan tanggal akhir
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($tindakan) && empty($bank)) {
			$this->db->where("CAST(tth.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		// tanggal awal dengan tindakan
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($tindakan) && empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('mth.tindakan_hukum', $tindakan);
		}
		// tanggal awal dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($tindakan) && !empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir dengan tindakan
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($tindakan) && empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('mth.tindakan_hukum', $tindakan);
		}
		// tanggal akhir dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($tindakan) && !empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('b.bank', $bank);
		}
		// tindakan dengan bank
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($tindakan) && !empty($bank)) {
			$this->db->where('mth.tindakan_hukum', $tindakan);
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal, tindakan dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($tindakan) && !empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('mth.tindakan_hukum', $tindakan);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir, tindakan dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($tindakan) && !empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('mth.tindakan_hukum', $tindakan);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, tindakan
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($tindakan) && empty($bank)) {
			$this->db->where("CAST(tth.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('mth.tindakan_hukum', $tindakan);
		}
		// tanggal akhir sampai tanggal akhir, dengan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($tindakan) && !empty($bank)) {
			$this->db->where("CAST(tth.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, dengan tindakan dan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($tindakan) && !empty($bank)) {
			$this->db->where("CAST(tth.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('mth.tindakan_hukum', $tindakan);
			$this->db->where('b.bank', $bank);
		}

		$hasil = $this->db->get()->result_array();
		$data_r_proses = array();

		foreach ($hasil as $h) {
			$id_debitur 	= $h['id_debitur'];
			$deal_reff 		= $h['no_reff'];
			$nama_debitur 	= $h['nama_debitur'];
			$cabang_bank 	= $h['cabang_bank'];
			$tgl_fu 		= $h['tgl_fu'];
			$tindakan_hukum = $h['tindakan_hukum'];
			$penyebab_klaim = $h['penyebab_klaim'];
			$no_klaim 		= $h['no_klaim'];
			$cabang_asuransi= $h['cabang_asuransi'];
			$bank 			= $h['bank'];

			$this->db->select('a.status_asset');
			$this->db->from('dokumen_asset as d');
			$this->db->join('status_asset as a', 'a.id_status_asset = d.id_status_asset', 'left');
			$this->db->where('d.id_jenis_dok', 2);
			$this->db->where('d.status', 1);
			$this->db->where('d.id_debitur', $id_debitur);

			$hasil_2 = $this->db->get()->row_array();

			$status_asset = $hasil_2['status_asset'];

			$data_r_proses[] = [
				'deal_reff'			=> $deal_reff,
				'nama_debitur'		=> $nama_debitur,
				'cabang_bank'		=> $cabang_bank,
				'tgl_fu' 			=> $tgl_fu,
				'tindakan_hukum'	=> $tindakan_hukum,
				'status_asset'		=> $status_asset,
				'penyebab_klaim'	=> $penyebab_klaim,
				'no_klaim' 			=> $no_klaim,
				'cabang_asuransi'	=> $cabang_asuransi,
				'bank'				=> $bank
			];

		}

		return $data_r_proses;
	}

	public function get_jml_r_proses($tgl_awal,$tgl_akhir,$tindakan,$bank)
	{
		$this->db->select('mth.tindakan_hukum, count(mth.tindakan_hukum) as jumlah_tindakan');
		$this->db->from('tr_tindakan_hukum as tth');
		$this->db->join('m_tindakan_hukum as mth', 'mth.id_tindakan_hukum = tth.id_tindakan_hukum', 'left');
		$this->db->join('tr_ots_p as op', 'op.id_tr_ots_p = tth.id_tr_ots_p', 'left');
		$this->db->join('tr_ots_fu as fu', 'fu.id_tr_ots_p = tth.id_tr_ots_p', 'left');
		$this->db->join('ots as o', 'o.id_ots = op.id_ots', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_cabang_asuransi as mca', 'mca.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->where('o.status', 1);

		// tanggal awal terisi
		if (!empty($tgl_awal) && empty($tgl_akhir) && empty($tindakan) && empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_awal%' ");
		}
		// tanggal akhir terisi
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($tindakan) && empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_akhir%' ");
		}
		// tindakan terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($tindakan) && empty($bank)) {
			$this->db->where('mth.tindakan_hukum', $tindakan);
		}
		// bank terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($tindakan) && !empty($bank)) {
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal dengan tanggal akhir
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($tindakan) && empty($bank)) {
			$this->db->where("CAST(tth.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		// tanggal awal dengan tindakan
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($tindakan) && empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('mth.tindakan_hukum', $tindakan);
		}
		// tanggal awal dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($tindakan) && !empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir dengan tindakan
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($tindakan) && empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('mth.tindakan_hukum', $tindakan);
		}
		// tanggal akhir dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($tindakan) && !empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('b.bank', $bank);
		}
		// tindakan dengan bank
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($tindakan) && !empty($bank)) {
			$this->db->where('mth.tindakan_hukum', $tindakan);
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal, tindakan dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($tindakan) && !empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('mth.tindakan_hukum', $tindakan);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir, tindakan dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($tindakan) && !empty($bank)) {
			$this->db->where("CAST(tth.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('mth.tindakan_hukum', $tindakan);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, tindakan
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($tindakan) && empty($bank)) {
			$this->db->where("CAST(tth.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('mth.tindakan_hukum', $tindakan);
		}
		// tanggal akhir sampai tanggal akhir, dengan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($tindakan) && !empty($bank)) {
			$this->db->where("CAST(tth.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, dengan tindakan dan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($tindakan) && !empty($bank)) {
			$this->db->where("CAST(tth.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('mth.tindakan_hukum', $tindakan);
			$this->db->where('b.bank', $bank);
		}

		$this->db->group_by('mth.id_tindakan_hukum');

		return $this->db->get();
	}

}

/* End of file M_proses.php */
/* Location: ./application/models/M_proses.php */