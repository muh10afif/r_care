<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_recov extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->cabang_as = $this->session->userdata('cabang_as');
	}
	
	public function cari_data($tabel, $where)
	{
		return $this->db->get_where($tabel, $where);
	}

	public function get_total_total($id_karyawan)
	{
		// SELECT count(d.id_debitur) as jml_ots FROM debitur as d JOIN penempatan as p ON p.id_capem_bank = d.id_capem_bank JOIN karyawan as w ON w.id_karyawan = p.id_karyawan JOIN kunjungan as kj ON kj.id_debitur = d.id_debitur WHERE w.id_karyawan = k.id_karyawan

		$this->db->select('d.id_debitur, d.nama_debitur');
		$this->db->from('debitur as d');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as w', 'w.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('kunjungan as j', 'j.id_debitur = d.id_debitur', 'inner');
		$this->db->where('w.id_karyawan', $id_karyawan);

		$this->db->group_by('d.id_debitur');
		$this->db->group_by('d.nama_debitur');
				
		return $this->db->get();
		
	}

	// AWAL DATA TABLES RECOV

	public function get_data_recov_as($dt)
	{
		$this->_get_datatables_query_recov_as($dt);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
	}

	var $kolom_order_recov_as = [null, 'k.nama_lengkap'];
    var $kolom_cari_recov_as  = ['LOWER(k.nama_lengkap)'];
    var $order_recov_as       = ['k.id_karyawan' => 'asc'];

	public function _get_datatables_query_recov_as($dt)
	{
		$id_spk = $dt['spk'];
		
		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$spk = "and de.id_spk is null";
			} else {
				$spk = "and de.id_spk = $id_spk";
			}
		} else {
			$spk = "";
		}

		$this->db->select("k.id_karyawan, k.nama_lengkap, (SELECT count(de.id_debitur) as noa_kelolaan FROM debitur as de JOIN penempatan as p ON p.id_capem_bank = de.id_capem_bank JOIN karyawan as w ON w.id_karyawan = p.id_karyawan WHERE w.id_karyawan = k.id_karyawan $spk), (SELECT count(de.id_debitur) as jml_ots FROM debitur as de JOIN penempatan as p ON p.id_capem_bank = de.id_capem_bank JOIN karyawan as w ON w.id_karyawan = p.id_karyawan JOIN kunjungan as kj ON kj.id_debitur = de.id_debitur WHERE w.id_karyawan = k.id_karyawan $spk), (SELECT sum(recoveries_awal_asuransi) as recoveries_awal_as FROM debitur as de join penempatan as e ON e.id_capem_bank = de.id_capem_bank where de.id_capem_bank = e.id_capem_bank and e.id_karyawan = k.id_karyawan $spk), (SELECT sum(nominal) as tot_recov_as FROM tr_recov_as as ta join debitur as de ON de.id_debitur = ta.id_debitur join penempatan as e ON e.id_capem_bank = de.id_capem_bank where e.id_karyawan = k.id_karyawan $spk)");
		$this->db->from('karyawan k');
		$this->db->join('penempatan p', 'p.id_karyawan = k.id_karyawan', 'inner');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur', 'inner');
		
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

            $this->db->where("CAST(r.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}
		
		$this->db->group_by('k.id_karyawan')->group_by('k.nama_lengkap');

		$b = 0;

        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $this->kolom_cari_recov_as;

        foreach ($kolom_cari as $cari) {
            if ($input_cari) {
                if ($b === 0) {
                    $this->db->group_start();
                    $this->db->like($cari, $input_cari);
                } else {
                    $this->db->or_like($cari, $input_cari);
                }

                if ((count($kolom_cari) - 1) == $b ) {
                    $this->db->group_end();
                }
            }

            $b++;
        }

        if (isset($_POST['order'])) {

            $kolom_order = $this->kolom_order_recov_as;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($this->order_recov_as)) {
            
            $order = $this->order_recov_as;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
	}

	public function jumlah_semua_recov_as($dt)
	{
		$id_spk = $dt['spk'];
		
		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$spk = "and de.id_spk is null";
			} else {
				$spk = "and de.id_spk = $id_spk";
			}
		} else {
			$spk = "";
		}

		$this->db->select("k.id_karyawan, k.nama_lengkap, (SELECT count(de.id_debitur) as noa_kelolaan FROM debitur as de JOIN penempatan as p ON p.id_capem_bank = de.id_capem_bank JOIN karyawan as w ON w.id_karyawan = p.id_karyawan WHERE w.id_karyawan = k.id_karyawan $spk), (SELECT count(de.id_debitur) as jml_ots FROM debitur as de JOIN penempatan as p ON p.id_capem_bank = de.id_capem_bank JOIN karyawan as w ON w.id_karyawan = p.id_karyawan JOIN kunjungan as kj ON kj.id_debitur = de.id_debitur WHERE w.id_karyawan = k.id_karyawan $spk), (SELECT sum(recoveries_awal_asuransi) as recoveries_awal_as FROM debitur as de join penempatan as e ON e.id_capem_bank = de.id_capem_bank where de.id_capem_bank = e.id_capem_bank and e.id_karyawan = k.id_karyawan $spk), (SELECT sum(nominal) as tot_recov_as FROM tr_recov_as as ta join debitur as de ON de.id_debitur = ta.id_debitur join penempatan as e ON e.id_capem_bank = de.id_capem_bank where e.id_karyawan = k.id_karyawan $spk)");
		$this->db->from('karyawan k');
		$this->db->join('penempatan p', 'p.id_karyawan = k.id_karyawan', 'inner');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur', 'inner');
		
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

            $this->db->where("CAST(r.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}
		
		$this->db->group_by('k.id_karyawan')->group_by('k.nama_lengkap');

		return $this->db->count_all_results();
	}

	public function jumlah_filter_recov_as($dt)
	{
		$this->_get_datatables_query_recov_as($dt);
        return $this->db->get()->num_rows();
	}

	public function get_data_recov($dt)
	{
		$this->_get_datatables_query_recov($dt);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
	}

	var $kolom_order_recov = [null, 'd.no_reff', 'd.no_klaim', ' d.nama_debitur', 'b.bank', 'asr.asuransi'];
    var $kolom_cari_recov  = ['CAST(d.id_care as VARCHAR)', 'LOWER(d.no_klaim)', 'LOWER(d.nama_debitur)', 'LOWER(b.bank)', 'LOWER(asr.asuransi)', 'CAST(d.jumlah as VARCHAR)'];
    var $order_recov       = ['t.tgl_bayar' => 'desc'];

	public function _get_datatables_query_recov($dt)
	{
		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		if ($id_cabang_as != '') {

			// $this->db->select('d.id_care, d.no_reff, d.no_klaim, d.nama_debitur, cap.capem_bank, d.jumlah as jml_bayar, b.bank, asr.asuransi, (SELECT tgl_bayar as tgl_bayar_as FROM tr_recov_as where id_debitur = d.id_debitur order by tgl_bayar DESC LIMIT 1), d.recoveries_awal_bank, d.recoveries_awal_asuransi, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur)');

			// $this->db->from('debitur as d');
			// $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
			// $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
			// $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
			// $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
			// $this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
			// $this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			// $this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
			// $this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
			// $this->db->join('tr_recov_as as t', 't.id_debitur = d.id_debitur', 'inner');

			// $this->db->group_by('d.id_care');
			// $this->db->group_by('d.id_debitur');
			// $this->db->group_by('d.no_reff');
			// $this->db->group_by('d.no_klaim');
			// $this->db->group_by('d.nama_debitur');
			// $this->db->group_by('cap.capem_bank');
			// $this->db->group_by('d.jumlah');
			// $this->db->group_by('b.bank');
			// $this->db->group_by('asr.asuransi');
			// $this->db->group_by('d.recoveries_awal_bank');
			// $this->db->group_by('d.recoveries_awal_asuransi');

			$this->db->select('d.id_care, d.no_reff, d.no_klaim, d.nama_debitur, cap.capem_bank, t.nominal as jml_bayar, t.tgl_bayar, b.bank, asu.cabang_asuransi, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur = d.id_debitur)');

			$this->db->from('debitur as d');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
			$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
			$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
			$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
			$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
			$this->db->join('tr_recov_as as t', 't.id_debitur = d.id_debitur', 'inner');

		
		} else {

			// $this->db->select('d.id_care, d.no_reff, d.no_klaim, d.nama_debitur, cap.capem_bank, d.jumlah as jml_bayar, b.bank, asr.asuransi, (SELECT tgl_bayar as tgl_bayar_as FROM tr_recov_as where id_debitur = d.id_debitur order by tgl_bayar DESC LIMIT 1), d.recoveries_awal_bank, d.recoveries_awal_asuransi, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur), (SELECT sum(nominal) as tot_nominal_bank FROM tr_recov_bank where id_debitur = d.id_debitur)');

			// $this->db->from('debitur as d');
			// $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
			// $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
			// $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
			// $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
			// $this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
			// $this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			// $this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
			// $this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
			// $this->db->join('tr_recov_as as t', 't.id_debitur = d.id_debitur', 'inner');

			// $this->db->group_by('d.id_care');
			// $this->db->group_by('d.id_debitur');
			// $this->db->group_by('d.no_reff');
			// $this->db->group_by('d.no_klaim');
			// $this->db->group_by('d.nama_debitur');
			// $this->db->group_by('cap.capem_bank');
			// $this->db->group_by('d.jumlah');
			// $this->db->group_by('b.bank');
			// $this->db->group_by('asr.asuransi');
			// $this->db->group_by('d.recoveries_awal_bank');
			// $this->db->group_by('d.recoveries_awal_asuransi');

			$this->db->select('d.id_care, d.no_reff, d.no_klaim, d.nama_debitur, cap.capem_bank, t.nominal as jml_bayar, t.tgl_bayar, b.bank, asu.cabang_asuransi,(SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur = d.id_debitur), (SELECT sum(nominal) as tot_nominal_bank FROM tr_recov_bank WHERE id_debitur = d.id_debitur)');

			$this->db->from('debitur as d');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
			$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
			$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
			$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
			$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
			$this->db->join('tr_recov_as as t', 't.id_debitur = d.id_debitur', 'inner');

		}

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}
		
		// $this->db->where('d.ots', 1);

		if ($dt['level'] == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($dt['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $dt['asuransi']);
			}
		}
		
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

            $this->db->where("CAST(t.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$b = 0;

        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $this->kolom_cari_recov;

        foreach ($kolom_cari as $cari) {
            if ($input_cari) {
                if ($b === 0) {
                    $this->db->group_start();
                    $this->db->like($cari, $input_cari);
                } else {
                    $this->db->or_like($cari, $input_cari);
                }

                if ((count($kolom_cari) - 1) == $b ) {
                    $this->db->group_end();
                }
            }

            $b++;
        }

        if (isset($_POST['order'])) {

            $kolom_order = $this->kolom_order_recov;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($this->order_recov)) {
            
            $order = $this->order_recov;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
	}

	public function jumlah_semua_recov($dt)
	{
		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		if ($id_cabang_as != '') {

			// $this->db->select('d.id_care, d.no_reff, d.no_klaim, d.nama_debitur, cap.capem_bank, d.jumlah as jml_bayar, b.bank, asr.asuransi, (SELECT tgl_bayar as tgl_bayar_as FROM tr_recov_as where id_debitur = d.id_debitur order by tgl_bayar DESC LIMIT 1), d.recoveries_awal_bank, d.recoveries_awal_asuransi, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur)');

			// $this->db->from('debitur as d');
			// $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
			// $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
			// $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
			// $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
			// $this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
			// $this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			// $this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
			// $this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
			// $this->db->join('tr_recov_as as t', 't.id_debitur = d.id_debitur', 'inner');

			// $this->db->group_by('d.id_care');
			// $this->db->group_by('d.id_debitur');
			// $this->db->group_by('d.no_reff');
			// $this->db->group_by('d.no_klaim');
			// $this->db->group_by('d.nama_debitur');
			// $this->db->group_by('cap.capem_bank');
			// $this->db->group_by('d.jumlah');
			// $this->db->group_by('b.bank');
			// $this->db->group_by('asr.asuransi');
			// $this->db->group_by('d.recoveries_awal_bank');
			// $this->db->group_by('d.recoveries_awal_asuransi');

			$this->db->select('d.id_care, d.no_reff, d.no_klaim, d.nama_debitur, cap.capem_bank, t.nominal as jml_bayar, t.tgl_bayar, b.bank, asu.cabang_asuransi, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur = d.id_debitur)');

			$this->db->from('debitur as d');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
			$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
			$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
			$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
			$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
			$this->db->join('tr_recov_as as t', 't.id_debitur = d.id_debitur', 'inner');

		
		} else {

			// $this->db->select('d.id_care, d.no_reff, d.no_klaim, d.nama_debitur, cap.capem_bank, d.jumlah as jml_bayar, b.bank, asr.asuransi, (SELECT tgl_bayar as tgl_bayar_as FROM tr_recov_as where id_debitur = d.id_debitur order by tgl_bayar DESC LIMIT 1), d.recoveries_awal_bank, d.recoveries_awal_asuransi, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur), (SELECT sum(nominal) as tot_nominal_bank FROM tr_recov_bank where id_debitur = d.id_debitur)');

			// $this->db->from('debitur as d');
			// $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
			// $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
			// $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
			// $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
			// $this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
			// $this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			// $this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
			// $this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
			// $this->db->join('tr_recov_as as t', 't.id_debitur = d.id_debitur', 'inner');

			// $this->db->group_by('d.id_care');
			// $this->db->group_by('d.id_debitur');
			// $this->db->group_by('d.no_reff');
			// $this->db->group_by('d.no_klaim');
			// $this->db->group_by('d.nama_debitur');
			// $this->db->group_by('cap.capem_bank');
			// $this->db->group_by('d.jumlah');
			// $this->db->group_by('b.bank');
			// $this->db->group_by('asr.asuransi');
			// $this->db->group_by('d.recoveries_awal_bank');
			// $this->db->group_by('d.recoveries_awal_asuransi');

			$this->db->select('d.id_care, d.no_reff, d.no_klaim, d.nama_debitur, cap.capem_bank, t.nominal as jml_bayar, t.tgl_bayar, b.bank, asu.cabang_asuransi,(SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as WHERE id_debitur = d.id_debitur), (SELECT sum(nominal) as tot_nominal_bank FROM tr_recov_bank WHERE id_debitur = d.id_debitur)');

			$this->db->from('debitur as d');
			$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
			$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
			$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
			$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
			$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
			$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
			$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
			$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
			$this->db->join('tr_recov_as as t', 't.id_debitur = d.id_debitur', 'inner');

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

            $this->db->where("CAST(t.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		return $this->db->count_all_results();
	}

	public function jumlah_filter_recov($dt)
	{
		$this->_get_datatables_query_recov($dt);
		return $this->db->get()->num_rows();
		
	}

	// AKHIR DATA TABLES RECOV

	public function get_cari_data_wilayah_ver($dt)
	{
		$this->db->select('rs.nominal, d.recoveries_awal_asuransi');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('tr_recov_as as rs', 'rs.id_debitur = d.id_debitur', 'inner');

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

            $this->db->where("CAST(rs.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$hasil = $this->db->get()->result_array();

		$tot_nominal 	= 0;
		$tot_recov_awal = 0;
		foreach ($hasil as $h) {
			$tot_nominal	 += $h['nominal'];
			$tot_recov_awal += $h['recoveries_awal_asuransi'];
		 }

		 $tot_nominal_as = $tot_nominal + $tot_recov_awal;
		 
		 $this->db->select('d.subrogasi_as');
		 $this->db->from('debitur as d');
		 $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		 $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		 $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		 $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		 $this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		 $this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		 $this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		 $this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		 $this->db->join('tr_recov_as as rs', 'rs.id_debitur = d.id_debitur', 'inner');
 
		 if ($dt['asuransi'] != 'a') {
			 $this->db->where('asr.id_asuransi', $dt['asuransi']);
		 }
		 if ($dt['cabang_asuransi'] != 'a') {
			 $this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
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
 
			 $this->db->where("CAST(rs.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		 }
		 if ($dt['verifikator'] != 'a') {
			 $this->db->where('k.id_karyawan', $dt['verifikator']);
			 
		 }
 
		$hasil2 = $this->db->get()->result_array();

		$tot_subrogasi_as = 0;
		foreach ($hasil2 as $s) {
			$tot_subrogasi_as += $s['subrogasi_as'];
		}

		$this->db->select('da.total_harga');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('dokumen_asset as da', 'da.id_debitur = d.id_debitur', 'inner');
		$this->db->join('tr_recov_as as rs', 'rs.id_debitur = d.id_debitur', 'inner');
		
		if ($dt['asuransi'] != 'a') {
			$this->db->where('asr.id_asuransi', $dt['asuransi']);
		}
		if ($dt['cabang_asuransi'] != 'a') {
			$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
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

			$this->db->where("CAST(rs.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$this->db->where('da.id_jenis_dok', 2)->where('da.status', 1);

		$asset = $this->db->get()->result_array();

		$tot_harga = 0;
		foreach ($asset as $s) {
			$tot_harga = $s['total_harga'];
		}

		if ($tot_subrogasi_as == 0) {
			$crp = 0;
		} else {
			$crp = ($tot_nominal_as / $tot_subrogasi_as );
		}

		$potensi_recov = $tot_nominal_as + $tot_harga;

		$value[] = [
			'tot_nominal' 	=> $tot_nominal_as,
			'crp'		  	=> $crp,
			'potensi_recov' => $potensi_recov
		];

		return $value;
	}

	// 06-04-2021
	public function get_cari_data_wilayah_ver_bank($dt)
	{
		$this->db->select('rs.nominal, d.recoveries_awal_bank');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('tr_recov_bank as rs', 'rs.id_debitur = d.id_debitur', 'inner');

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

            $this->db->where("CAST(rs.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$hasil = $this->db->get()->result_array();

		$tot_nominal 	= 0;
		$tot_recov_awal = 0;
		foreach ($hasil as $h) {
			$tot_nominal	 += $h['nominal'];
			$tot_recov_awal += $h['recoveries_awal_bank'];
		 }

		 $tot_nominal_as = $tot_nominal + $tot_recov_awal;
		 
		 $this->db->select('d.subrogasi_as');
		 $this->db->from('debitur as d');
		 $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		 $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		 $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		 $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		 $this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		 $this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		 $this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		 $this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		 $this->db->join('tr_recov_bank as rs', 'rs.id_debitur = d.id_debitur', 'inner');
 
		 if ($dt['asuransi'] != 'a') {
			 $this->db->where('asr.id_asuransi', $dt['asuransi']);
		 }
		 if ($dt['cabang_asuransi'] != 'a') {
			 $this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
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
 
			 $this->db->where("CAST(rs.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		 }
		 if ($dt['verifikator'] != 'a') {
			 $this->db->where('k.id_karyawan', $dt['verifikator']);
			 
		 }
 
		$hasil2 = $this->db->get()->result_array();

		$tot_subrogasi_as = 0;
		foreach ($hasil2 as $s) {
			$tot_subrogasi_as += $s['subrogasi_as'];
		}

		$this->db->select('da.total_harga');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('dokumen_asset as da', 'da.id_debitur = d.id_debitur', 'inner');
		$this->db->join('tr_recov_bank as rs', 'rs.id_debitur = d.id_debitur', 'inner');
		
		if ($dt['asuransi'] != 'a') {
			$this->db->where('asr.id_asuransi', $dt['asuransi']);
		}
		if ($dt['cabang_asuransi'] != 'a') {
			$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
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

			$this->db->where("CAST(rs.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$this->db->where('da.id_jenis_dok', 2)->where('da.status', 1);

		$asset = $this->db->get()->result_array();

		$tot_harga = 0;
		foreach ($asset as $s) {
			$tot_harga = $s['total_harga'];
		}

		if ($tot_subrogasi_as == 0) {
			$crp = 0;
		} else {
			$crp = ($tot_nominal_as / $tot_subrogasi_as );
		}

		$potensi_recov = $tot_nominal_as + $tot_harga;

		$value[] = [
			'tot_nominal' 	=> $tot_nominal_as,
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
		$this->db->group_by('k.nama_lengkap, cap.capem_bank');

		$this->db->order_by('k.nama_lengkap', 'asc');
		return $this->db->get();
	}

	// fungsi untuk print data
	public function get_cari_print_data_r_recov($dt)
	{
		$this->db->select('(SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur), (SELECT sum(nominal) as tot_nominal_bank FROM tr_recov_bank where id_debitur = d.id_debitur), asu.cabang_asuransi, cab.cabang_bank, b.bank, cap.capem_bank, r.no_rek, d.no_reff, d.nama_debitur, d.no_klaim, d.subrogasi_as, d.recoveries_awal_asuransi,recoveries_awal_bank,d.bunga, d.denda, r.tgl_bayar, r.nominal');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur', 'inner');

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

            $this->db->where("CAST(r.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$this->db->order_by('r.tgl_bayar', 'desc');

		return $this->db->get();
		
	}

}

/* End of file M_recov.php */
/* Location: ./application/models/M_recov.php */