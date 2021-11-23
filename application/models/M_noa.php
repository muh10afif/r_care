<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_noa extends CI_Model {

	public function get_tot_r_noa($dt, $aksi)
	{
		$this->db->select('d.id_care, d.no_klaim, d.nama_debitur, b.bank, cab.cabang_bank, asr.asuransi, d.subrogasi_as, d.bunga, d.denda, d.recoveries_awal_asuransi, d.potensial, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}
		
		if ($aksi == 'sudah_ots') {
			$this->db->where('d.ots', 1);
		}
		if ($aksi == 'potensial') {
			$this->db->where('d.potensial', 1);
		}
		if ($aksi == 'non_potensial') {
			$this->db->where('d.potensial', 0);
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

		$this->db->group_by('d.id_care')->group_by('d.id_debitur')->group_by('d.no_klaim')->group_by('d.nama_debitur')->group_by('b.bank')->group_by('cab.cabang_bank')->group_by('asu.cabang_asuransi')->group_by('d.subrogasi_as')->group_by('d.recoveries_awal_asuransi')->group_by('d.potensial')->group_by('asr.asuransi');

		return $this->db->get();
		
	}

	// AWAL DATATABLES

	public function get_data_noa_as($dt)
	{
		$this->_get_datatables_query_noa_as($dt);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
	}

	var $kolom_order_noa_as = [null, 'k.nama_lengkap'];
    var $kolom_cari_noa_as  = ['LOWER(k.nama_lengkap)'];
	var $order_noa_as       = ['k.id_karyawan' => 'asc'];
	
	public function _get_datatables_query_noa_as($dt)
	{
		$this->db->select('k.id_karyawan, k.nama_lengkap, (SELECT count(d.id_debitur) as noa_kelolaan FROM debitur as d JOIN penempatan as p ON p.id_capem_bank = d.id_capem_bank JOIN karyawan as w ON w.id_karyawan = p.id_karyawan WHERE w.id_karyawan = k.id_karyawan), (SELECT sum(recoveries_awal_asuransi) as recoveries_awal_as FROM debitur as d join penempatan as e ON e.id_capem_bank = d.id_capem_bank where d.id_capem_bank = e.id_capem_bank and e.id_karyawan = k.id_karyawan), (SELECT sum(subrogasi_as) as subrogasi_as FROM debitur as d join penempatan as e ON e.id_capem_bank = d.id_capem_bank where d.id_capem_bank = e.id_capem_bank and e.id_karyawan = k.id_karyawan),(SELECT sum(bunga) as bunga FROM debitur as d join penempatan as e ON e.id_capem_bank = d.id_capem_bank where d.id_capem_bank = e.id_capem_bank and e.id_karyawan = k.id_karyawan and d.ots = 1),(SELECT sum(denda) as denda FROM debitur as d join penempatan as e ON e.id_capem_bank = d.id_capem_bank where d.id_capem_bank = e.id_capem_bank and e.id_karyawan = k.id_karyawan), (SELECT sum(nominal) as tot_recov_as FROM tr_recov_as as ta join debitur as d ON d.id_debitur = ta.id_debitur join penempatan as e ON e.id_capem_bank = d.id_capem_bank where e.id_karyawan = k.id_karyawan)');
		$this->db->from('karyawan k');
		$this->db->join('penempatan p', 'p.id_karyawan = k.id_karyawan', 'inner');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

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
		
		$this->db->group_by('k.id_karyawan')->group_by('k.nama_lengkap');

		$b = 0;

        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $this->kolom_cari_noa_as;

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

            $kolom_order = $this->kolom_order_noa_as;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($this->order_noa_as)) {
            
            $order = $this->order_noa_as;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
	}

	public function jumlah_semua_noa_as($dt)
	{
		$id_spk = $dt['spk'];

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$spk = "and de.id_spk is null";
			} else {
				$spk = "and de.id_spk = $id_spk";
			}
		}

		$this->db->select('k.id_karyawan, k.nama_lengkap, (SELECT count(d.id_debitur) as noa_kelolaan FROM debitur as d JOIN penempatan as p ON p.id_capem_bank = d.id_capem_bank JOIN karyawan as w ON w.id_karyawan = p.id_karyawan WHERE w.id_karyawan = k.id_karyawan), (SELECT sum(recoveries_awal_asuransi) as recoveries_awal_as FROM debitur as d join penempatan as e ON e.id_capem_bank = d.id_capem_bank where d.id_capem_bank = e.id_capem_bank and e.id_karyawan = k.id_karyawan), (SELECT sum(subrogasi_as) as subrogasi_as FROM debitur as d join penempatan as e ON e.id_capem_bank = d.id_capem_bank where d.id_capem_bank = e.id_capem_bank and e.id_karyawan = k.id_karyawan),(SELECT sum(bunga) as bunga FROM debitur as d join penempatan as e ON e.id_capem_bank = d.id_capem_bank where d.id_capem_bank = e.id_capem_bank and e.id_karyawan = k.id_karyawan and d.ots = 1),(SELECT sum(denda) as denda FROM debitur as d join penempatan as e ON e.id_capem_bank = d.id_capem_bank where d.id_capem_bank = e.id_capem_bank and e.id_karyawan = k.id_karyawan), (SELECT sum(nominal) as tot_recov_as FROM tr_recov_as as ta join debitur as d ON d.id_debitur = ta.id_debitur join penempatan as e ON e.id_capem_bank = d.id_capem_bank where e.id_karyawan = k.id_karyawan)');
		$this->db->from('karyawan k');
		$this->db->join('penempatan p', 'p.id_karyawan = k.id_karyawan', 'inner');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

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
		
		$this->db->group_by('k.id_karyawan')->group_by('k.nama_lengkap');

		return $this->db->count_all_results();
	}

	public function jumlah_filter_noa_as($dt)
	{
		$this->_get_datatables_query_noa_as($dt);

		return $this->db->get()->num_rows();
		
	}

	public function cari_tgl_bayar_awal($level)
	{
		$this->db->select('r.tgl_bayar');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur', 'inner');
		
		if ($level == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		}
		
		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');
		
		if ($level == 'asuransi') {
			// $this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
			
		}

		$this->db->order_by('r.tgl_bayar', 'asc');
		
		return $this->db->get();
				
	}
	
	public function get_data_noa($dt)
	{
		$this->_get_datatables_query_noa($dt);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
	}

	var $kolom_order_noa = [null, 'd.nama_debitur', 'd.no_klaim', 'b.bank', 'cab.cabang_bank', 'asu.cabang_asuransi', 'd.subrogasi_as',null,null,'CAST(d.potensial as VARCHAR)'];
    var $kolom_cari_noa  = ['LOWER(d.nama_debitur)', 'LOWER(d.no_klaim)', 'LOWER(b.bank)', 'LOWER(cab.cabang_bank)', 'LOWER(asr.asuransi)', 'CAST(d.subrogasi_as as VARCHAR)'];
    var $order_noa       = ['d.id_debitur' => 'asc'];

	public function _get_datatables_query_noa($dt)
	{
		$this->db->select('d.id_care, d.no_klaim, d.nama_debitur, b.bank, cab.cabang_bank, asu.cabang_asuransi, d.subrogasi_as, d.recoveries_awal_asuransi, d.potensial, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur', 'left');

		// $this->db->where('d.ots', 1);

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
        if ($dt['tanggal_akhir'] != '') {

            $tgl_awal   = $dt['tanggal_awal']; 
            $tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

            $this->db->where("CAST(r.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$this->db->group_by('d.id_care')->group_by('d.id_debitur')->group_by('d.no_klaim')->group_by('d.nama_debitur')->group_by('b.bank')->group_by('cab.cabang_bank')->group_by('asu.cabang_asuransi')->group_by('d.subrogasi_as')->group_by('d.recoveries_awal_asuransi')->group_by('d.potensial');
		

		$b = 0;

        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $this->kolom_cari_noa;

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

            $kolom_order = $this->kolom_order_noa;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($this->order_noa)) {
            
            $order = $this->order_noa;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
	}

	public function jumlah_semua_noa($dt)
	{
		$this->db->select('d.id_care, d.no_klaim, d.nama_debitur, b.bank, cab.cabang_bank, asu.cabang_asuransi, d.subrogasi_as, d.recoveries_awal_asuransi, d.potensial, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur', 'left');
		
		// $this->db->where('d.ots', 1);

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
        if ($dt['tanggal_akhir'] != '') {

            $tgl_awal   = $dt['tanggal_awal']; 
            $tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

            $this->db->where("CAST(r.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$this->db->group_by('d.id_care')->group_by('d.id_debitur')->group_by('d.no_klaim')->group_by('d.nama_debitur')->group_by('b.bank')->group_by('cab.cabang_bank')->group_by('asu.cabang_asuransi')->group_by('d.subrogasi_as')->group_by('d.recoveries_awal_asuransi')->group_by('d.potensial');

		return $this->db->count_all_results();
	}

	public function jumlah_filter_noa($dt)
	{
		$this->_get_datatables_query_noa($dt);
		return $this->db->get()->num_rows();
	}

	// AKHIR DATATABLES

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
		return $this->db->get('m_bank');
	}

	var $column_order   = ['d.id_debitur', 'd.nama_debitur', 'cab.cabang_bank', 'b.bank', 'd.subrogasi'];
    var $column_search  = ['d.id_debitur', 'd.nama_debitur', 'cab.cabang_bank', 'b.bank', 'd.subrogasi'];
    var $order          = ['d.nama_debitur' => 'asc'];

    public function _get_datatables_query()
    {
        $this->db->select('d.id_debitur, d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries');
		$this->db->from('debitur as d');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->group_by('d.id_debitur');
		$this->db->group_by('cab.id_cabang_bank');
		$this->db->group_by('b.bank');

		$i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

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
        
        
    }
 
    public function get_datatables()
    {
		$this->db->select('d.id_debitur, d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries');
		$this->db->from('debitur as d');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->group_by('d.id_debitur');
		$this->db->group_by('cab.id_cabang_bank');
		$this->db->group_by('b.bank');

		$i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
		}
		
		if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);

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
 
    public function count_filtered()
    {
		$this->db->select('d.id_debitur, d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries');
		$this->db->from('debitur as d');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->group_by('d.id_debitur');
		$this->db->group_by('cab.id_cabang_bank');
		$this->db->group_by('b.bank');

		$i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
		}

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
		
		return count($value);
    }
 
    public function count_all()
    {
        $this->db->select('d.id_debitur, d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries');
		$this->db->from('debitur as d');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
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

		return count($value);
    }

	public function get_data_r_noa()
	{
		$this->db->select('d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries, ms.status_deb');
		$this->db->from('debitur as d');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
		$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
		$this->db->where('o.status', 1);
		$this->db->group_by('d.id_debitur');
		$this->db->group_by('cab.id_cabang_bank');
		$this->db->group_by('ms.id_status_deb');
		$this->db->group_by('b.bank');
		$this->db->order_by('d.nama_debitur', 'asc');

		return $this->db->get()->result_array();

		// $this->db->select('d.id_debitur, d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries');
		// $this->db->from('debitur as d');
		// $this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		// $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		// $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		// $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		// $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		// $this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		// $this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
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
		// 	$this->db->group_by('d.id_debitur');
		// 	$this->db->group_by('cab.id_cabang_bank');
		// 	$this->db->group_by('ms.id_status_deb');
		// 	$this->db->group_by('b.bank');
		// 	$this->db->order_by('d.nama_debitur', 'asc');

		// 	$hasil_2 = $this->db->get()->row_array();

		// 	$value[] = 	['nama_debitur'	=> $nama_debitur,
		// 				 'cabang_bank'	=> $cabang_bank,
		// 				 'bank'			=> $bank,
		// 				 'subrogasi'	=> $subrogasi,
		// 				 'recoveries'	=> $recoveries,
		// 				 'status_deb'	=> $hasil_2['status_deb']
		// 				];
			
		// }

		// return $value;
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
		
		

		$this->db->select('d.id_debitur, d.nama_debitur, cab.cabang_bank, b.bank, d.subrogasi, sum(r.nominal) as recoveries');
		$this->db->from('debitur as d');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');

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

	public function get_data_noa_jml($dt)
	{
		$this->db->select('count(mt.id_tindakan_hukum) as jml_tindakan_hukum, mt.tindakan_hukum, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur), (SELECT sum(recoveries_awal_asuransi) as tot_recov_awal_as FROM debitur where id_debitur=d.id_debitur)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('kunjungan as j', 'j.id_debitur = d.id_debitur', 'inner');
		$this->db->join('tr_potensial as tp', 'tp.id_kunjungan = j.id_kunjungan', 'inner');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_potensial = tp.id_tr_potensial', 'inner');
		$this->db->join('m_tindakan_hukum as mt', 'mt.id_tindakan_hukum = th.id_tindakan_hukum', 'inner');

		$this->db->where('tp.status', 1);
		$this->db->where('tp.ots', 1);
		
		$this->db->where('d.ots', 1);

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

		$this->db->group_by('mt.tindakan_hukum');
		$this->db->group_by('d.id_debitur');
	
		return $this->db->get();
		
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
	public function get_cari_print_data_r_noa($dt,$aksi)
	{
		// $this->db->select('d.no_klaim, d.no_reff, d.nama_debitur, b.bank, cab.cabang_bank, asu.cabang_asuransi, d.subrogasi_as, d.bunga, d.denda, d.recoveries_awal_asuransi, d.potensial, pf.nama_proses, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur)');
		// $this->db->from('debitur as d');
		// $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		// $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		// $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		// $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		// $this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		// $this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		// $this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'left');
		// $this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		// $this->db->join('kunjungan as j', 'j.id_debitur = d.id_debitur', 'left');
		// $this->db->join('tr_potensial as tp', 'tp.id_kunjungan = j.id_kunjungan', 'left');
		// $this->db->join('tr_fu as f', 'f.id_tr_potensial = tp.id_tr_potensial', 'left');
		// $this->db->join('m_proses_fu as pf', 'pf.id_proses_fu = f.id_proses', 'left');

		// $this->db->where('f.status_fu', 1);
		
		// $this->db->where('d.ots', 1);

		// $this->db->select('d.id_care, d.*, d.no_klaim, d.nama_debitur, b.bank, cab.cabang_bank, cap.capem_bank, asu.cabang_asuransi, d.subrogasi_as, d.recoveries_awal_asuransi, d.potensial, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur)');
		// $this->db->from('debitur as d');
		// $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		// $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		// $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		// $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		// $this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		// $this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		// $this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'left');
		// $this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');

		// if ($aksi == 'potensial') {
		// 	$this->db->where('d.potensial', 1);
		// }
		// if ($aksi == 'non_potensial') {
		// 	$this->db->where('d.potensial', 0);
		// }

		// if ($dt['level'] == "syariah") {
		// 	$this->db->where('asr.id_asuransi', 2);
		// } else {
		// 	if ($dt['asuransi'] != 'a') {
		// 		$this->db->where('asr.id_asuransi', $dt['asuransi']);
		// 	}
		// }

		// $id_cabang_as = $this->session->userdata('cabang_as');

		// if ($dt['level'] == 'asuransi') {
		// 	$this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
		// } else {
		// 	if ($dt['cabang_asuransi'] != 'a') {
		// 		$this->db->where('asu.id_cabang_asuransi', $dt['cabang_asuransi']);
		// 	}
		// }

        // if ($dt['bank'] != 'a') {
        //     $this->db->where('b.id_bank', $dt['bank']);
        // }
        // if ($dt['cabang_bank'] != 'a') {
        //     $this->db->where('cab.id_cabang_bank', $dt['cabang_bank']);
        // }
        // if ($dt['capem_bank'] != 'a') {
        //     $this->db->where('cap.id_capem_bank', $dt['capem_bank']);
        // }
        // if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

        //     $tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
        //     $tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

        //     $this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		// }
		// if ($dt['verifikator'] != 'a') {
		// 	$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		// }

		$this->db->select('d.id_care, d.no_klaim, d.no_reff, d.nama_debitur, d.bunga, d.denda, b.bank, cap.capem_bank, cab.cabang_bank, asu.cabang_asuransi, d.subrogasi_as, d.recoveries_awal_asuransi, d.potensial, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->join('tr_recov_as as r', 'r.id_debitur = d.id_debitur', 'left');
		
		// $this->db->where('d.ots', 1);

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}

		if ($aksi == 'potensial') {
			$this->db->where('d.potensial', 1);
		}
		if ($aksi == 'non_potensial') {
			$this->db->where('d.potensial', 0);
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
        if ($dt['tanggal_akhir'] != '') {

            $tgl_awal   = $dt['tanggal_awal']; 
            $tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

            $this->db->where("CAST(r.tgl_bayar AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$this->db->group_by('d.id_care')->group_by('d.id_debitur')->group_by('d.no_klaim')->group_by('d.bunga')->group_by('denda')->group_by('d.no_reff')->group_by('cap.capem_bank')->group_by('d.nama_debitur')->group_by('b.bank')->group_by('cab.cabang_bank')->group_by('asu.cabang_asuransi')->group_by('d.subrogasi_as')->group_by('d.recoveries_awal_asuransi')->group_by('d.potensial');

		return $this->db->get();
		
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
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
		$this->db->join('ots_status_debitur as sd', 'sd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as ms', 'ms.id_status_deb = sd.id_status_deb', 'left');
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