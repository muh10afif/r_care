<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_recov_syariah extends CI_Model {

	public function get_cari_data_wilayah_ver($ver,$capem)
	{
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
			$nama 		 = $h['nama_lengkap'];
			$capem 		 = $h['capem_bank'];
			$tot_nominal = $h['tot_nominal'];
 		}

 		$this->db->select('k.nama_lengkap, cap.capem_bank, sum(d.subrogasi) as tot_subrogasi');
		$this->db->from('penempatan as p');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->where('k.nama_lengkap', $ver)->where('cap.capem_bank', $capem);

		$this->db->group_by('k.nama_lengkap, cap.capem_bank');
		$this->db->order_by('k.nama_lengkap', 'asc');
		$subrogasi = $this->db->get()->result_array();

		foreach ($subrogasi as $s) {
			$tot_subrogasi = $s['tot_subrogasi'];
		}

		$this->db->select('k.nama_lengkap, cap.capem_bank, sum(t.harga) as tot_harga');
		$this->db->from('penempatan as p');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('dokumen_asset as t', 't.id_debitur = d.id_debitur', 'left');
		$this->db->where('k.nama_lengkap', $ver)->where('cap.capem_bank', $capem);
		$this->db->where('t.id_jenis_dok', '2')->where('t.status', '1');

		$this->db->group_by('k.nama_lengkap, cap.capem_bank');
		$this->db->order_by('k.nama_lengkap', 'asc');
		$asset = $this->db->get()->result_array();

		$tot_harga = 0;
		
		foreach ($asset as $s) {
			$tot_harga = $s['tot_harga'];
		}

		$crp = ($tot_nominal/$tot_subrogasi) * 100;

		$potensi_recov = $tot_nominal + $tot_harga;

		$value[] = [
			'tot_nominal' 	=> $tot_nominal,
			'crp'		  	=> $crp,
			'potensi_recov' => $potensi_recov
		];

		return $value;
	}

	public function get_data_wilayah_ver()
	{
		$this->db->select('k.nama_lengkap, cap.capem_bank, sum(r.nominal) as tot_nominal');
		$this->db->from('penempatan as p');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('m_cabang_asuransi as ca','ca.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as ka','ka.id_korwil_asuransi = ca.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as a','a.id_asuransi = ka.id_asuransi','inner');
		$this->db->where('a.id_asuransi',2);
		$this->db->group_by('k.nama_lengkap, cap.capem_bank');

		$this->db->order_by('k.nama_lengkap', 'asc');
		return $this->db->get();
	}

	// fungsi untuk print data
	public function get_cari_print_data_r_recov($tgl_awal,$tgl_akhir,$potensial,$bank)
	{
		$this->db->select('d.id_debitur,d.nama_debitur,d.bunga, b.bank, ca.cabang_asuransi, d.no_klaim, cab.cabang_bank, d.subrogasi, sum(r.nominal) as recoveries, ms.status_deb');
		$this->db->from('debitur as d');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
		$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = ca.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asu','asu.id_asuransi = kor.id_asuransi','inner');
		$this->db->where('asu.id_asuransi',2);

		// tanggal awal terisi
		if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
			$this->db->where("CAST(r.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('asu.id_asuransi',2);
		}
		// tanggal akhir terisi
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($potensial) && empty($bank)) {
			$this->db->where("CAST(r.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('asu.id_asuransi',2);
		}
		// potensial terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($potensial) && empty($bank)) {
			$this->db->where('ms.status_deb', $potensial);
			$this->db->where('asu.id_asuransi',2);
		}
		// bank terisi
		elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && !empty($bank)) {
			$this->db->where('b.bank', $bank);
			$this->db->where('asu.id_asuransi',2);
		}
		// tanggal awal dengan tanggal akhir
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($potensial) && empty($bank)) {
			$this->db->where("CAST(r.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('asu.id_asuransi',2);
		}
		// tanggal awal dengan potensial
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($potensial) && empty($bank)) {
			$this->db->where("CAST(r.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('ms.status_deb', $potensial);
			$this->db->where('asu.id_asuransi',2);
		}
		// tanggal awal dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && !empty($bank)) {
			$this->db->where("CAST(r.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('b.bank', $bank);
			$this->db->where('asu.id_asuransi',2);
		}
		// tanggal akhir dengan potensial
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($potensial) && empty($bank)) {
			$this->db->where("CAST(r.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('ms.status_deb', $potensial);
			$this->db->where('asu.id_asuransi',2);
		}
		// tanggal akhir dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($potensial) && !empty($bank)) {
			$this->db->where("CAST(r.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('b.bank', $bank);
			$this->db->where('asu.id_asuransi',2);
		}
		// potensial dengan bank
		elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($potensial) && !empty($bank)) {
			$this->db->where('ms.status_deb', $potensial);
			$this->db->where('b.bank', $bank);
			$this->db->where('asu.id_asuransi',2);
		}
		// tanggal awal, potensial dengan bank
		elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($potensial) && !empty($bank)) {
			$this->db->where("CAST(r.add_time AS VARCHAR) like '%$tgl_awal%' ");
			$this->db->where('ms.status_deb', $potensial);
			$this->db->where('b.bank', $bank);
			$this->db->where('asu.id_asuransi',2);
		}
		// tanggal akhir, potensial dengan bank
		elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($potensial) && !empty($bank)) {
			$this->db->where("CAST(r.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			$this->db->where('ms.status_deb', $potensial);
			$this->db->where('b.bank', $bank);
			$this->db->where('asu.id_asuransi',2);
		}
		// tanggal akhir sampai tanggal akhir, potensial
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($potensial) && empty($bank)) {
			$this->db->where("CAST(r.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('ms.status_deb', $potensial);
			$this->db->where('asu.id_asuransi',2);
		}
		// tanggal akhir sampai tanggal akhir, dengan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($potensial) && !empty($bank)) {
			$this->db->where("CAST(r.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('b.bank', $bank);
			$this->db->where('asu.id_asuransi',2);
		}
		// tanggal akhir sampai tanggal akhir, dengan potensial dan bank
		elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($potensial) && !empty($bank)) {
			$this->db->where("CAST(r.add_time as VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
			$this->db->where('ms.status_deb', $potensial);
			$this->db->where('b.bank', $bank);
			$this->db->where('asu.id_asuransi',2);
		}

		$this->db->group_by('d.id_debitur');
		$this->db->group_by('cab.id_cabang_bank');
		$this->db->group_by('b.bank');
		$this->db->group_by('ca.id_cabang_asuransi');
		$this->db->group_by('ms.id_status_deb');
		$this->db->order_by('d.nama_debitur', 'asc');

		$hasil = $this->db->get()->result_array();
		$value = array();

		foreach ($hasil as $h) {
			$id_debitur 	 = $h['id_debitur'];
			$bunga 			 = $h['bunga'];
			$cabang_asuransi = $h['cabang_asuransi'];
			$cabang_bank 	 = $h['cabang_bank'];
			$nama_debitur 	 = $h['nama_debitur'];
			$no_klaim 		 = $h['no_klaim'];
			$subrogasi 		 = $h['subrogasi'];
			$recoveries 	 = $h['recoveries'];
			$bank 			 = $h['bank'];

			// mencari total shs perdebitur
			$shs 	= $subrogasi - $recoveries;

			$this->db->select('*');
			$this->db->from('denda as d');
			$this->db->where('d.id_debitur', $id_debitur);
			$this->db->order_by('d.add_time', 'desc');
			$this->db->limit(1);
			$denda = $this->db->get()->row('nominal_denda');

			$tot_tagihan = $subrogasi+$bunga+$denda;

			$saldo_tagihan = $tot_tagihan - $recoveries;

			$value[] = [
				    'cabang_asuransi'	=> $cabang_asuransi,
				    'cabang_bank'		=> $cabang_bank,
				    'nama_debitur'		=> $nama_debitur,
				    'no_klaim' 			=> $no_klaim,
				    'subrogasi'			=> $subrogasi,
				    'recoveries'		=> $recoveries,
				    'shs'				=> $shs,
				    'nilai_tagihan'		=> $tot_tagihan,
				    'saldo_tagihan'		=> $saldo_tagihan,
				    'bank' 				=> $bank
				];
		}
		return $value;
	}

}

/* End of file M_recov.php */
/* Location: ./application/models/M_recov.php */