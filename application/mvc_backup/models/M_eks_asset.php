<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_eks_asset extends CI_Model {

	public function get_jenis_asset()
	{
		return $this->db->get('m_jenis_asset');
	}

	public function get_cari_data_wilayah_ver($ver,$capem)
	{
		$this->db->select('k.nama_lengkap, cap.capem_bank, count(ta.id_dokumen_asset) as tot_jumlah_asset_all');
		$this->db->from('penempatan as p');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('dokumen_asset as ta', 'ta.id_debitur = d.id_debitur', 'left');
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);		

		$this->db->where('k.nama_lengkap', $ver)->where('cap.capem_bank', $capem);

		$this->db->group_by('k.nama_lengkap')->group_by('cap.capem_bank');

		$hasil = $this->db->get()->result_array();

		$tot_jumlah_asset = 0;
		foreach ($hasil as $h) {
			$tot_jumlah_asset = $h['tot_jumlah_asset_all'];
		}

		$this->db->select('k.nama_lengkap, cap.capem_bank, count(a.id_dokumen_asset) as tot_jumlah_asset, sum(a.harga) as tot_hasil_penjualan');
		$this->db->from('penempatan as p');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('dokumen_asset as a', 'a.id_debitur = d.id_debitur', 'left');
		$this->db->join('status_asset as sa', 'sa.id_status_asset = a.id_status_asset', 'left');
		$this->db->where('a.id_jenis_dok', 2);
		$this->db->where('a.status', 1);	

		$this->db->where('k.nama_lengkap', $ver)->where('cap.capem_bank', $capem)->where('sa.status_asset', 'Sudah Terjual');

		$this->db->group_by('k.nama_lengkap')->group_by('cap.capem_bank');

		$hasil = $this->db->get()->result_array();

		$tot_jumlah_asset_sdh = 0;
		$tot_hasil_penjualan  = 0;
		foreach ($hasil as $h) {
			$tot_jumlah_asset_sdh = $h['tot_jumlah_asset'];
			$tot_hasil_penjualan  = $h['tot_hasil_penjualan'];
		}


		$this->db->select('k.nama_lengkap, cap.capem_bank, count(a.id_dokumen_asset) as tot_jumlah_asset, sum(a.harga) as tot_hasil_penjualan');
		$this->db->from('penempatan as p');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('dokumen_asset as a', 'a.id_debitur = d.id_debitur', 'left');
		$this->db->join('status_asset as sa', 'sa.id_status_asset = a.id_status_asset', 'left');
		$this->db->where('a.id_jenis_dok', 2);
		$this->db->where('a.status', 1);	

		$this->db->where('k.nama_lengkap', $ver)->where('cap.capem_bank', $capem)->where('sa.status_asset', 'Belum Terjual');

		$this->db->group_by('k.nama_lengkap')->group_by('cap.capem_bank');

		$hasil = $this->db->get()->result_array();

		$tot_jumlah_asset_blm 	 = 0;
		$tot_hasil_penjualan_pot = 0;
		foreach ($hasil as $h) {
			$tot_jumlah_asset_blm     = $h['tot_jumlah_asset'];
			$tot_hasil_penjualan_pot  = $h['tot_hasil_penjualan'];
		}

		$value[] = [
			'tot_jumlah_asset'			=> $tot_jumlah_asset,
			'tot_hasil_penjualan'		=> $tot_hasil_penjualan,
			'tot_jumlah_asset_sdh'		=> $tot_jumlah_asset_sdh,
			'tot_hasil_penjualan_pot'	=> $tot_hasil_penjualan_pot
		];

		return $value;

	}

	public function get_data_r_eks_asset()
	{
		$this->db->select('d.nama_debitur, cab.cabang_bank, b.bank, ja.jenis_asset, sa.status_asset, k.nama_lengkap as eksekutor');
		$this->db->from('dokumen_asset as ta');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = ta.id_jenis_asset', 'left');
		$this->db->join('status_asset as sa', 'sa.id_status_asset = ta.id_status_asset', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = ta.id_karyawan', 'left');
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);

		return $this->db->get();
	}

	public function get_cari_data_r_eks_asset($tgl_awal,$tgl_akhir,$jenis_asset,$bank)
	{
		$this->db->select('d.*, d.nama_debitur, cab.cabang_bank, ja.jenis_asset, b.bank, s.sifat_asset, ta.harga, sa.status_asset, d.no_klaim, a.cabang_asuransi, k.nama_lengkap as eksekutor');
		$this->db->from('dokumen_asset as ta');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'left');
		$this->db->join('m_cabang_asuransi as a', 'a.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = ta.id_jenis_asset', 'left');
		$this->db->join('status_asset as sa', 'sa.id_status_asset = ta.status', 'left');
		$this->db->join('m_sifat_asset as s', 's.id_sifat_asset = ta.id_sifat_asset', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = ta.id_karyawan', 'left');
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);

		// tanggal awal terisi
		if (!empty($tgl_awal) && empty($tgl_akhir) && empty($jenis_asset) && empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_awal%' ");
		}
		// tanggal akhir terisi
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($jenis_asset) && empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_akhir%' ");
		}
		// jenis_asset terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($jenis_asset) && empty($bank)) {
			$this->db->where('ja.jenis_asset', $jenis_asset);
		}
		// bank terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($jenis_asset) && !empty($bank)) {
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal dengan tanggal akhir
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($jenis_asset) && empty($bank)) {
			$this->db->where("CAST(ta.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		// tanggal awal dengan jenis_asset
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($jenis_asset) && empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('ja.jenis_asset', $jenis_asset);
		}
		// tanggal awal dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($jenis_asset) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir dengan jenis_asset
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($jenis_asset) && empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('ja.jenis_asset', $jenis_asset);
		}
		// tanggal akhir dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($jenis_asset) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('b.bank', $bank);
		}
		// jenis_asset dengan bank
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($jenis_asset) && !empty($bank)) {
			$this->db->where('ja.jenis_asset', $jenis_asset);
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal, jenis_asset dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($jenis_asset) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('ja.jenis_asset', $jenis_asset);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir, jenis_asset dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($jenis_asset) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('ja.jenis_asset', $jenis_asset);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, jenis_asset
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($jenis_asset) && empty($bank)) {
			$this->db->where("CAST(ta.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('ja.jenis_asset', $jenis_asset);
		}
		// tanggal akhir sampai tanggal akhir, dengan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($jenis_asset) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, dengan jenis_asset dan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($jenis_asset) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('ja.jenis_asset', $jenis_asset);
			$this->db->where('b.bank', $bank);
		}

		return $this->db->get();
	}

}

/* End of file M_eks_asset.php */
/* Location: ./application/models/M_eks_asset.php */