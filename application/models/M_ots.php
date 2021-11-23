<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_ots extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->cabang_as = $this->session->userdata('cabang_as');
	}

	// 18-03-2020

		public function get_data($tabel)
		{
			return $this->db->get($tabel);
		}

		public function cari_data($tabel, $where)
		{
			return $this->db->get_where($tabel, $where);
		}

	// Akhir 18-03-2020

	// get data ots 
	public function get_data_ots($nama)
	{
		$this->_get_datatables_query_ots($nama);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
	}

	public function jumlah_semua_ots($nama)
	{
		$this->db->select("d.id_debitur,d.nama_debitur, d.no_klaim, cab.cabang_bank, cap.capem_bank, b.bank, asu.cabang_asuransi, asr.asuransi, d.potensial, d.alamat_awal, j.keterangan");
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
        $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
        $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
        $this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('alamat_debitur as ad', 'ad.id_debitur = d.id_debitur', 'left');
		$this->db->join('kunjungan as j', 'j.id_debitur = d.id_debitur', 'inner');

		// $this->db->where('d.ots', 1);

		if ($nama['level'] == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($nama['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $nama['asuransi']);
			}
		}

		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		if ($nama['level'] == 'asuransi') {
			// $this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
			
		} else {
			if ($nama['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $nama['cabang_asuransi']);
			}
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

            $this->db->where("CAST(j.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($nama['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $nama['verifikator']);
		}
		if ($nama['status'] != 'a') {
			$this->db->where('d.potensial', $nama['status']);
		}
		if ($nama['spk'] != 'a') {
			if ($nama['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $nama['spk']);
			}
		}

        return $this->db->count_all_results();
	}

	// jumlah data filter ots
    public function jumlah_filter_ots($nama)
    {
        $this->_get_datatables_query_ots($nama);
        return $this->db->get()->num_rows();
	}
	
	var $kolom_order_ots = [null, 'd.nama_debitur', 'd.no_klaim', 'b.bank', 'cab.cabang_bank', 'cap.capem_bank', 'd.alamat_awal', 'asu.cabang_asuransi', 'j.keterangan', 'CAST(d.potensial as VARCHAR)'];
	var $kolom_cari_ots  = ['LOWER(d.nama_debitur)', 'LOWER(d.no_klaim)', 'LOWER(b.bank)', 'LOWER(cab.cabang_banK)', 'LOWER(cap.capem_bank)', 'LOWER(d.alamat_awal)', 'LOWER(asu.cabang_asuransi)', 'LOWER(j.keterangan)', 'CAST(d.potensial as VARCHAR)'];

	var $kolom_order_ots_cb = [null, 'd.nama_debitur', 'd.no_klaim', 'b.bank', 'cab.cabang_bank', 'cap.capem_bank', 'd.alamat_awal', 'j.keterangan', 'CAST(d.potensial as VARCHAR)'];
	var $kolom_cari_ots_cb  = ['LOWER(d.nama_debitur)', 'LOWER(d.no_klaim)', 'LOWER(b.bank)', 'LOWER(cab.cabang_banK)', 'LOWER(cap.capem_bank)', 'LOWER(d.alamat_awal)', 'LOWER(j.keterangan)', 'CAST(d.potensial as VARCHAR)'];
	
    var $order_ots       = ['d.nama_debitur' => 'asc'];

    // query tampil data kelolaan
    public function _get_datatables_query_ots($nama)
    {
        $this->db->select("d.id_debitur,d.nama_debitur, d.no_klaim, cab.cabang_bank, cap.capem_bank, b.bank, asu.cabang_asuransi, asr.asuransi, d.potensial, d.alamat_awal, j.keterangan");
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
        $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
        $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
        $this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('alamat_debitur as ad', 'ad.id_debitur = d.id_debitur', 'left');
		$this->db->join('kunjungan as j', 'j.id_debitur = d.id_debitur', 'inner');
		
		// $this->db->where('d.ots', 1);
		
        $id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		if ($nama['level'] == "syariah") {
			$this->db->where('asr.id_asuransi', 2);
		} else {
			if ($nama['asuransi'] != 'a') {
				$this->db->where('asr.id_asuransi', $nama['asuransi']);
			}
		}

		if ($nama['level'] == 'asuransi') {
			// $this->db->where('asu.id_cabang_asuransi', $id_cabang_as);
			$this->db->where('d.id_spk', $id_spk);
		} else {
			if ($nama['cabang_asuransi'] != 'a') {
				$this->db->where('asu.id_cabang_asuransi', $nama['cabang_asuransi']);
			}
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

            $this->db->where("CAST(j.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($nama['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $nama['verifikator']);
		}
		if ($nama['status'] != 'a') {
			$this->db->where('d.potensial', $nama['status']);
		}
		if ($nama['spk'] != 'a') {
			if ($nama['spk'] == 'null') {
				$this->db->where("d.id_spk is null");
			} else {
				$this->db->where('d.id_spk', $nama['spk']);
			}
		}

		$b = 0;
		
		if ($this->cabang_as != '') {
			$co = $this->kolom_cari_ots_cb;
			$or = $this->kolom_order_ots_cb;
		} else {
			$co = $this->kolom_cari_ots;
			$or = $this->kolom_order_ots;
		}

        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $co;

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

            $kolom_order = $or;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($or)) {
            
            $order = $this->order_ots;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
        
    }

	// menampilkan verfikator
	public function get_verifikator_new()
	{
		$this->db->select('k.id_karyawan, k.nama_lengkap');
		$this->db->from('karyawan k');
		$this->db->join('penempatan p', 'p.id_karyawan = k.id_karyawan', 'inner');
		$this->db->group_by('k.id_karyawan')->group_by('k.nama_lengkap');
		
		return $this->db->get();
		
	}

	// get_shs_sudah
	public function get_shs_sudah()
	{
		$this->db->select('d.id_debitur, d.subrogasi, sum(r.nominal) as recoveries');
		$this->db->from('debitur as d');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'inner');

		$this->db->group_by('d.id_debitur');

		$hasil = $this->db->get()->result_array();
		$value = array();

		$tot_shs 	= 0;
		$tot_subro 	= 0;
		$tot_rec 	= 0;
		
		foreach ($hasil as $h) {
			$subrogasi 		 = $h['subrogasi'];
			$recoveries 	 = $h['recoveries'];

			$tot_shs += ($subrogasi-$recoveries);

			$tot_subro 	+= $subrogasi;

			$tot_rec += $recoveries;
		}

		$value = [
			'tot_shs_noa'	=> $tot_shs,
			'tot_subro'		=> $tot_subro,
			'tot_rec'		=> $tot_rec
			];

		return $value;
	}

	// get shs semua noa
	public function get_shs_noa()
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

		$this->db->group_by('d.id_debitur');
		$this->db->group_by('cab.id_cabang_bank');
		$this->db->group_by('ca.id_cabang_asuransi');
		$this->db->group_by('ms.id_status_deb');
		$this->db->group_by('p.id_proses');
		$this->db->order_by('d.nama_debitur', 'asc');

		$hasil = $this->db->get()->result_array();
		$value = array();

		$tot_shs 		= 0;
		$tot_subro 		= 0;
		$tot_subro_deb 	= 0;
		foreach ($hasil as $h) {
			$subrogasi 		 = $h['subrogasi'];
			$recoveries 	 = $h['recoveries'];
			$id_deb 		 = $h['id_debitur'];

			$this->db->select('sum(subrogasi) as total_subro');
			$this->db->from('debitur');
			$this->db->where('id_debitur', $id_deb);

			$av = $this->db->get()->row_array();

			$tot_subro_deb += $av['total_subro'];

			$tot_shs 	+= ($subrogasi-$recoveries);

			$tot_subro 	+= $subrogasi;
		}

		$value = [
			'tot_shs'		=> $tot_shs,
			'tot_subro'		=> $tot_subro,
			'tot_subro_deb'	=> $tot_subro_deb
			];

		return $value;
	}

	public function get_asuransi($id)
	{
		if (!empty($id)) {
			return $this->db->get_where('m_asuransi',array('id_asuransi' => $id));
		} else {
			return $this->db->get('m_asuransi');
		}
		
	}

	public function get_wilayah_capem()
	{
		return $this->db->get('m_cabang_bank');
	}

	public function get_verifikator()
	{
		$this->db->select('k.nama_lengkap, k.id_karyawan');
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'inner');
		$this->db->group_by('k.nama_lengkap');
		$this->db->group_by('k.id_karyawan');
		$this->db->order_by('k.nama_lengkap', 'asc');

		return $this->db->get();
	}

	public function get_data_r_ots()
	{
		$this->db->select('d.*,d.nama_debitur, d.no_klaim, cab.cabang_bank, b.bank, o.alamat as alamat_deb, o.keterangan, msd.status_deb');
		$this->db->from('ots as o');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as msd', 'msd.id_status_deb = osd.id_status_deb', 'left');
		$this->db->where('o.status', 1);

		return $this->db->get();
	}

	public function get_agunan_ots($id_debitur)
	{
		$this->db->select('ja.jenis_asset');
		$this->db->from('dokumen_asset as da');
		$this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = da.id_jenis_asset', 'inner');
		$this->db->where('da.id_debitur', $id_debitur);
		
		return $this->db->get();
		
	}

	public function get_fu_ots($id_debitur)
	{
		$this->db->select('fu');
		$this->db->from('tr_fu');
		$this->db->where('id_debitur', $id_debitur);
		$this->db->where('status_fu', 1);
		
		return $this->db->get();
		
	}

	public function get_stp_ots($id_kunjungan, $aksi)
	{

		if ($aksi == 'st_proses') {
			$this->db->select('s.status_proses');
			$this->db->from('tr_potensial as t');

			$this->db->join('tr_tindakan_proses as tp', 'tp.id_tr_potensial = t.id_tr_potensial', 'inner');
			$this->db->join('status_proses as s', 's.id_status_proses = tp.id_proses', 'inner');
		} else {
			$this->db->select('m.tindakan_hukum');
			$this->db->from('tr_potensial as t');

			$this->db->join('tr_tindakan_hukum as h', 'h.id_tr_potensial = t.id_tr_potensial', 'inner');
			$this->db->join('m_tindakan_hukum as m', 'm.id_tindakan_hukum = h.id_tindakan_hukum', 'inner');
		}

		$this->db->where('t.id_kunjungan', $id_kunjungan);
		
		return $this->db->get();
		
	}

	// fungsi untuk lihat pdf dan unduh pdf
	public function get_cari_data_unduh_r_ots($dt)
	{
		$id_cabang_as = $this->session->userdata('cabang_as');
		$id_spk   	  = $this->session->userdata('id_spk');

		// (SELECT pic, status_hubungan, alamat, telp_pic, keterangan, add_time as tgl_ots FROM kunjungan where id_debitur=d.id_debitur order by add_time DESC LIMIT 1)

		// $this->db->select('d.no_klaim, d.no_reff, d.nama_debitur, b.bank, cab.cabang_bank, asu.cabang_asuransi, (SELECT pic FROM kunjungan where id_debitur=d.id_debitur order by add_time DESC LIMIT 1),(SELECT alamat FROM kunjungan where id_debitur=d.id_debitur order by add_time DESC LIMIT 1),(SELECT telp_pic FROM kunjungan where id_debitur=d.id_debitur order by add_time DESC LIMIT 1),(SELECT keterangan FROM kunjungan where id_debitur=d.id_debitur order by add_time DESC LIMIT 1),(SELECT add_time as tgl_ots FROM kunjungan where id_debitur=d.id_debitur order by add_time DESC LIMIT 1), mh.tindakan_hukum as proses, f.fu, stp.status_proses as potensi, ja.jenis_asset, d.potensial');
		// $this->db->from('debitur as d');
		// $this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		// $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
		// $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
		// $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','inner');
		// $this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','inner');
		// $this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
		// $this->db->join('penempatan as p', 'p.id_capem_bank = d.id_capem_bank', 'left');
		// $this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		// $this->db->join('kunjungan as j', 'j.id_debitur = d.id_debitur', 'inner');
		// $this->db->join('tr_potensial as tp', 'tp.id_kunjungan = j.id_kunjungan', 'left');
		// $this->db->join('tr_fu as f', 'f.id_tr_potensial = tp.id_tr_potensial', 'left');
		// $this->db->join('m_proses_fu as pf', 'pf.id_proses_fu = f.id_proses', 'left');
		// $this->db->join('tr_tindakan_hukum as th', 'th.id_tr_potensial = tp.id_tr_potensial', 'left');
		// $this->db->join('m_tindakan_hukum as mh', 'mh.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		// $this->db->join('dokumen_asset as st', 'st.id_debitur = d.id_debitur', 'left');
		// $this->db->join('m_jenis_asset as ja', 'ja.id_jenis_asset = st.id_jenis_asset', 'left');
		// $this->db->join('tr_tindakan_proses as tps', 'tps.id_tr_potensial = tp.id_tr_potensial', 'left');
		// $this->db->join('status_proses as stp', 'stp.id_status_proses = tps.id_proses', 'left');
		
		// $this->db->where('st.id_jenis_dok', 2);
		// $this->db->where('st.status', 1);
		
		// $this->db->where('f.status_fu', 1);
		
		// $this->db->where('d.ots', 1);

		$this->db->select("d.id_debitur,j.id_kunjungan, d.nama_debitur, d.no_reff, d.no_klaim, cab.cabang_bank, cap.capem_bank, b.bank, asu.cabang_asuransi, asr.asuransi, d.potensial, j.keterangan, j.add_time, j.alamat, j.telp_pic, j.pic, (SELECT sum(subrogasi_as) as tot_subro FROM debitur where d.id_debitur = id_debitur), (SELECT sum(recoveries_awal_asuransi) as tot_recov_awal FROM debitur where d.id_debitur = id_debitur), (SELECT sum(nominal) as tot_nominal_as FROM tr_recov_as where d.id_debitur = id_debitur)");
		$this->db->from('debitur as d');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'inner');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
        $this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'inner');
        $this->db->join('m_cabang_asuransi as asu','asu.id_cabang_asuransi = d.id_cabang_as','INNER');
		$this->db->join('m_korwil_asuransi as kor','kor.id_korwil_asuransi = asu.id_korwil_asuransi','INNER');
		$this->db->join('m_asuransi as asr', 'asr.id_asuransi = kor.id_asuransi', 'inner');
        $this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'inner');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
		$this->db->join('alamat_debitur as ad', 'ad.id_debitur = d.id_debitur', 'left');
		$this->db->join('kunjungan as j', 'j.id_debitur = d.id_debitur', 'inner');
		$this->db->order_by('j.add_time', 'desc');

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

            $this->db->where("CAST(d.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
		}
		if ($dt['verifikator'] != 'a') {
			$this->db->where('k.id_karyawan', $dt['verifikator']);
			
		}

		return $this->db->get();
	}

	public function get_cari_data_r_ots($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank, $asuransi)
	{
		$this->db->select('d.nama_debitur, d.no_klaim, b.bank, sp.status_proses as potensi, mh.tindakan_hukum as proses, cab.cabang_bank, o.keterangan,o.narasumber,o.add_time,o.alamat as alamat_deb, msd.status_deb, ca.cabang_asuransi, o.telp, a.asuransi');
		$this->db->from('ots as o');
		$this->db->join('tr_ots_p as op', 'op.id_ots = o.id_ots', 'left');
		$this->db->join('status_proses as sp', 'sp.id_status_proses = op.status_proses', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_ots_p = op.id_tr_ots_p', 'left');
		$this->db->join('m_tindakan_hukum as mh', 'mh.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('m_status_debitur as msd', 'msd.id_status_deb = osd.id_status_deb', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($wilayah) || !empty($ver) || !empty($bank) || !empty($asuransi)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($wilayah) && empty($ver)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (!empty($tgl_akhir) && empty($tgl_awal) && empty($wilayah) && empty($ver)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($wilayah)) {
				$this->db->where('cab.cabang_bank', $wilayah);
			}
			if (!empty($ver)) {
				$this->db->where('k.nama_lengkap', $ver);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($asuransi)) {
				$this->db->where('a.id_asuransi', $asuransi);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($ver)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		return $this->db->get(); 	
	}

	public function get_peringkat_ver($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank, $asuransi)
	{
		$select = [
			'distinct (k.id_karyawan)',
		    'k.nama_lengkap',
		    'sum(r.nominal) as total_recoveries'
		];

		$this->db->select($select);
		$this->db->from('karyawan as k');
		$this->db->join('penempatan as p', 'p.id_karyawan = k.id_karyawan', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('debitur as d', 'd.id_capem_bank = p.id_capem_bank', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');
		$this->db->join('recoveries as r', 'r.id_debitur = d.id_debitur', 'left');
		$this->db->join('ots as o', 'o.id_debitur = d.id_debitur', 'left');
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($wilayah) || !empty($ver) || !empty($bank) || !empty($asuransi)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($wilayah) && empty($ver)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (!empty($tgl_akhir) && empty($tgl_awal) && empty($wilayah) && empty($ver)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($wilayah)) {
				$this->db->where('cab.cabang_bank', $wilayah);
			}
			if (!empty($ver)) {
				$this->db->where('k.nama_lengkap', $ver);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($asuransi)) {
				$this->db->where('a.id_asuransi', $asuransi);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($ver)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		$this->db->order_by('total_recoveries', 'desc');
		$this->db->group_by('k.id_karyawan');
		$this->db->limit(10);

		return $this->db->get();
	}

	public function get_total($tabel)
	{
		if ($tabel == 'ots') { 
			$this->db->distinct();
			$this->db->select("$tabel.id_debitur");
			$this->db->from($tabel);
			$this->db->join('debitur as d', "d.id_debitur = $tabel.id_debitur", 'left');
			$this->db->where('status', 1);
		} else {
			$this->db->distinct();
			$this->db->select('id_debitur');
			$this->db->from($tabel);
		}
		
		return $this->db->count_all_results();
	}

	public function get_total_ots($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank, $asuransi)
	{
		$this->db->select('d.id_debitur, d.nama_debitur, b.bank, sp.status_proses as potensi,  mh.tindakan_hukum as proses, cab.cabang_bank, o.keterangan,o.narasumber,o.add_time,o.alamat as alamat_deb,ca.cabang_asuransi, o.telp, a.asuransi');
		$this->db->from('ots as o');
		$this->db->join('tr_ots_p as op', 'op.id_ots = o.id_ots', 'left');
		$this->db->join('status_proses as sp', 'sp.id_status_proses = op.status_proses', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_ots_p = op.id_tr_ots_p', 'left');
		$this->db->join('m_tindakan_hukum as mh', 'mh.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');
		$this->db->where('o.status', 1);

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($wilayah) || !empty($ver) || !empty($bank) || !empty($asuransi)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (!empty($tgl_akhir) && empty($tgl_awal) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($wilayah)) {
				$this->db->where('cab.cabang_bank', $wilayah);
			}
			if (!empty($ver)) {
				$this->db->where('k.nama_lengkap', $ver);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($asuransi)) {
				$this->db->where('a.id_asuransi', $asuransi);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		return $this->db->count_all_results();
	}

	public function get_total_noa($tgl_awal, $tgl_akhir, $wilayah, $ver, $bank, $asuransi)
	{
		$this->db->select('d.id_debitur');
		$this->db->from('ots as o');
		$this->db->join('tr_ots_p as op', 'op.id_ots = o.id_ots', 'left');
		$this->db->join('status_proses as sp', 'sp.id_status_proses = op.status_proses', 'left');
		$this->db->join('tr_tindakan_hukum as th', 'th.id_tr_ots_p = op.id_tr_ots_p', 'left');
		$this->db->join('m_tindakan_hukum as mh', 'mh.id_tindakan_hukum = th.id_tindakan_hukum', 'left');
		$this->db->join('debitur as d', 'd.id_debitur = o.id_debitur', 'left');
		$this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = d.id_cabang_as', 'left');
		$this->db->join('m_korwil_asuransi as ka', 'ka.id_korwil_asuransi = ca.id_korwil_asuransi', 'left');
		$this->db->join('m_asuransi as a', 'a.id_asuransi = ka.id_asuransi', 'left');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = d.id_capem_bank', 'left');
		$this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'left');
		$this->db->join('m_bank as b', 'b.id_bank = cab.id_bank', 'left');
		$this->db->join('ots_status_debitur as osd', 'osd.id_ots = o.id_ots', 'left');
		$this->db->join('penempatan as p', 'p.id_capem_bank = cap.id_capem_bank', 'left');
		$this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'left');

		if (!empty($tgl_awal) || !empty($tgl_akhir) || !empty($wilayah) || !empty($ver) || !empty($bank) || !empty($asuransi)) {
			if (!empty($tgl_awal) && empty($tgl_akhir) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_awal%' ");
			}
			if (!empty($tgl_akhir) && empty($tgl_awal) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) like '%$tgl_akhir%' ");
			}
			if (!empty($wilayah)) {
				$this->db->where('cab.cabang_bank', $wilayah);
			}
			if (!empty($ver)) {
				$this->db->where('k.nama_lengkap', $ver);
			}
			if (!empty($bank)) {
				$this->db->where('b.bank', $bank);
			}
			if (!empty($asuransi)) {
				$this->db->where('a.id_asuransi', $asuransi);
			}
			if (!empty($tgl_awal) && !empty($tgl_akhir) && empty($wilayah) && empty($ver) && empty($bank) && empty($asuransi)) {
				$this->db->where("CAST(o.add_time AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1' ");
			}
		}

		$this->db->group_by('d.id_debitur');

		return $this->db->count_all_results();
	}

}

/* End of file M_ots.php */
/* Location: ./application/models/M_ots.php */