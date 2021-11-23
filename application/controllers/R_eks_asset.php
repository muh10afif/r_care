<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class R_eks_asset extends CI_controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
        $this->load->model(array('M_eks_asset', 'M_noa','M_recov', 'M_home', 'M_ots'));
	}

    public function index()
    {
        $dt = [ 'asuransi'          => 'a',
                'cabang_asuransi'   => 'a',
                'bank'              => 'a',
                'cabang_bank'       => 'a',
                'capem_bank'        => 'a',
                'tanggal_awal'      => '',
                'tanggal_akhir'     => '',
                'verifikator'       => 'a',
                'spk'               => 'a'
        ];

        $d_tot = $this->M_eks_asset->get_cari_data_wilayah_ver($dt);

        foreach ($d_tot as $t) {
            $tot_jumlah_asset        = $t['tot_jumlah_asset'];
            $tot_hasil_penjualan     = $t['tot_hasil_penjualan'];
            $tot_jumlah_asset_sdh    = $t['tot_jumlah_asset_sdh'];
            $tot_hasil_penjualan_pot = $t['tot_hasil_penjualan_pot'];
        }

        $data = [
            'judul'                     => 'Eks Asset',
            'bank'                      => $this->M_home->get_data('m_bank')->result_array(),
            'asuransi'                  => $this->M_home->get_data('m_asuransi')->result_array(),
            'verifikator'               => $this->M_ots->get_verifikator_new()->result_array(),
            'tot_jumlah_asset'          => $tot_jumlah_asset,
            'tot_hasil_penjualan'       => $tot_hasil_penjualan,
            'tot_jumlah_asset_sdh'      => $tot_jumlah_asset_sdh,
            'tot_hasil_penjualan_pot'   => $tot_hasil_penjualan_pot,
            'spk'                       => $this->M_eks_asset->cari_data('spk', array('status' => 1))->result_array()
        ];

        $this->template->load('layout/template','eks_asset/V_eks_asset', $data);

    }

    // ambil data total 
    public function ambil_data_total()
    {
        $dt = [ 'asuransi'          => $this->input->post('asuransi'),
                'cabang_asuransi'   => $this->input->post('cabang_asuransi'),
                'bank'              => $this->input->post('bank'),
                'cabang_bank'       => $this->input->post('cabang_bank'),
                'capem_bank'        => $this->input->post('capem_bank'),
                'tanggal_awal'      => $this->input->post('tanggal_awal'),
                'tanggal_akhir'     => $this->input->post('tanggal_akhir'),
                'verifikator'       => $this->input->post('verifikator'),
                'spk'               => $this->input->post('spk')
        ];

        $d_tot = $this->M_eks_asset->get_cari_data_wilayah_ver($dt);

        foreach ($d_tot as $t) {
            $tot_jumlah_asset        = $t['tot_jumlah_asset'];
            $tot_hasil_penjualan     = $t['tot_hasil_penjualan'];
            $tot_jumlah_asset_sdh    = $t['tot_jumlah_asset_sdh'];
            $tot_hasil_penjualan_pot = $t['tot_hasil_penjualan_pot'];
        }

        $data = ['tot_jumlah_asset'         => $tot_jumlah_asset." Unit",
                 'tot_hasil_penjualan'      => "Rp. ".number_format($tot_hasil_penjualan, '0', '.','.'),
                 'tot_jumlah_asset_sdh'     => $tot_jumlah_asset_sdh." Unit",
                 'tot_hasil_penjualan_pot'  => "Rp. ".number_format($tot_hasil_penjualan_pot, '0', '.', '.')
        ];
        
        echo json_encode($data);
    }

    // menampilkan data
    public function tampil_eks_asset()
    {
        $nama = ['asuransi'         => $this->input->post('asuransi'),
                'cabang_asuransi'   => $this->input->post('cabang_asuransi'),
                'bank'              => $this->input->post('bank'),
                'cabang_bank'       => $this->input->post('cabang_bank'),
                'capem_bank'        => $this->input->post('capem_bank'),
                'tanggal_awal'      => $this->input->post('tanggal_awal'),
                'tanggal_akhir'     => $this->input->post('tanggal_akhir'),
                'verifikator'       => $this->input->post('verifikator'),
                'spk'               => $this->input->post('spk')
        ];

        $list = $this->M_eks_asset->get_data_eks_asset($nama);

        $data = array();

        $no   = $this->input->post('start');

        foreach ($list as $o) {
            $no++;
            $tbody = array();

            $tbody[]    = "<div align='center'>".$no."</div>";
            $tbody[]    = $o['id_care'];
            $tbody[]    = $o['no_klaim'];
            $tbody[]    = $o['nama_debitur'];
            $tbody[]    = $o['capem_bank'];
            $tbody[]    = $o['cabang_asuransi'];
            $tbody[]    = "<div align='center'><h4><span class='badge badge-success'>".$o['status_asset']."</span></h4></div>";
            $tbody[]    = $o['verifikator'];
            $data[]     = $tbody;
        }

        $output = [ "draw"             => $_POST['draw'],
                    "recordsTotal"     => $this->M_eks_asset->jumlah_semua_eks_asset($nama),
                    "recordsFiltered"  => $this->M_eks_asset->jumlah_filter_eks_asset($nama),   
                    "data"             => $data
                ];

        echo json_encode($output);
    }

    // proses unduh data 
    public function unduh_data($aksi)
    {
        $dt  = ['asuransi'          => $this->input->post('asuransi'),
                'cabang_asuransi'   => $this->input->post('cabang_asuransi'),
                'bank'              => $this->input->post('bank'),
                'cabang_bank'       => $this->input->post('cabang_bank'),
                'capem_bank'        => $this->input->post('capem_bank'),
                'tanggal_awal'      => $this->input->post('tgl_awal'),
                'tanggal_akhir'     => $this->input->post('tgl_akhir'),
                'verifikator'       => $this->input->post('verifikator'),
                'spk'               => $this->input->post('spk')
        ];

        if ($dt['asuransi'] != 'a') {
            $nm_asuransi2   = $this->M_eks_asset->cari_data('m_asuransi', array('id_asuransi' => $dt['asuransi']))->row_array();
            $nm_asuransi    = $nm_asuransi2['asuransi'];
        } else {
            $nm_asuransi    = "";
        }
        if ($dt['cabang_asuransi'] != 'a') {
            $cb_asuransi2    = $this->M_eks_asset->cari_data('m_cabang_asuransi', array('id_cabang_asuransi' => $dt['cabang_asuransi']))->row_array();
            $cb_asuransi     = $cb_asuransi2['cabang_asuransi'];
        } else {
            $cb_asuransi     = "";
        }
        if ($dt['bank'] != 'a') {
            $nm_bank2   = $this->M_eks_asset->cari_data('m_bank', array('id_bank' => $dt['bank']))->row_array();
            $nm_bank    = $nm_bank2['bank'];
        } else {
            $nm_bank     = "";
        }
        if ($dt['cabang_bank'] != 'a') {
            $cb_bank2   = $this->M_eks_asset->cari_data('m_cabang_bank', array('id_cabang_bank' => $dt['cabang_bank']))->row_array();
            $cb_bank    = $cb_bank2['cabang_bank'];
        } else {
            $cb_bank    = "";
        }
        if ($dt['capem_bank'] != 'a') {
            $cpm_bank2   = $this->M_eks_asset->cari_data('m_capem_bank', array('id_capem_bank' => $dt['capem_bank']))->row_array();
            $cpm_bank    = $cpm_bank2['capem_bank'];
        } else {
            $cpm_bank    = "";
        }
        if ($dt['verifikator'] != 'a') {
            $ver2   = $this->M_eks_asset->cari_data('karyawan', array('id_karyawan' => $dt['verifikator']))->row_array();
            $ver    = $ver2['nama_lengkap'];
        } else {
            $ver     = "";
        }
        if ($dt['spk'] != 'a') {
            if ($dt['spk'] == 'null') {
                $spk = 'No SPK';
            } else {
                $sp    = $this->M_eks_asset->cari_data('spk', array('id_spk' => $dt['spk']))->row_array();
                $spk   = $sp['no_spk'];
            }
        } else {
            $spk    = ""; 
        }

        $all = "LAPORAN PENGELOLAAN SUBROGASI DAN RECOVERY";

        $data = [
                'judul'             => $all,
                'data_r_eks_asset'  => $this->M_eks_asset->get_cari_data_r_eks_asset($dt),
                'kondisi'           => $aksi,
                'asuransi'          => $nm_asuransi,
                'asuransi_id'       => $dt['asuransi'],
                'cbg_asuransi'      => $cb_asuransi,
                'cbg_asuransi_id'   => $dt['cabang_asuransi'],
                'bank'              => $nm_bank,
                'bank_id'           => $dt['bank'],
                'cbg_bank'          => $cb_bank,
                'cbg_bank_id'       => $dt['cabang_bank'],
                'cpm_bank'          => $cpm_bank,
                'cpm_bank_id'       => $dt['capem_bank'],
                'verifikator'       => $ver,
                'verifikator_id'    => $dt['verifikator'],
                'tgl_awal'          => $dt['tanggal_awal'],
                'tgl_akhir'         => $dt['tanggal_akhir'],
                'report'            => 'Eks Asset',
                'id_spk'            => $dt['spk'],
                'spk'               => $spk
            ];

        if ($aksi == 'lihat') {
            $this->load->view('eks_asset/lihat_print', $data);
        } else {
            if (isset($_POST['pdf'])) {
                $data['kondisi'] = 'pdf';
                $this->template->load('template_pdf', 'eks_asset/lihat_print', $data);
            } else {
                $data['kondisi'] = 'excel';
                $this->template->load('template_excel', 'eks_asset/lihat_print', $data);
            }
        }
        
    }
}