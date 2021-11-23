<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class R_proses extends CI_controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
        $this->load->model(array('M_noa', 'M_proses','M_recov', 'M_home', 'M_ots', 'M_eks_asset'));
	}
	
    public function index()
    {
        $level          = $this->uri->segment(3);

        $id_spk         = $this->uri->segment(4);
        $id_cabang_as   = $this->uri->segment(5);

        $akses          = $this->session->userdata('akses');

        if ($akses == 'kanwil_asuransi') {
            $array = array(
                'id_spk'    => $id_spk,
                'cabang_as' => $id_cabang_as
            );
            
            $this->session->set_userdata( $array );
        }

        $id_cabang_as = $this->session->userdata('cabang_as');
        
        $dt = [ 'asuransi'          => 'a',
                'cabang_asuransi'   => 'a',
                'bank'              => 'a',
                'cabang_bank'       => 'a',
                'capem_bank'        => 'a',
                'tanggal_awal'      => '',
                'tanggal_akhir'     => '',
                'verifikator'       => 'a',
                'spk'               => 'a',
                'level'             => $level
        ];

        $d_tot = $this->M_proses->get_cari_data_wilayah_ver($dt);

        foreach ($d_tot as $d) {
            $tot_tindakan_somasi = $d['tot_tindakan_somasi'];
            $tot_tindakan        = $d['tot_tindakan'];
            $tot_potensi_recov   = $d['potensi_recov'];
        }

        $cr = $this->M_home->cari_id_asuransi($id_cabang_as)->row_array();

        if ($level == "syariah") {
            $asr = $this->M_home->cari_data('m_asuransi', array('id_asuransi' => 2))->result_array();
            $bnk = $this->M_home->get_data_bank('m_bank', 'x', 'syariah')->result_array();
        } elseif ($level == 'asuransi') {
            $asr = $this->M_home->cari_data('m_asuransi', array('id_asuransi' => $cr['id_asuransi']))->result_array();
            $bnk = $this->M_home->get_data_bank('m_bank', $id_cabang_as, 'asuransi')->result_array();
        } else {
            $asr = $this->M_home->get_data('m_asuransi')->result_array();
            $bnk = $this->M_home->get_data('m_bank')->result_array();
        }
        
        $data = [
            'judul'                 => 'Report Proses',
            'id_cabang_as'          => $id_cabang_as,
            'bank'                  => $bnk,
            'asuransi'              => $asr,
            'verifikator'           => $this->M_ots->get_verifikator_new()->result_array(),
            'tot_tindakan_somasi'   => $tot_tindakan_somasi,
            'tot_tindakan'          => $tot_tindakan,
            'potensi_recov'         => $tot_potensi_recov,
            'level'                 => $level,
            'akses'                 => $akses,
            'nm_cabang'             => $this->M_home->cari_data('m_cabang_asuransi', array('id_cabang_asuransi' => $id_cabang_as))->row_array(),
            'no_spk'                => $this->M_home->cari_data('spk', array('id_spk' => $id_spk))->row_array(),
            'spk'                   => $this->M_home->cari_data('spk', array('status' => 1))->result_array()
        ];

        $this->template->load('layout/template','proses/V_proses', $data);
        
    }

    // proses ambil total 
    public function ambil_data_total()
    {
        $syariah = $this->session->userdata('level');
        
        if ($syariah == 10) {
            $syr = 'syariah';
        } elseif ($syariah == 13) {
            $syr = 'asuransi';
        } else {
            $syr = "";
        }

        $dt = [ 'asuransi'          => $this->input->post('asuransi'),
                'cabang_asuransi'   => $this->input->post('cabang_asuransi'),
                'bank'              => $this->input->post('bank'),
                'cabang_bank'       => $this->input->post('cabang_bank'),
                'capem_bank'        => $this->input->post('capem_bank'),
                'tanggal_awal'      => $this->input->post('tanggal_awal'),
                'tanggal_akhir'     => $this->input->post('tanggal_akhir'),
                'verifikator'       => $this->input->post('verifikator'),
                'spk'               => $this->input->post('spk'),
                'level'             => $syr
        ];

        $d_tot = $this->M_proses->get_cari_data_wilayah_ver($dt);

        foreach ($d_tot as $d) {
            $tot_tindakan_somasi = $d['tot_tindakan_somasi'];
            $tot_tindakan        = $d['tot_tindakan'];
            $tot_potensi_recov   = $d['potensi_recov'];
        }

        $data = ['tot_tindakan_somasi'  => $tot_tindakan_somasi." Surat",
                 'tot_tindakan'         => $tot_tindakan." Unit",
                 'tot_potensi_recov'    => "Rp. ".number_format($tot_potensi_recov, '0', '.','.')
        ];

        echo json_encode($data);
    }

    // proses menampilkan data 
    public function tampil_r_proses()
    {
        $dt = [ 'asuransi'          => $this->input->post('asuransi'),
                'cabang_asuransi'   => $this->input->post('cabang_asuransi'),
                'bank'              => $this->input->post('bank'),
                'cabang_bank'       => $this->input->post('cabang_bank'),
                'capem_bank'        => $this->input->post('capem_bank'),
                'tanggal_awal'      => $this->input->post('tanggal_awal'),
                'tanggal_akhir'     => $this->input->post('tanggal_akhir'),
                'verifikator'       => $this->input->post('verifikator'),
                'spk'               => $this->input->post('spk'),
                'level'             => $this->uri->segment(3)                
        ];

        $list = $this->M_proses->get_data_proses($dt);

        $data = array();

        $no   = $this->input->post('start');

        foreach ($list as $o) {
            $no++;
            $tbody = array();

            // sisa hutang (bank)
            if ($o['tot_nominal_bank'] == 0) {
                $sisa_hutang = 0;
            } else {
                $sisa_hutang = ($o['besar_klaim'] - $o['recoveries_awal_bank']) - $o['tot_nominal_bank'];
            }

            // shs
            if ($o['tot_nominal_as'] == 0) {
                $shs = 0;
            } else {
                $shs         = ($o['subrogasi_as'] - $o['recoveries_awal_asuransi']) - $o['tot_nominal_as'];
            }        

            $tbody[]    = "<div align='center'>".$no."</div>";
            $tbody[]    = $o['id_care'];
            $tbody[]    = $o['no_klaim'];
            $tbody[]    = $o['nama_debitur'];
            $tbody[]    = $o['capem_bank'];
            $tbody[]    = $o['cabang_asuransi'];
            $tbody[]    = "<div align='right'>Rp. ".$sisa_hutang."</div>";
            $tbody[]    = "<div align='right'>Rp. ".$shs."</div>";
            $tbody[]    = "<div align='center'><h4><span class='badge badge-success'>".$o['tindakan_hukum']."</span></h4></div>";
            $tbody[]    = "<div align='center'><h4><span class='badge badge-success'>".$o['status_asset']."</span></h4></div>";
            $data[]     = $tbody;
        }

        $output = [ "draw"             => $_POST['draw'],
                    "recordsTotal"     => $this->M_proses->jumlah_semua_proses($dt),
                    "recordsFiltered"  => $this->M_proses->jumlah_filter_proses($dt),   
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
                'spk'               => $this->input->post('spk'),
                'level'             => $this->uri->segment(4)
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
                'level'             => $this->uri->segment(4),                
                'data_r_proses'     => $this->M_proses->get_cari_data_r_proses($dt)->result_array(),
                'jml_r_proses'      => $this->M_proses->get_jml_r_proses($dt)->result_array(),
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
                'report'            => 'Proses',
                'id_spk'            => $dt['spk'],
                'spk'               => $spk
            ];

        if ($aksi == 'lihat') {
            $this->load->view('proses/lihat_print', $data);
        } else {
            if (isset($_POST['pdf'])) {
                $data['kondisi'] = 'pdf';
                $this->template->load('template_pdf', 'proses/lihat_print', $data);
            } else {
                $data['kondisi'] = 'excel';
                $this->template->load('template_excel', 'proses/lihat_print', $data);
            }
        }
    }

}