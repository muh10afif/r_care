<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_eks_asset extends CI_Model {

	public function cari_data($tabel, $where)
	{
		return $this->db->get_where($tabel, $where);
	}

	public function get_jenis_asset()
	{
		return $this->db->get('m_jenis_asset');
	}

	public function get_cari_data_wilayah_ver($nama)
	{	
		$this->db->select('d.id_care, d.no_klaim, d.nama_debitur, cap.capem_bank, asu.cabang_asuransi, sa.status_asset, k.nama_lengkap as verifikator');
		$this->db->from('dokumen_asset as ta');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = ta.id_jenis_asset', 'inner');
		$this->db->join('status_asset as sa', 'sa.id_status_asset = ta.id_status_asset', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = ta.id_karyawan', 'inner');
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);

		if ($nama['spk'] != 'a') {
			if ($nama['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $nama['spk']);
			}
		}

		if ($nama['asuransi'] != 'a') {
            $this->db->where('asr.id_asuransi', $nama['asuransi']);
        }
        if ($nama['cabang_asuransi'] != 'a') {
            $this->db->where('asu.id_cabang_asuransi', $nama['cabang_asuransi']);
        }
        if ($nama['bank'] != 'a') {
            $this->db->where('b.id_bank', $nama['bank']);
        }
        if ($nama['cabang_bank'] != 'a') {
            $this->db->where('cab.id_cabang_bank', $nama['cabang_bank']);
        }
        if ($nama['capem_bank'] != 'a') {
            $this->db->where('cap.id_capem_bank', $nama['capem_bank']);
        }
        if ($nama['tanggal_awal'] != '' && $nama['tanggal_akhir'] != '') {

            $tgl_awal   = nice_date($nama['tanggal_awal'], 'Y-m-d'); 
            $tgl_akhir  = nice_date($nama['tanggal_akhir'], 'Y-m-d');

            $this->db->where("CAST(ta.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($nama['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $nama['verifikator']);
			
		}

		$hasil = $this->db->get()->num_rows();

		$tot_jumlah_asset = $hasil;
		// foreach ($hasil as $h) {
		// 	$tot_jumlah_asset = $h['tot_jumlah_asset_all'];
		// }

		$this->db->select('d.id_care, d.no_klaim, d.nama_debitur, cap.capem_bank, asu.cabang_asuransi, sa.status_asset, k.nama_lengkap as verifikator, ta.total_harga');
		$this->db->from('dokumen_asset as ta');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = ta.id_jenis_asset', 'inner');
		$this->db->join('status_asset as sa', 'sa.id_status_asset = ta.id_status_asset', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = ta.id_karyawan', 'inner');
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);

		if ($nama['spk'] != 'a') {
			if ($nama['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $nama['spk']);
			}
		}

		if ($nama['asuransi'] != 'a') {
            $this->db->where('asr.id_asuransi', $nama['asuransi']);
        }
        if ($nama['cabang_asuransi'] != 'a') {
            $this->db->where('asu.id_cabang_asuransi', $nama['cabang_asuransi']);
        }
        if ($nama['bank'] != 'a') {
            $this->db->where('b.id_bank', $nama['bank']);
        }
        if ($nama['cabang_bank'] != 'a') {
            $this->db->where('cab.id_cabang_bank', $nama['cabang_bank']);
        }
        if ($nama['capem_bank'] != 'a') {
            $this->db->where('cap.id_capem_bank', $nama['capem_bank']);
        }
        if ($nama['tanggal_awal'] != '' && $nama['tanggal_akhir'] != '') {

            $tgl_awal   = nice_date($nama['tanggal_awal'], 'Y-m-d'); 
            $tgl_akhir  = nice_date($nama['tanggal_akhir'], 'Y-m-d');

            $this->db->where("CAST(ta.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($nama['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $nama['verifikator']);
			
		}

		$this->db->where('sa.id_status_asset', 7);

		$hasil = $this->db->get();

		$tot_jumlah_asset_sdh = $hasil->num_rows();
		$tot_hasil_penjualan  = 0;
		foreach ($hasil->result_array() as $h) {
			// $tot_jumlah_asset_sdh = $h['tot_jumlah_asset'];
			$tot_hasil_penjualan  += $h['total_harga'];
		}

		$this->db->select('d.id_care, d.no_klaim, d.nama_debitur, cap.capem_bank, asu.cabang_asuransi, sa.status_asset, k.nama_lengkap as verifikator, ta.total_harga');
		$this->db->from('dokumen_asset as ta');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = ta.id_jenis_asset', 'inner');
		$this->db->join('status_asset as sa', 'sa.id_status_asset = ta.id_status_asset', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = ta.id_karyawan', 'inner');
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);

		if ($nama['spk'] != 'a') {
			if ($nama['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $nama['spk']);
			}
		}

		if ($nama['asuransi'] != 'a') {
            $this->db->where('asr.id_asuransi', $nama['asuransi']);
        }
        if ($nama['cabang_asuransi'] != 'a') {
            $this->db->where('asu.id_cabang_asuransi', $nama['cabang_asuransi']);
        }
        if ($nama['bank'] != 'a') {
            $this->db->where('b.id_bank', $nama['bank']);
        }
        if ($nama['cabang_bank'] != 'a') {
            $this->db->where('cab.id_cabang_bank', $nama['cabang_bank']);
        }
        if ($nama['capem_bank'] != 'a') {
            $this->db->where('cap.id_capem_bank', $nama['capem_bank']);
        }
        if ($nama['tanggal_awal'] != '' && $nama['tanggal_akhir'] != '') {

            $tgl_awal   = nice_date($nama['tanggal_awal'], 'Y-m-d'); 
            $tgl_akhir  = nice_date($nama['tanggal_akhir'], 'Y-m-d');

            $this->db->where("CAST(ta.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($nama['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $nama['verifikator']);
			
		}	

		$this->db->where('sa.id_status_asset', 6);

		$hasil = $this->db->get();

		$tot_jumlah_asset_blm 	 = $hasil->num_rows();
		$tot_hasil_penjualan_pot = 0;
		foreach ($hasil->result_array() as $h) {
			// $tot_jumlah_asset_blm     = $h['tot_jumlah_asset'];
			$tot_hasil_penjualan_pot  += $h['total_harga'];
		}

		$value[] = [
			'tot_jumlah_asset'			=> $tot_jumlah_asset,
			'tot_hasil_penjualan'		=> $tot_hasil_penjualan,
			'tot_jumlah_asset_sdh'		=> $tot_jumlah_asset_sdh,
			'tot_hasil_penjualan_pot'	=> $tot_hasil_penjualan_pot
		];

		return $value;

	}

	public function get_data_eks_asset($dt)
	{
		$this->_get_datatables_query_eks_asset($dt);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
	}

	var $kolom_order_eks_asset = [null, 'd.id_care', 'd.no_klaim', ' d.nama_debitur', 'cap.capem_bank', 'asu.cabang_asuransi', 'sa.status_asset', 'k.nama_lengkap'];
    var $kolom_cari_eks_asset  = ['CAST(d.id_care as VARCHAR)', 'LOWER(d.no_klaim)', 'LOWER(d.nama_debitur)', 'LOWER(cap.capem_bank)', 'LOWER(asu.cabang_asuransi)', 'LOWER(sa.status_asset)', 'LOWER(k.nama_lengkap)'];
    var $order_eks_asset       = ['d.id_debitur' => 'asc'];

	public function _get_datatables_query_eks_asset($nama)
	{
		$this->db->select('d.id_care, d.no_klaim, d.nama_debitur, cap.capem_bank, asu.cabang_asuransi, sa.status_asset, k.nama_lengkap as verifikator');
		$this->db->from('dokumen_asset as ta');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = ta.id_jenis_asset', 'inner');
		$this->db->join('status_asset as sa', 'sa.id_status_asset = ta.id_status_asset', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = ta.id_karyawan', 'inner');
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);

		if ($nama['spk'] != 'a') {
			if ($nama['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $nama['spk']);
			}
		}

		if ($nama['asuransi'] != 'a') {
            $this->db->where('asr.id_asuransi', $nama['asuransi']);
        }
        if ($nama['cabang_asuransi'] != 'a') {
            $this->db->where('asu.id_cabang_asuransi', $nama['cabang_asuransi']);
        }
        if ($nama['bank'] != 'a') {
            $this->db->where('b.id_bank', $nama['bank']);
        }
        if ($nama['cabang_bank'] != 'a') {
            $this->db->where('cab.id_cabang_bank', $nama['cabang_bank']);
        }
        if ($nama['capem_bank'] != 'a') {
            $this->db->where('cap.id_capem_bank', $nama['capem_bank']);
        }
        if ($nama['tanggal_awal'] != '' && $nama['tanggal_akhir'] != '') {

            $tgl_awal   = nice_date($nama['tanggal_awal'], 'Y-m-d'); 
            $tgl_akhir  = nice_date($nama['tanggal_akhir'], 'Y-m-d');

            $this->db->where("CAST(ta.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($nama['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $nama['verifikator']);
			
		}

        $b = 0;

        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $this->kolom_cari_eks_asset;

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

            $kolom_order = $this->kolom_order_eks_asset;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($this->order_eks_asset)) {
            
            $order = $this->order_eks_asset;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
	}

	public function jumlah_semua_eks_asset($nama)
	{
		$this->db->select('d.id_care, d.no_klaim, d.nama_debitur, cap.capem_bank, asu.cabang_asuransi, sa.status_asset, k.nama_lengkap as verifikator');
		$this->db->from('dokumen_asset as ta');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = ta.id_jenis_asset', 'inner');
		$this->db->join('status_asset as sa', 'sa.id_status_asset = ta.id_status_asset', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = ta.id_karyawan', 'inner');
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);

		if ($nama['spk'] != 'a') {
			if ($nama['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $nama['spk']);
			}
		}

		if ($nama['asuransi'] != 'a') {
            $this->db->where('asr.id_asuransi', $nama['asuransi']);
        }
        if ($nama['cabang_asuransi'] != 'a') {
            $this->db->where('asu.id_cabang_asuransi', $nama['cabang_asuransi']);
        }
        if ($nama['bank'] != 'a') {
            $this->db->where('b.id_bank', $nama['bank']);
        }
        if ($nama['cabang_bank'] != 'a') {
            $this->db->where('cab.id_cabang_bank', $nama['cabang_bank']);
        }
        if ($nama['capem_bank'] != 'a') {
            $this->db->where('cap.id_capem_bank', $nama['capem_bank']);
        }
        if ($nama['tanggal_awal'] != '' && $nama['tanggal_akhir'] != '') {

            $tgl_awal   = nice_date($nama['tanggal_awal'], 'Y-m-d'); 
            $tgl_akhir  = nice_date($nama['tanggal_akhir'], 'Y-m-d');

            $this->db->where("CAST(ta.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($nama['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $nama['verifikator']);
		}

		return $this->db->count_all_results();
	}

	public function jumlah_filter_eks_asset($dt)
	{
		$this->_get_datatables_query_eks_asset($dt);
        return $this->db->get()->num_rows();
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

	public function get_cari_data_r_eks_asset($nama)
	{
		$this->db->select('asr.asuransi, cab.cabang_bank, asu.cabang_asuransi, b.bank, d.no_reff, d.nama_debitur, d.no_klaim, ja.jenis_asset, s.sifat_asset, ta.total_harga as harga, sa.status_asset');
		$this->db->from('dokumen_asset as ta');
		$this->db->join('debitur as d', 'd.id_debitur = ta.id_debitur', 'inner');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		$this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = ta.id_jenis_asset', 'inner');
		$this->db->join('status_asset as sa', 'sa.id_status_asset = ta.id_status_asset', 'inner');
		$this->db->join('m_sifat_asset as s', 's.id_sifat_asset = ta.id_sifat_asset', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = ta.id_karyawan', 'inner');
		$this->db->where('ta.id_jenis_dok', 2);
		$this->db->where('ta.status', 1);

		if ($nama['spk'] != 'a') {
			if ($nama['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $nama['spk']);
			}
		}

		if ($nama['asuransi'] != 'a') {
            $this->db->where('asr.id_asuransi', $nama['asuransi']);
        }
        if ($nama['cabang_asuransi'] != 'a') {
            $this->db->where('asu.id_cabang_asuransi', $nama['cabang_asuransi']);
        }
        if ($nama['bank'] != 'a') {
            $this->db->where('b.id_bank', $nama['bank']);
        }
        if ($nama['cabang_bank'] != 'a') {
            $this->db->where('cab.id_cabang_bank', $nama['cabang_bank']);
        }
        if ($nama['capem_bank'] != 'a') {
            $this->db->where('cap.id_capem_bank', $nama['capem_bank']);
        }
        if ($nama['tanggal_awal'] != '' && $nama['tanggal_akhir'] != '') {

            $tgl_awal   = nice_date($nama['tanggal_awal'], 'Y-m-d'); 
            $tgl_akhir  = nice_date($nama['tanggal_akhir'], 'Y-m-d');

            $this->db->where("CAST(ta.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($nama['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $nama['verifikator']);
		}

		return $this->db->get();
	}

}

/* End of file M_eks_asset.php */
/* Location: ./application/models/M_eks_asset.php */