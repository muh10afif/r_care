<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_noa_syariah extends CI_Model {

	public function get_total($tabel, $tgl_awal,$tgl_akhir,$potensial,$bank)
	{
		if ($tabel == 'ots') { 
			/*$this->db->distinct();
			$this->db->select("$tabel.id_debitur");
			$this->db->from($tabel);
			$this->db->join('debitur as d', "d.id_debitur = $tabel.id_debitur", 'left');
			$this->db->where('status', 1);*/

			$this->db->select('d.id_debitur');
			$this->db->from('debitur as d');
			$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
			$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
			$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
			$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
			$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
			$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
			$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			$this->db->where('asr.id_asuransi', 2);
			$this->db->where('o.status', 1);

			if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
				if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
				}
				if (empty($tgl_akhir) && !empty($tgl_awal) && empty($potensial) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
				}
				if (!empty($potensial)) {
					$this->db->where('ms.status_deb', $potensial);
				}
				if (!empty($bank)) {
					$this->db->where('b.bank', $bank);
				}
				if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
				}
			}

			$this->db->group_by('d.id_debitur');
		} else {
			$this->db->select('d.id_debitur');
			$this->db->from('debitur as d');
			$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
			$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
			$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
			$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
			$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
			$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
			$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			$this->db->where('asr.id_asuransi', 2);
			$this->db->where('o.status', 1);

			if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
				if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
				}
				if (empty($tgl_akhir) && !empty($tgl_awal) && empty($potensial) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
				}
				if (!empty($potensial)) {
					$this->db->where('ms.status_deb', $potensial);
				}
				if (!empty($bank)) {
					$this->db->where('b.bank', $bank);
				}
				if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
				}
			}

			$this->db->group_by('d.id_debitur');
		}


		
		return $this->db->count_all_results();
	}

	public function get_status_debitur()
	{
		return $this->db->get('m_status_debitur');
	}

	public function get_bank()
	{
		$this->db->SELECT('m_bank.*');
		$this->db->FROM('m_bank');
		$this->db->JOIN('m_cabang_bank','m_cabang_bank.id_bank = m_bank.id_bank');
		$this->db->JOIN('m_capem_bank','m_capem_bank.id_cabang_bank = m_cabang_bank.id_cabang_bank','inner');
		$this->db->JOIN('debitur','debitur.id_capem_bank = m_capem_bank.id_capem_bank','inner');
		$this->db->JOIN('m_cabang_asuransi','m_cabang_asuransi.id_cabang_asuransi = debitur.id_cabang_as','inner');
		$this->db->JOIN('m_korwil_asuransi','m_korwil_asuransi.id_korwil_asuransi = m_cabang_asuransi.id_korwil_asuransi','inner');
		$this->db->JOIN('m_asuransi','m_asuransi.id_asuransi = m_korwil_asuransi.id_asuransi','inner');
		$this->db->WHERE('m_asuransi.id_asuransi',2);
		$this->db->GROUP_BY('m_bank.bank');
		$this->db->GROUP_BY('m_bank.id_bank');
		$this->db->GROUP_BY('m_bank.add_time');
		$this->db->GROUP_BY('m_bank.singkatan');

		return $this->db->get();
	}

	public function get_data_r_noa()
	{
		// $this->db->select('d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries, ms.status_deb');
		// $this->db->from('debitur as d');
		// $this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		// $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		// $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		// $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		// $this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
		// $this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
		// $this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
		// $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		// $this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		// $this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		// $this->db->where('asr.id_asuransi', 2);
		// $this->db->where('o.status', 1);
		// $this->db->group_by('d.id_debitur');
		// $this->db->group_by('cab.id_cabang_bank');
		// $this->db->group_by('ms.id_status_deb');
		// $this->db->group_by('b.bank');
		// $this->db->order_by('d.nama_debitur', 'asc');

		$this->db->select('d.id_debitur, d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries');
		$this->db->from('debitur as d');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->where('asr.id_asuransi', 2);
		$this->db->group_by('d.id_debitur');
		$this->db->group_by('cab.id_cabang_bank');
		$this->db->group_by('b.bank');
		$this->db->order_by('d.nama_debitur', 'asc');

		$hasil = $this->db->get()->result_array();

		$value = array();

		foreach ($hasil as $h) {
			$nama_debitur 	= $h['nama_debitur'];
			$cabang_bank	= $h['cabang_bank'];
			$bank 			= $h['bank'];
			$subrogasi 		= $h['subrogasi'];
			$recoveries 	= $h['recoveries'];
			$id_debitur 	= $h['id_debitur'];
			
			$this->db->select('d.id_debitur, d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries, ms.status_deb');
			$this->db->from('debitur as d');
			$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
			$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
			$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
			$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
			$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
			$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
			$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			$this->db->where('asr.id_asuransi', 2);
			$this->db->where('o.status', 1);
			$this->db->where('d.id_debitur', $id_debitur);
			$this->db->group_by('d.id_debitur');
			$this->db->group_by('cab.id_cabang_bank');
			$this->db->group_by('ms.id_status_deb');
			$this->db->group_by('b.bank');
			$this->db->order_by('d.nama_debitur', 'asc');

			$hasil_2 = $this->db->get()->row_array();

			$value[] = 	['nama_debitur'	=> $nama_debitur,
						 'cabang_bank'	=> $cabang_bank,
						 'bank'			=> $bank,
						 'subrogasi'	=> $subrogasi,
						 'recoveries'	=> $recoveries,
						 'status_deb'	=> $hasil_2['status_deb']
						];
			
		}

		return $value;
	}

	public function get_cari_data_r_noa($tgl_awal,$tgl_akhir,$potensial,$bank)
	{
		if ($potensial) {
			
			$this->db->select('d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries, ms.status_deb');
			$this->db->from('debitur as d');
			$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
			$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
			$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
			$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');

			$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
			$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
			$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			
			$this->db->where('o.status', 1);
			$this->db->where('asr.id_asuransi', 2);

			if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
				if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
				}
				if (empty($tgl_akhir) && !empty($tgl_awal) && empty($potensial) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
				}
				if (!empty($potensial)) {
					$this->db->where('ms.status_deb', $potensial);
				}
				if (!empty($bank)) {
					$this->db->where('b.bank', $bank);
				}
				if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($potensial) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
				}
			}

			$this->db->group_by('d.id_debitur');
			$this->db->group_by('cab.id_cabang_bank');
			$this->db->group_by('ms.id_status_deb');
			$this->db->group_by('b.bank');
			$this->db->order_by('d.nama_debitur', 'asc');

			return $this->db->get()->result_array();
			
		} else {
			
			$this->db->select('d.id_debitur, d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries');
			$this->db->from('debitur as d');
			$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
			$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
			$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
			$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			$this->db->where('asr.id_asuransi', 2);

			if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($bank)) {
				if (!empty($tgl_awal) && empty($tgl_akhir) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
				}
				if (empty($tgl_akhir) && !empty($tgl_awal) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
				}
				if (!empty($bank)) {
					$this->db->where('b.bank', $bank);
				}
				if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
				}
			}

			$this->db->group_by('d.id_debitur');
			$this->db->group_by('cab.id_cabang_bank');
			$this->db->group_by('b.bank');
			$this->db->order_by('d.nama_debitur', 'asc');

			return $this->db->get()->result_array();
			
		}
		

		// $this->db->select('d.id_debitur, d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries');
		// $this->db->from('debitur as d');
		// $this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		// $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		// $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		// $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		// $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		// $this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		// $this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

		// if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
		// 	if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
		// 		$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
		// 	}
		// 	if (empty($tgl_akhir) && !empty($tgl_awal) && empty($potensial) && empty($bank)) {
		// 		$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
		// 	}
		// 	if (!empty($bank)) {
		// 		$this->db->where('b.bank', $bank);
		// 	}
		// 	if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($bank)) {
		// 		$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
		// 	}
		// }

		// $this->db->group_by('d.id_debitur');
		// $this->db->group_by('cab.id_cabang_bank');
		// $this->db->group_by('b.bank');
		// $this->db->order_by('d.nama_debitur', 'asc');

		// $hasil = $this->db->get()->result_array();

		// $value = array();

		// foreach ($hasil as $h) {
		// 	$nama_debitur 	= $h['nama_debitur'];
		// 	$cabang_bank	= $h['cabang_bank'];
		// 	$bank 			= $h['bank'];
		// 	$subrogasi 		= $h['subrogasi'];
		// 	$recoveries 	= $h['recoveries'];
		// 	$id_debitur 	= $h['id_debitur'];
			
		// 	$this->db->select('d.id_debitur, d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries, ms.status_deb');
		// 	$this->db->from('debitur as d');
		// 	$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		// 	$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		// 	$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		// 	$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		// 	$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
		// 	$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
		// 	$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
		// 	$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		// 	$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		// 	$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		// 	$this->db->where('o.status', 1);
		// 	$this->db->where('d.id_debitur', $id_debitur);

			
		// 	if (!empty($potensial)) {
		// 		$this->db->where('ms.status_deb', $potensial);
		// 	}

		// 	$this->db->group_by('d.id_debitur');
		// 	$this->db->group_by('cab.id_cabang_bank');
		// 	$this->db->group_by('ms.id_status_deb');
		// 	$this->db->group_by('b.bank');
		// 	$this->db->order_by('d.nama_debitur', 'asc');

		// 	$hasil_2 = $this->db->get()->row_array();

		// 	$value[] = 	['nama_debitur'	=> $nama_debitur,
		// 					'cabang_bank'	=> $cabang_bank,
		// 					'bank'			=> $bank,
		// 					'subrogasi'	=> $subrogasi,
		// 					'recoveries'	=> $recoveries,
		// 					'status_deb'	=> $hasil_2['status_deb']
		// 				];
			
		// }

		// return $value;
	}

	public function get_data_noa_jml($tgl_awal,$tgl_akhir,$potensial,$bank)
	{
		$this->db->select('count(mh.tindakan_hukum) as jml_tindakan_hukum, mh.tindakan_hukum, sum(r.nominal) as total_nominal_recoveries');
		$this->db->from('debitur as d');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
		$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
		$this->db->join('tr_ots_p as op', 'op.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_ots_p = op.id_tr_ots_p', 'left');
		$this->db->join('m_tindakan_hukum as mh', 'mh.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->where('asr.id_asuransi', 2);
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (empty($tgl_akhir) && !empty($tgl_awal) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($potensial)) {
				$this->db->where('ms.status_deb', $potensial);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		$this->db->group_by('mh.id_tindakan_hukum');

		return $this->db->get()->result_array();
	}

	/*
	
	SELECT d.nama_debitur, cab.cabang_bank, d.subrogasi as subro, de.nominal_denda, d.bunga, "sum"(r.nominal) as total_nominal_recoveries, ms.status_deb
	FROM debitur as d
	LEFT JOIN recoveries as r ON r.id_debitur = d.id_debitur
	LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = d.id_capem_bank
	LEFT JOIN m_cabang_bank as cab ON cab.id_cabang_bank = cap.id_cabang_bank
	LEFT JOIN ots as o ON o.id_debitur = d.id_debitur
	LEFT JOIN ots_status_debitur as sd ON sd.id_ots = o.id_ots
	LEFT JOIN m_status_debitur as ms ON ms.id_status_deb = sd.id_status_deb
	LEFT JOIN tr_ots_p as op ON op.id_ots = o.id_ots
	LEFT JOIN tr_tindakan_hukum as th ON th.id_tr_ots_p = op.id_tr_ots_p
	LEFT JOIN m_tindakan_hukum as mh ON mh.id_tindakan_hukum = th.id_tindakan_hukum
	LEFT JOIN denda as de ON de.id_debitur = d.id_debitur

	WHERE ms.status_deb = 'Potensial'

	GROUP BY d.id_debitur, cab.id_cabang_bank, ms.id_status_deb, de.id_denda
	ORDER BY nama_debitur ASC

	*/

	public function get_tot_pot($status, $tgl_awal,$tgl_akhir,$potensial,$bank)
	{
		$this->db->select('d.id_debitur,d.nama_debitur,d.bunga, ca.cabang_asuransi, d.no_klaim, cab.cabang_bank, d.subrogasi, sum(r.nominal) as recoveries, ms.status_deb, p.nama_proses');
		$this->db->from('debitur as d');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'INNER');
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
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = ca.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->where('asr.id_asuransi', 2);
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (empty($tgl_akhir) && !empty($tgl_awal) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($potensial)) {
				$this->db->where('ms.status_deb', $potensial);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		if ($status == 'Potensial') {
			$this->db->where('ms.status_deb', 'Potensial');
		} else {
			$this->db->where('ms.status_deb', 'Tidak Potensial');
		}
		
		$this->db->group_by('d.id_debitur');
		$this->db->group_by('cab.id_cabang_bank');
		$this->db->group_by('ca.id_cabang_asuransi');
		$this->db->group_by('ms.id_status_deb');
		$this->db->group_by('p.id_proses');
		$this->db->order_by('d.nama_debitur', 'asc');

		return $this->db->get()->result_array();
	}

	public function get_data_pot($status, $tgl_awal,$tgl_akhir,$potensial,$bank)
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
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = ca.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->where('asr.id_asuransi', 2);
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (empty($tgl_akhir) && !empty($tgl_awal) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($potensial)) {
				$this->db->where('ms.status_deb', $potensial);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		if ($status == 'Potensial') {
			$this->db->where('ms.status_deb', 'Potensial');
		} else {
			$this->db->where('ms.status_deb', 'Tidak Potensial');
		}
		
		$this->db->group_by('d.id_debitur');
		$this->db->group_by('cab.id_cabang_bank');
		$this->db->group_by('ca.id_cabang_asuransi');
		$this->db->group_by('ms.id_status_deb');
		$this->db->group_by('p.id_proses');
		$this->db->order_by('d.nama_debitur', 'asc');

		$hasil = $this->db->get()->result_array();
		$value = array();

		$tot_shs = 0;
		$tot_bunga = 0;
		$tot_subrogasi = 0;
		$tot_denda = 0;
		$tot_tagihan = 0;
		$tot_recov = 0;
		foreach ($hasil as $h) {
			$id_debitur 	 = $h['id_debitur'];
			$bunga 			 = $h['bunga'];
			$subrogasi 		 = $h['subrogasi'];
			$recoveries 	 = $h['recoveries'];

			// mencari denda debitur
			
			$this->db->select('*');
			$this->db->from('denda as d');
			$this->db->where('d.id_debitur', $id_debitur);
			$this->db->order_by('d.add_time', 'desc');
			$this->db->limit(1);
			$denda = $this->db->get()->row('nominal_denda');

			$tot_shs += ($subrogasi-$recoveries);

			$tot_bunga += $bunga;
			$tot_subrogasi += $subrogasi;
			$tot_denda += $denda;

			$tot_recov += $recoveries;


		}

			$tot_tagihan = $tot_bunga+$tot_subrogasi+$tot_denda;
			$sal_tagihan = ($tot_tagihan - $tot_recov);


		$value[] = [
			'tot_shs'		=> $tot_shs,
			'tot_tagihan' 	=> $sal_tagihan
			];

		return $value;
	}

	// fungsi untuk print data
	public function get_cari_print_data_r_noa($tgl_awal,$tgl_akhir,$potensial,$bank)
	{
		$this->db->select('d.id_debitur,d.nama_debitur,d.bunga, b.bank, ca.cabang_asuransi, d.no_klaim, cab.cabang_bank, d.subrogasi, sum(r.nominal) as recoveries, ms.status_deb, p.nama_proses');
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
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = ca.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->where('asr.id_asuransi', 2);
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (empty($tgl_akhir) && !empty($tgl_awal) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($potensial)) {
				$this->db->where('ms.status_deb', $potensial);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		$this->db->group_by('d.id_debitur');
		$this->db->group_by('b.bank');
		$this->db->group_by('cab.id_cabang_bank');
		$this->db->group_by('ca.id_cabang_asuransi');
		$this->db->group_by('ms.id_status_deb');
		$this->db->group_by('p.id_proses');
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
			$status_deb 	 = $h['status_deb'];
			$nama_proses	 = $h['nama_proses'];
			$bank 			 = $h['bank'];

			// mencari total shs perdebitur
			$shs 	= $subrogasi - $recoveries;

			// mencari denda debitur
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
				    'shs'				=> $shs,
				    'saldo_tagihan'		=> $saldo_tagihan,
				    'status_deb'		=> $status_deb,
				    'nama_proses'		=> $nama_proses,
				    'bank'				=> $bank
				];
		}
		return $value;
	}

	public function get_tot_noa()
	{
		return $this->db->get('debitur')->num_rows();
	}

	public function get_sudah_ots()
	{
		$this->db->from('ots');
		$this->db->group_by('id_debitur');
		$this->db->group_by('id_ots');

		return $this->db->get()->num_rows();
	}

	public function get_status_pot($status, $tgl_awal,$tgl_akhir,$potensial,$bank)
	{
		$this->db->select('d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries, ms.status_deb');
		$this->db->from('debitur as d');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
		$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = ca.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->where('asr.id_asuransi', 2);
		$this->db->where('o.status', 1);
		$this->db->where('ms.status_deb', $status);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (!empty($tgl_akhir) && empty($tgl_awal) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($potensial)) {
				$this->db->where('ms.status_deb', $potensial);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		$this->db->group_by('d.id_debitur');
		$this->db->group_by('cab.id_cabang_bank');
		$this->db->group_by('ms.id_status_deb');
		$this->db->group_by('b.bank');
		$this->db->order_by('d.nama_debitur', 'asc');

		return $this->db->count_all_results();
	}

	public function get_non_pot($tabel)
	{
		$this->db->from('ots as o');
		$this->db->join("$tabel as a", 'a.id_ots = o.id_ots', 'inner');
		$this->db->where('o.status', 1);

		return $this->db->count_all_results();
	}

	public function get_tot_subrogasi($tgl_awal,$tgl_akhir,$potensial,$bank)
	{
		$this->db->select('sum(d.subrogasi) as total_subrogasi');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');	
		$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = ca.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->where('asr.id_asuransi', 2);
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (empty($tgl_akhir) && !empty($tgl_awal) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($potensial)) {
				$this->db->where('ms.status_deb', $potensial);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		return $this->db->get()->row_array();
	}

	public function get_tot_denda($tgl_awal,$tgl_akhir,$potensial,$bank)
	{		
		$this->db->select('d.id_debitur');
		$this->db->distinct();
		$this->db->from('denda as e');
		$this->db->join('debitur as d', 'e.id_debitur = d.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');	
		$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = ca.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->where('asr.id_asuransi', 2);
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (empty($tgl_akhir) && !empty($tgl_awal) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($potensial)) {
				$this->db->where('ms.status_deb', $potensial);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		$hasil_1 = $this->db->get()->result_array();
		$value = array();
		$tot_denda = 0;
		foreach ($hasil_1 as $h) {
			$id_debitur = $h['id_debitur'];

			$this->db->select('*');
			$this->db->from('denda as e');
			$this->db->join('debitur as d', 'e.id_debitur = d.id_debitur', 'left');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
			$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');	
			$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
			$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
			$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'inner');
			$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = ca.id_korwil_asuransi','INNER');
			$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			$this->db->where('asr.id_asuransi', 2);
			$this->db->where('o.status', 1);
			$this->db->where('e.id_debitur', $id_debitur);

			if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
				if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
				}
				if (empty($tgl_akhir) && !empty($tgl_awal) && empty($potensial) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
				}
				if (!empty($potensial)) {
					$this->db->where('ms.status_deb', $potensial);
				}
				if (!empty($bank)) {
					$this->db->where('b.bank', $bank);
				}
				if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($bank)) {
					$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
				}
			}

			$this->db->order_by('e.add_time', 'desc');
			$this->db->limit(1);
			$denda = $this->db->get()->row('nominal_denda');

			$tot_denda += $denda;

			$value[] = [
					'tot_denda' => $tot_denda
				];
			
		}

		return $value;

	}

	public function get_tot_bunga($tgl_awal,$tgl_akhir,$potensial,$bank)
	{
		/*$this->db->select('sum(bunga) as total_bunga');
		$this->db->from('debitur');*/

		$this->db->select('sum(bunga) as total_bunga');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');	
		$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = ca.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->where('asr.id_asuransi', 2);
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (empty($tgl_akhir) && !empty($tgl_awal) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($potensial)) {
				$this->db->where('ms.status_deb', $potensial);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		return $this->db->get()->row_array();
	}

	public function get_tot_recov($tgl_awal,$tgl_akhir,$potensial,$bank)
	{
		$this->db->select('sum(nominal) as tot_nominal_recov');
		$this->db->from('recoveries as r');
		$this->db->join('debitur as d', 'd.id_debitur = r.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');	
		$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = ca.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->where('asr.id_asuransi', 2);
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($potensial) || !empty($bank)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (empty($tgl_akhir) && !empty($tgl_awal) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($potensial)) {
				$this->db->where('ms.status_deb', $potensial);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($potensial) && empty($bank)) {
				$this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		return $this->db->get()->row_array();
	}

}

/* End of file M_noa.php */
/* Location: ./application/models/M_noa.php */