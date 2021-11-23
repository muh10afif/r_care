<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_laporan extends CI_Model {

    
    public function __construct()
    {
        parent::__construct();
        $this->db_2 = $this->load->database('database_hrd', TRUE);
    }

    /***********************************************************************/
    /*
    /*                   LAPORAN KEUANGAN 1 & 2
    /*
    /**********************************************************************/

    public function get_data_detail_jurnal_coa($a, $k)
    {
        $this->db_2->select('c.deskripsi_coa, c.no_coa_des');
        $this->db_2->from('detail_jurnal as d');
        $this->db_2->join('anggota as a', 'CAST(a.id_anggota as VARCHAR) = CAST(d.pelaksana as VARCHAR)', 'inner');
        $this->db_2->join('posisi as p', 'p.id_posisi = a.id_posisi', 'inner');
        $this->db_2->join('bagian as b','b.id_bagian = p.id_bagian', 'inner');
        $this->db_2->join('des_coa as c', 'c.no_coa_des = d.coa', 'inner');
        $this->db_2->where('b.id_bagian', 4);
        $this->db_2->where('d.id_group', 8);
        
        if ($a != '' && $k != '') {

            $bulan_awal  = nice_date($a, 'Y-m');
            $bulan_akhir = nice_date($k, 'Y-m');

            $this->db_2->where("to_char(d.tgl_transaksi, 'YYYY-MM') BETWEEN '$bulan_awal' AND '$bulan_akhir'");
        }

        $this->db_2->group_by('c.no_coa_des');
        $this->db_2->group_by('c.deskripsi_coa');

        return $this->db_2->get();
    }   

    public function get_cetak_coa($bulan, $no_coa)
    {
        $this->db_2->select('sum(debit) as total');
        $this->db_2->from('detail_jurnal as d');
        $this->db_2->join('anggota as a', 'CAST(a.id_anggota as VARCHAR) = CAST(d.pelaksana as VARCHAR)', 'inner');
        $this->db_2->join('posisi as p', 'p.id_posisi = a.id_posisi', 'inner');
        $this->db_2->join('bagian as b','b.id_bagian = p.id_bagian', 'inner');
        $this->db_2->join('des_coa as c', 'c.no_coa_des = d.coa', 'inner');
        $this->db_2->where('b.id_bagian', 4);
        $this->db_2->where('d.id_group', 8);

        $this->db_2->where("CAST(d.tgl_transaksi as VARCHAR) LIKE '%$bulan%'");

        if ($no_coa != '') {
            $this->db_2->where('d.coa', $no_coa);
        }
        
        return $this->db_2->get();
    }

    public function get_pendapatan_invoice($bulan)
    {
        $this->db->select('sum(komisi_diajukan) as total');
        $this->db->from('invoice');
        $this->db->where("to_char(add_time, 'YYYY-MM') LIKE '%$bulan%'");
        
        return $this->db->get();
    }
    
    public function get_pendapatan_invoice_dibayarkan($bulan)
    {
        $this->db->select('sum(komisi_dibayarkan) as total');
        $this->db->from('invoice');
        $this->db->where("to_char(tgl_cair, 'YYYY-MM') LIKE '%$bulan%'");
        
        return $this->db->get();
    }

    public function get_recoveries($bulan)
    {
        $this->db->select('sum(nominal) as total');
        $this->db->from('tr_recov_as');
        $this->db->where("to_char(tgl_bayar, 'YYYY-MM') LIKE '%$bulan%'");
        
        return $this->db->get();
    }

    /***********************************************************************/
    /*
    /*                          LAPORAN KEUANGAN 3
    /*
    /**********************************************************************/

    public function get_data_potensi_komisi($a, $k)
    {
        // SELECT DISTINCT ca.cabang_asuransi, mp.nama_periode
        // FROM rekonsiliasi as r 
        // JOIN periode as p ON r.id_periode = p.id_periode
        // JOIN m_periode as mp ON mp.id_periode = p.m_periode
        // JOIN m_cabang_asuransi as ca ON ca.id_cabang_asuransi = p.id_cabang_as

        // WHERE mp.nama_periode BETWEEN '2019-11' AND '2019-12'

        // ORDER BY ca.cabang_asuransi ASC

        $this->db->DISTINCT();
        $this->db->select('ca.id_cabang_asuransi, ca.cabang_asuransi, mp.nama_periode');
        $this->db->from('rekonsiliasi as r');
        $this->db->join('periode as p', 'r.id_periode = p.id_periode', 'inner');
        $this->db->join('m_periode as mp', 'mp.id_periode = p.m_periode', 'inner');
        $this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = p.id_cabang_as', 'inner');
        $this->db->where("mp.nama_periode BETWEEN '$a' AND '$k'");
        $this->db->order_by('ca.cabang_asuransi', 'asc');

        return $this->db->get();
                
    }

    public function get_data_recov_aju($id_cabang_as, $nama_periode, $aksi)
    {
        
        $this->db->DISTINCT();
        if ($aksi == 'recov_aju') {
            $this->db->select('i.recoveries_aju');
        } else {
            $this->db->select('i.komisi_diajukan');
        }
        
        $this->db->from('rekonsiliasi as r');
        $this->db->join('periode as p', 'r.id_periode = p.id_periode', 'inner');
        $this->db->join('m_periode as mp', 'mp.id_periode = p.m_periode', 'inner');
        $this->db->join('m_cabang_asuransi as ca', 'ca.id_cabang_asuransi = p.id_cabang_as', 'inner');
        $this->db->join('invoice as i', 'i.id_invoice = r.id_invoice', 'inner');
        
        $this->db->where('ca.id_cabang_asuransi', $id_cabang_as);
        $this->db->where("mp.nama_periode", $nama_periode);

        return $this->db->get();
    }

    /***********************************************************************/
    /*
    /*                          LAPORAN KEUANGAN 4    
    /*
    /**********************************************************************/

    public function get_pengeluran_sps($data)
    {
        if ($data['total'] == 'tidak') {
            // cari id karyawan dari database hrd 
            $this->db_2->from('karyawan');
            $this->db_2->where('nik', $data['nik']);
            
            $hasil = $this->db_2->get()->row_array();
        }
        
        $this->db_2->select('sum(dj.debit) as tot_pengeluaran');
        $this->db_2->from('detail_jurnal as dj');
        $this->db_2->join('des_coa as dc', 'dc.no_coa_des = dj.coa', 'inner');
        $this->db_2->join('anggota as a', 'CAST(a.id_anggota as VARCHAR) = CAST(dj.pelaksana as VARCHAR)', 'inner');
        $this->db_2->join('posisi as p', 'p.id_posisi = a.id_posisi', 'inner');
        $this->db_2->join('bagian as b','b.id_bagian = p.id_bagian', 'inner');
        $this->db_2->join('karyawan as k', 'k.id_anggota = a.id_anggota', 'inner');
        
        $this->db_2->where('b.id_bagian', 4);
        $this->db_2->where('dj.id_group', 8);

        if ($data['total'] == 'tidak') {

            $this->db_2->where('k.id_karyawan', $hasil['id_karyawan']);

        }

        $tgl        = nice_date($data['tgl_awal'], 'm');
        $tgl_ak     = date('t', strtotime($data['bulan_awal']));
        $tgl_ak_2   = date('t', strtotime($data['bulan']));

        if ($data['jumlah'] != 1) {

            // jika bulan (foreach) sama dengan input bulan awal
            if ($data['bulan'] == $data['bulan_awal']) {

                $tgl_awal   = $data['tgl_awal'];
                $tgl_akhir  = $data['bulan_awal']."-".$tgl_ak;
                
            // jika bulan (foreach) sama dengan input bulan akhir
            } elseif ($data['bulan'] == $data['bulan_akhir']) {
                
                $tgl_awal   = $data['bulan']."-01";
                $tgl_akhir  = $data['tgl_akhir'];

            // jika tidak bulan awal maupun bulan akhir
            } else {

                $tgl_awal   = $data['bulan']."-01";
                $tgl_akhir  = $data['bulan']."-".$tgl_ak_2;

            }

        } else {

            $tgl_awal   = $data['tgl_awal'];
            $tgl_akhir  = $data['tgl_akhir'];

        }

        $this->db_2->where("dj.tgl_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'");

        return $this->db_2->get();
        
    }

    public function get_recov_invoice($id_karyawan)
    {
        // SELECT i.recoveries_aju, i.id_invoice, i.komisi_diajukan
        // FROM penempatan as p 
        // JOIN m_capem_bank as cap ON cap.id_capem_bank = p.id_capem_bank 
        // JOIN m_cabang_bank as cab ON cab.id_cabang_bank = cap.id_cabang_bank 
        // JOIN periode as pe ON pe.id_cabang_bank = cab.id_cabang_bank 
        // JOIN rekonsiliasi as r ON r.id_periode = pe.id_periode 
        // JOIN invoice as i ON i.id_invoice = r.id_invoice
        // JOIN karyawan as k ON k.id_karyawan = p.id_karyawan
        // WHERE k.id_karyawan = 3

        // GROUP BY i.recoveries_aju, i.id_invoice

        $this->db->select("i.recoveries_aju, i.id_invoice, i.komisi_diajukan");
		$this->db->from('penempatan as p');
		$this->db->join('m_capem_bank as cap', 'cap.id_capem_bank = p.id_capem_bank', 'inner');
        $this->db->join('m_cabang_bank as cab', 'cab.id_cabang_bank = cap.id_cabang_bank', 'inner');
        $this->db->join('periode as pe', 'pe.id_cabang_bank = cab.id_cabang_bank', 'inner');
        $this->db->join('rekonsiliasi as r', 'r.id_periode = pe.id_periode', 'inner');
        $this->db->join('invoice as i', 'i.id_invoice = r.id_invoice', 'inner');
        $this->db->join('karyawan as k', 'k.id_karyawan = p.id_karyawan', 'inner');
        
        $this->db->where('k.id_karyawan', $id_karyawan);

        $this->db->group_by('i.recoveries_aju');
        $this->db->group_by('i.id_invoice');

        return $this->db->get();
        
    }
        
    /***********************************************************************/
    /*
    /*                          LAPORAN KEUANGAN 5    
    /*
    /**********************************************************************/

    public function get_data_pengeluaran($dt)
    {
        $this->db_2->select('dj.tgl_transaksi, dj.keterangan, dc.deskripsi_coa, dc.no_coa_des, a.pengguna, dj.debit');
        $this->db_2->from('detail_jurnal as dj');
        $this->db_2->join('des_coa as dc', 'dc.no_coa_des = dj.coa', 'inner');
        $this->db_2->join('anggota as a', 'CAST(a.id_anggota as VARCHAR) = CAST(dj.pelaksana as VARCHAR)', 'inner');
        $this->db_2->join('posisi as p', 'p.id_posisi = a.id_posisi', 'inner');
        $this->db_2->join('bagian as b','b.id_bagian = p.id_bagian', 'inner');
        $this->db_2->where('b.id_bagian', 4);
        $this->db_2->where('dj.id_group', 8);
        
        if ($dt['tanggal_awal'] != '' && $dt['tanggal_akhir'] != '') {

            $tgl_awal   = nice_date($dt['tanggal_awal'], 'Y-m-d'); 
            $tgl_akhir  = nice_date($dt['tanggal_akhir'], 'Y-m-d');

            $this->db_2->where("CAST(dj.tgl_transaksi AS VARCHAR) BETWEEN '$tgl_awal' AND '$tgl_akhir+1'");
        }

        return $this->db_2->get();
        
    }

}

/* End of file M_laporan.php */
