<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_proses extends CI_Model {

	public function get_tindakan()
	{
		return $this->db->get('m_tindakan_hukum');
	}

	public function get_cari_data_wilayah_ver($dt)
	{
		// TOTAL TINDAKAN HUKUM SOMASI
		$this->db->select('d.id_care, d.no_klaim, d.nama_debitur');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur', 'inner');
		$this->db->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan', 'inner');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_potensial = tp.id_tr_potensial', 'inner');
		
		$this->db->where('d.ots', 1);
		$this->db->where("(th.id_tindakan_hukum= 3 OR th.id_tindakan_hukum= 4)");	

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

		$hasil1 = $this->db->get()->num_rows();

		$tot_tindakan_somasi = $hasil1;

		// TOTAL TINDAKAN HUKUM
		$this->db->select('d.id_care, d.no_klaim, d.nama_debitur');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur', 'inner');
		$this->db->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan', 'inner');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_potensial = tp.id_tr_potensial', 'inner');
		
		$this->db->where('d.ots', 1);

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}

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

            $this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$hasil2 = $this->db->get()->num_rows();

		$tot_tindakan = $hasil2;


		// NOMINAL RECOVERIES
		$this->db->select('tas.nominal');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('tr_recov_as as tas', 'tas.id_debitur = d.id_debitur', 'inner');
	
		$this->db->where('d.ots', 1);

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}

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

            $this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}
		
		$hasil3 = $this->db->get()->result_array();

		$tot_nominal = 0;

		foreach ($hasil3 as $h) {
			$tot_nominal += $h['nominal'];
 		}

		 // TOTAL HARGA ASSET
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
		$this->db->where('da.id_jenis_dok', 2);
		$this->db->where('da.status', 1);
		
		$this->db->where('d.ots', 1);

		if ($dt['spk'] != 'a') {
			if ($dt['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $dt['spk']);
			}
		}

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

            $this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		$asset = $this->db->get()->result_array();

		$tot_harga = 0;

		foreach ($asset as $s) {
			$tot_harga += $s['total_harga'];
		}

		$potensi_recov = $tot_nominal + $tot_harga;

		$value[] = [
			'potensi_recov'		  => $potensi_recov,
			'tot_tindakan_somasi' => $tot_tindakan_somasi,
			'tot_tindakan'		  => $tot_tindakan
		];

		return $value;

	}

	// Awal dataTable serverside 
	public function get_data_proses($dt)
	{
		$this->_get_datatables_query_proses($dt);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
	}

	var $kolom_order_proses = [null, 'd.id_care', 'd.no_klaim', ' d.nama_debitur', 'cap.capem_bank', 'asu.cabang_asuransi', 'sa.status_asset', 'mt.tindakan_hukum'];
    var $kolom_cari_proses  = ['CAST(d.id_care as VARCHAR)', 'LOWER(d.no_klaim)', 'LOWER(d.nama_debitur)', 'LOWER(cap.capem_bank)', 'LOWER(asu.cabang_asuransi)', 'LOWER(sa.status_asset)', 'LOWER(mt.tindakan_hukum)'];
    var $order_proses       = ['d.id_debitur' => 'asc'];

	public function _get_datatables_query_proses($dt)
	{
		$this->db->select('d.id_care, d.no_klaim, d.nama_debitur, cap.capem_bank, asu.cabang_asuransi, d.besar_klaim, d.subrogasi_as, d.recoveries_awal_bank, d.recoveries_awal_asuransi, sa.status_asset, mt.tindakan_hukum, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur), (SELECT sum(nominal) as tot_nominal_bank FROM tr_recov_bank where id_debitur = d.id_debitur)');
		$this->db->from('dokumen_asset as ta');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('status_asset as sa', 'sa.id_status_asset = ta.id_status_asset', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur', 'left');
		$this->db->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_potensial = tp.id_tr_potensial', 'left');
		$this->db->join('m_tindakan_hukum as mt', 'mt.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);

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

            $this->db->where("CAST(ta.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

        $b = 0;

        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $this->kolom_cari_proses;

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

            $kolom_order = $this->kolom_order_proses;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($this->order_proses)) {
            
            $order = $this->order_proses;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
	}

	public function jumlah_semua_proses($dt)
	{
		$this->db->select('d.id_care, d.no_klaim, d.nama_debitur, cap.capem_bank, asu.cabang_asuransi, d.besar_klaim, d.subrogasi_as, d.recoveries_awal_bank, d.recoveries_awal_asuransi, sa.status_asset, mt.tindakan_hukum, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur), (SELECT sum(nominal) as tot_nominal_bank FROM tr_recov_bank where id_debitur = d.id_debitur)');
		$this->db->from('dokumen_asset as ta');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('status_asset as sa', 'sa.id_status_asset = ta.id_status_asset', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur', 'left');
		$this->db->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_potensial = tp.id_tr_potensial', 'left');
		$this->db->join('m_tindakan_hukum as mt', 'mt.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);

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

            $this->db->where("CAST(ta.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		return $this->db->count_all_results();
	}

	public function jumlah_filter_proses($dt)
	{
		$this->_get_datatables_query_proses($dt);
        return $this->db->get()->num_rows();
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

	public function get_cari_data_r_proses($dt)
	{
		$this->db->select('d.id_care, d.no_klaim, d.nama_debitur, cap.capem_bank, asu.cabang_asuransi, d.besar_klaim, d.subrogasi_as, d.recoveries_awal_bank, d.recoveries_awal_asuransi, sa.status_asset, cab.cabang_bank, b.bank, d.*, mt.tindakan_hukum, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur), (SELECT sum(nominal) as tot_nominal_bank FROM tr_recov_bank where id_debitur = d.id_debitur)');
		$this->db->from('dokumen_asset as ta');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('status_asset as sa', 'sa.id_status_asset = ta.id_status_asset', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur', 'left');
		$this->db->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_potensial = tp.id_tr_potensial', 'left');
		$this->db->join('m_tindakan_hukum as mt', 'mt.id_tindakan_hukum = th.id_tindakan_hukum', 'left');

		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);
		
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
        if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

            $tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
            $tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

            $this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}
		

		return $this->db->get();
		
	}

	public function get_jml_r_proses($dt)
	{
		$this->db->select('mh.tindakan_hukum, count(mh.id_tindakan_hukum) as jumlah_tindakan');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('kunjungan as kj', 'kj.id_debitur = d.id_debitur', 'inner');
		$this->db->join('tr_potensial as tp', 'tp.id_kunjungan = kj.id_kunjungan', 'inner');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_potensial = tp.id_tr_potensial', 'inner');
		$this->db->join('m_tindakan_hukum as mh', 'mh.id_tindakan_hukum = th.id_tindakan_hukum', 'inner');
		
		$this->db->where('d.ots', 1);

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

		$this->db->group_by('mh.id_tindakan_hukum');

		return $this->db->get();
	}

}

/* End of file M_proses.php */
/* Location: ./application/models/M_proses.php */