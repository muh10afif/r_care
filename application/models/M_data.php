<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class M_data extends CI_Model {

    public function get_jns_dok($id_debitur)
    {
        $this->db->from('dokumen_asset as d');
        $this->db->join('m_jenis_dokumen as j', 'j.id_jenis_dok = d.id_jenis_dok', 'left');
        $this->db->where('d.id_debitur', $id_debitur);
        $this->db->where('d.status', 1);
        $this->db->order_by('j.id_jenis_dok', 'asc');
        
        return $this->db->get();
        
    }

    public function get_foto_deb($id_debitur, $id_dok_asset)
    {
        $this->db->from('tr_foto_dokumen as f');
        $this->db->join('tampak_asset as t', 't.id_tampak_asset = f.id_tampak_asset', 'left');
        $this->db->where('f.id_debitur', $id_debitur);
        $this->db->where('f.id_dokumen_asset', $id_dok_asset);
                
        return $this->db->get();
        
    }

    public function get_data_deb($id_debitur)
    {
        $this->db->select('d.id_debitur, d.nama_debitur, d.id_care, d.no_klaim, cab.cabang_bank, cap.capem_bank, asu.cabang_asuransi, d.besar_klaim, d.subrogasi_as, d.recoveries_awal_bank, d.recoveries_awal_asuransi, ts.status_asset, me.total_harga, me.alamat, t.alamat_deb, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur), (SELECT sum(nominal) as tot_nominal_bank FROM tr_recov_bank where id_debitur = d.id_debitur)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
        $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
        $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
        $this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
        $this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
        $this->db->join('dokumen_asset as me', 'me.id_debitur = d.id_debitur', 'inner');
        $this->db->join('status_asset as ts', 'ts.id_status_asset = me.id_status_asset', 'left');
        $this->db->join('alamat_debitur as t', 't.id_debitur = d.id_debitur', 'left');
        $this->db->where('me.status', 1);

        $this->db->where('d.id_debitur', $id_debitur);
    
        return $this->db->get();
    
    }

    // DATATABLES
    public function get_list_deb($data)
    {
        $this->_get_datatables_query_list_deb($data);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
    }

    var $kolom_order_ft = [null, 'd.nama_debitur', 'd.no_klaim', 'cap.capem_bank', 't.alamat_deb'];
    var $kolom_cari_ft  = ['LOWER(d.nama_debitur)', 'LOWER(d.no_klaim)', 'LOWER(cap.capem_bank)', 'LOWER(cab.cabang_asuransi)', 'LOWER(me.alamat)', 'LOWER(t.alamat_deb)', 'CAST(me.total_harga as VARCHAR)', 'LOWER(ts.status_asset)'];
    var $order_ft       = ['d.id_debitur' => 'desc'];

    public function _get_datatables_query_list_deb($nama)
    {
        $this->db->select('d.id_debitur, d.nama_debitur, d.id_care, d.no_klaim, cab.cabang_bank, cap.capem_bank, asu.cabang_asuransi, d.besar_klaim, d.subrogasi_as, d.recoveries_awal_bank, d.recoveries_awal_asuransi, ts.status_asset, me.total_harga, me.alamat, t.alamat_deb, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur), (SELECT sum(nominal) as tot_nominal_bank FROM tr_recov_bank where id_debitur = d.id_debitur)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
        $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
        $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
        $this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
        $this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
        $this->db->join('dokumen_asset as me', 'me.id_debitur = d.id_debitur', 'inner');
        $this->db->join('status_asset as ts', 'ts.id_status_asset = me.id_status_asset', 'left');
        $this->db->join('alamat_debitur as t', 't.id_debitur = d.id_debitur', 'left');
        $this->db->where('me.status', 1);

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

            $tgl_awal   = $nama['tanggal_awal']; 
            $tgl_akhir  = $nama['tanggal_akhir'];

            $this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($nama['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $nama['verifikator']);
			
		}

        $this->db->order_by('d.id_debitur', 'asc');
        
        $b = 0;

        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $this->kolom_cari_ft;

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

            $kolom_order = $this->kolom_order_ft;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($this->order_ft)) {
            
            $order = $this->order_ft;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
        
    }

    public function jumlah_semua_deb($nama)
    {
        $this->db->select('d.id_debitur, d.nama_debitur, d.id_care, d.no_klaim, cab.cabang_bank, cap.capem_bank, asu.cabang_asuransi, d.besar_klaim, d.subrogasi_as, d.recoveries_awal_bank, d.recoveries_awal_asuransi, ts.status_asset, me.total_harga, me.alamat, t.alamat_deb, (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where id_debitur = d.id_debitur), (SELECT sum(nominal) as tot_nominal_bank FROM tr_recov_bank where id_debitur = d.id_debitur)');
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
        $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
        $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
        $this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
        $this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
        $this->db->join('dokumen_asset as me', 'me.id_debitur = d.id_debitur', 'inner');
        $this->db->join('status_asset as ts', 'ts.id_status_asset = me.id_status_asset', 'left');
        $this->db->join('alamat_debitur as t', 't.id_debitur = d.id_debitur', 'left');
        $this->db->where('me.status', 1);

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

            $tgl_awal   = $nama['tanggal_awal']; 
            $tgl_akhir  = $nama['tanggal_akhir'];

            $this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($nama['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $nama['verifikator']);
			
		}

        $this->db->order_by('d.id_debitur', 'asc');
        
        return $this->db->count_all_results();
    }

    public function jumlah_filter_deb($nama)
    {
        $this->_get_datatables_query_list_deb($nama);
        return $this->db->get()->num_rows();
    }

    // cari jenis dokumen agunan
    public function cari_jns_dok($where)
    {
        $this->db->join('m_jenis_dokumen as j', 'j.id_jenis_dok = d.id_jenis_dok', 'inner');
        
        return $this->db->get_where('dokumen_asset as d', $where);
        
    }

    // cek id_debitur pada tr_foto_dokumen
    public function cek_id_deb($id_deb)
    {
        return $this->db->get_where('tr_foto_dokumen', array('id_debitur' => $id_deb));        
    }

    public function get_data_verifikator()
    {
        $this->db->select('k.nama_lengkap, k.telfon, k.alamat, k.id_karyawan');
        $this->db->from('karyawan as k');
        $this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'inner');
        $this->db->group_by('k.nama_lengkap');
        $this->db->group_by('k.telfon');
        $this->db->group_by('k.alamat');
        $this->db->group_by('k.id_karyawan');
        $this->db->order_by('k.add_time', 'asc');
        
        return $this->db->get();
        
    }

    public function get_data_debitur_ver($id_ver)
    {
        $this->db->select('d.nama_debitur, d.no_klaim, d.id_debitur');
        $this->db->from('karyawan as k');
        $this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'inner');
        $this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'inner');
        $this->db->where('k.id_karyawan', $id_ver);
        $this->db->group_by('d.id_debitur');
        $this->db->order_by('d.add_time', 'desc');
        
        return $this->db->get();
        
    }

    public function cari_data($tabel, $where)
    {
        return $this->db->get_where($tabel, $where);
        
    }

    public function get_data_foto_deb($id_deb)
    {
        $this->db->from('tr_foto_dokumen as f');
        $this->db->join('tampak_asset as t', 't.id_tampak_asset = f.id_tampak_asset', 'left');
        
        $this->db->where('f.id_debitur', $id_deb);
        $this->db->order_by('f.add_time', 'desc');
        
        return $this->db->get();
        
    }

}

/* End of file M_data.php */
