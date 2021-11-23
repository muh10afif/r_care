<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_print_report extends CI_Model {

	public function cari_data($tabel, $where)
	{
		return $this->db->get_where($tabel, $where);
		
	}

	public function simpan_upload($judul, $dok)
	{
		$data = [
		    'judul' => $judul,
		    'dokumen' => $dok 
		];

		$hasil = $this->db->insert('tr_laporan', $data);

		return $hasil;
	}

	public function hapus_data($tabel, $where)
	{
		$this->db->where($where);
		$this->db->delete($tabel);
	}

	public function get_data_upload()
	{
		return $this->db->get('tr_laporan')->result_array();
	}

	public function get_asuransi()
	{
		return $this->db->get('m_asuransi');
	}

	public function get_print_agunan($debitur,$no_klaim)
	{
		$this->db->select('cas.cabang_asuransi, cab.cabang_bank, ta.total_harga as harga, ta.keterangan, s.status_asset, ja.jenis_asset, j.jenis_dokumen as jenis_dok, d.nama_debitur, d.no_klaim');	
		$this->db->from('dokumen_asset as ta');
		$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = ta.id_jenis_asset', 'left');
		$this->db->join('status_asset as s', 's.id_status_asset = ta.id_status_asset', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_cabang_asuransi as cas', 'cas.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = cas.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');
		$this->db->join('m_jenis_dokumen as j', 'j.id_jenis_dok = ta.id_jenis_dok', 'left');
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);	

		$this->db->where('d.id_debitur', $debitur)->where('d.no_klaim', $no_klaim);

		return $this->db->get();		
	}

	public function get_cari_agunan($tgl_awal,$tgl_akhir,$asuransi,$bank)
	{
		$this->db->select('d.id_debitur, d.nama_debitur, d.no_klaim');	
		$this->db->from('dokumen_asset as ta');
		$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = ta.id_jenis_asset', 'left');
		$this->db->join('status_asset as s', 's.id_status_asset = ta.id_status_asset', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_cabang_asuransi as cas', 'cas.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = cas.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);	

		// tanggal awal terisi
		if (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_awal%' ");
		}
		// tanggal akhir terisi
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_akhir%' ");
		}
		// asuransi terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where('a.asuransi', $asuransi);
		}
		// bank terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal dengan tanggal akhir
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(ta.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		// tanggal awal dengan asuransi
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('a.asuransi', $asuransi);
		}
		// tanggal awal dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir dengan asuransi
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('a.asuransi', $asuransi);
		}
		// tanggal akhir dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('b.bank', $bank);
		}
		// asuransi dengan bank
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal, asuransi dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir, asuransi dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, asuransi
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(ta.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('a.asuransi', $asuransi);
		}
		// tanggal akhir sampai tanggal akhir, dengan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, dengan asuransi dan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}

		$this->db->group_by('d.id_debitur');
		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.no_klaim');

		return $this->db->get();
	}

	public function get_cari_agunan_2($tgl_awal,$tgl_akhir,$asuransi,$bank, $id_debitur)
	{
		$this->db->select('d.id_debitur, d.nama_debitur, d.no_klaim');	
		$this->db->from('dokumen_asset as ta');
		$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = ta.id_jenis_asset', 'left');
		$this->db->join('status_asset as s', 's.id_status_asset = ta.id_status_asset', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_cabang_asuransi as cas', 'cas.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = cas.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);	

		if ($id_debitur) {
			$this->db->where('d.id_debitur', $id_debitur);
		}

		// tanggal awal terisi
		if (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_awal%' ");
		}
		// tanggal akhir terisi
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_akhir%' ");
		}
		// asuransi terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where('a.asuransi', $asuransi);
		}
		// bank terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal dengan tanggal akhir
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(ta.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		// tanggal awal dengan asuransi
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('a.asuransi', $asuransi);
		}
		// tanggal awal dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir dengan asuransi
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('a.asuransi', $asuransi);
		}
		// tanggal akhir dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('b.bank', $bank);
		}
		// asuransi dengan bank
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal, asuransi dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir, asuransi dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, asuransi
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(ta.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('a.asuransi', $asuransi);
		}
		// tanggal akhir sampai tanggal akhir, dengan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, dengan asuransi dan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(ta.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}

		$this->db->group_by('d.id_debitur');
		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.no_klaim');


		return $this->db->get();
	}

	public function get_print_dokumen_upload($debitur,$no_klaim)
	{
		$this->db->select('d.nama_debitur, d.no_klaim, ja.jenis_asset, jd.jenis_dokumen, da.total_harga as harga, cas.cabang_asuransi, cab.cabang_bank, da.keterangan');
		$this->db->from('dokumen_asset as da');
		$this->db->join('debitur as d', 'd.id_debitur = da.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_cabang_asuransi as cas', 'cas.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = cas.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');
		$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = da.id_jenis_asset', 'left');
		$this->db->join('m_jenis_dokumen as jd', 'jd.id_jenis_dok = da.id_jenis_dok', 'left');
		$this->db->where('da.status', 1);
	
		$this->db->where('d.nama_debitur', $debitur)->where('d.no_klaim', $no_klaim);

		return $this->db->get();
	}

	public function get_cari_dokumen_upload($tgl_awal,$tgl_akhir,$asuransi,$bank)
	{
		$this->db->select('d.id_debitur, d.nama_debitur, d.no_klaim');
		$this->db->from('dokumen_asset as da');
		$this->db->join('debitur as d', 'd.id_debitur = da.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_cabang_asuransi as cas', 'cas.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = cas.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');

		// tanggal awal terisi
		if (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_awal%' ");
		}
		// tanggal akhir terisi
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_akhir%' ");
		}
		// asuransi terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where('a.asuransi', $asuransi);
		}
		// bank terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal dengan tanggal akhir
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(da.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		// tanggal awal dengan asuransi
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('a.asuransi', $asuransi);
		}
		// tanggal awal dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir dengan asuransi
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('a.asuransi', $asuransi);
		}
		// tanggal akhir dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('b.bank', $bank);
		}
		// asuransi dengan bank
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal, asuransi dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir, asuransi dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, asuransi
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(da.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('a.asuransi', $asuransi);
		}
		// tanggal akhir sampai tanggal akhir, dengan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(da.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, dengan asuransi dan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(da.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}

		$this->db->group_by('d.id_debitur');
		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.no_klaim');
		
		return $this->db->get();
	}

	public function get_cari_dokumen_upload_2($tgl_awal,$tgl_akhir,$asuransi,$bank, $id_debitur)
	{
		$this->db->select('d.id_debitur, d.nama_debitur, d.no_klaim');
		$this->db->from('dokumen_asset as da');
		$this->db->join('debitur as d', 'd.id_debitur = da.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_cabang_asuransi as cas', 'cas.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = cas.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');

		if ($id_debitur) {
			$this->db->where('d.id_debitur', $id_debitur);
		}

		// tanggal awal terisi
		if (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_awal%' ");
		}
		// tanggal akhir terisi
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_akhir%' ");
		}
		// asuransi terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where('a.asuransi', $asuransi);
		}
		// bank terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal dengan tanggal akhir
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(da.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		// tanggal awal dengan asuransi
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('a.asuransi', $asuransi);
		}
		// tanggal awal dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir dengan asuransi
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('a.asuransi', $asuransi);
		}
		// tanggal akhir dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('b.bank', $bank);
		}
		// asuransi dengan bank
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}
		// tanggal awal, asuransi dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir, asuransi dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(da.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, asuransi
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && empty($bank)) {
			$this->db->where("CAST(da.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('a.asuransi', $asuransi);
		}
		// tanggal akhir sampai tanggal akhir, dengan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(da.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('b.bank', $bank);
		}
		// tanggal akhir sampai tanggal akhir, dengan asuransi dan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($asuransi) && !empty($bank)) {
			$this->db->where("CAST(da.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('a.asuransi', $asuransi);
			$this->db->where('b.bank', $bank);
		}

		$this->db->group_by('d.id_debitur');
		$this->db->group_by('d.nama_debitur');
		$this->db->group_by('d.no_klaim');

		return $this->db->get();
	}

}

/* End of file M_print_report.php */
/* Location: ./application/models/M_print_report.php */