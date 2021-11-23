<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Katalog
 */
class R_noa extends CI_controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
        $this->load->model(array('M_noa','M_ots', 'M_home', 'M_eks_asset'));
        $this->cabang_as = $this->session->userdata('cabang_as');
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

        $tot = $this->M_noa->get_tot_r_noa($dt, 'semua')->result_array();

        $tot_denda          = 0;
        $tot_bunga          = 0;
        $tot_subro_as       = 0;
        $tot_recov_awal_as  = 0;
        $tot_nominal_as     = 0;

        foreach ($tot as $t) {
            $tot_denda          += $t['denda'];
            $tot_bunga          += $t['bunga'];
            $tot_subro_as       += $t['subrogasi_as'];
            $tot_recov_awal_as  += $t['recoveries_awal_asuransi'];
            $tot_nominal_as     += $t['tot_nominal_as'];
        }

        $tot_tagihan        = $tot_subro_as + $tot_bunga + $tot_denda;
        $tot_shs            = ($tot_subro_as - $tot_recov_awal_as) - $tot_nominal_as;
        $tot_recov          = $tot_recov_awal_as + $tot_nominal_as;
        $tot_saldo_tagihan  = $tot_tagihan - $tot_recov;

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
            'judul'             => 'NOA',
            'id_cabang_as'      => $id_cabang_as,
            'tot_noa'           => $this->M_noa->get_tot_r_noa($dt, 'semua')->num_rows(),
            'tot_sudah_ots'     => $this->M_home->get_total_noa_2($dt)->num_rows(),
            'tot_blm_ots'       => $this->M_noa->get_tot_r_noa($dt, 'semua')->num_rows() - $this->M_home->get_total_noa_2($dt)->num_rows(),
            'tot_potensial'     => $this->M_noa->get_tot_r_noa($dt, 'potensial')->num_rows(),
            'tot_non_potensial' => $this->M_noa->get_tot_r_noa($dt, 'non_potensial')->num_rows(),
            'tot_subro_as'      => "Rp. ".number_format($tot_subro_as, '2',',','.'),
            'tot_tagihan'       => "Rp. ".number_format($tot_tagihan,'2',',','.'),
            'tot_recov'         => "Rp. ".number_format($tot_recov, '2',',','.'),
            'tot_shs'           => "Rp. ".number_format($tot_shs,'2',',','.'),
            'tot_saldo_tagihan' => "Rp. ".number_format($tot_saldo_tagihan,'2',',','.'),
            'bank'              => $bnk,
            'asuransi'          => $asr,
            'verifikator'       => $this->M_ots->get_verifikator_new()->result_array(),
            'level'             => $level,
            'akses'             => $akses,
            'nm_cabang'         => $this->M_home->cari_data('m_cabang_asuransi', array('id_cabang_asuransi' => $id_cabang_as))->row_array(),
            'no_spk'            => $this->M_home->cari_data('spk', array('id_spk' => $id_spk))->row_array(),
            'spk'               => $this->M_home->cari_data('spk', array('status' => 1))->result_array()
        ];

        $this->template->load('layout/template','noa/V_noa', $data); 
        
    }

    // unduh data 
    public function unduh_data($aksi)
    {

        $level = $this->uri->segment(4);
        // cari tanggal awal pada tabel recov 
        $tgl_awal = $this->M_noa->cari_tgl_bayar_awal($level)->row_array();
        
        $dt = [ 'asuransi'          => $this->input->post('asuransi'),
                'cabang_asuransi'   => $this->input->post('cabang_asuransi'),
                'bank'              => $this->input->post('bank'),
                'cabang_bank'       => $this->input->post('cabang_bank'),
                'capem_bank'        => $this->input->post('capem_bank'),
                'tanggal_awal'      => $tgl_awal['tgl_bayar'],
                'tanggal_akhir'     => $this->input->post('tanggal_akhir'),
                'verifikator'       => $this->input->post('verifikator'),
                'spk'               => $this->input->post('spk'),
                'level'             => $level
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

        $sp    = $this->M_ots->cari_data('spk', array('id_spk' => $dt['spk']))->row_array();

        if ($dt['spk'] != 'a') {
            if ($dt['spk'] == 'null') {
                $spk = 'No SPK';
            } else {
                $spk   = $sp['no_spk'];
            }
        } else {
            $spk    = ""; 
        }

        $all = "LAPORAN PENGELOLAAN SUBROGASI DAN RECOVERY";

        $data = [
                'judul'             => $all,
                'id_cabang_as'      => $this->cabang_as,
                'level'             => $this->uri->segment(4),
                'data_r_noa'        => $this->M_noa->get_cari_print_data_r_noa($dt, 'semua'),
                'noa_potensial'     => $this->M_noa->get_cari_print_data_r_noa($dt, 'potensial'),
                'noa_non_potensial' => $this->M_noa->get_cari_print_data_r_noa($dt, 'non_potensial'),
                'data_noa_jml'      => $this->M_noa->get_data_noa_jml($dt)->result_array(),
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
                'tgl_awal'          => nice_date($dt['tanggal_awal'], 'd-F-Y'),
                'tgl_akhir'         => $dt['tanggal_akhir'],
                'report'            => 'Noa',
                'id_spk'            => $dt['spk'],
                'spk'               => $sp['no_spk']
            ];

        if ($aksi == 'lihat') {
            $this->load->view('noa/lihat_print', $data);
        } else {
            if (isset($_POST['pdf'])) {
                $data['kondisi'] = 'pdf';
                $this->template->load('template_pdf', 'noa/lihat_print', $data);
            } else {
                $data['kondisi'] = 'excel';
                $this->template->load('template_excel', 'noa/lihat_print', $data);
            }
        }
    }

    // ambil data total
    public function ambil_data_total()
    {
        $level = $this->session->userdata('level');
        
        if ($level == 10) {
            $syr = 'syariah';
        } elseif ($level == 13) {
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

        $tot = $this->M_noa->get_tot_r_noa($dt, 'semua')->result_array();

        $tot_denda          = 0;
        $tot_bunga          = 0;
        $tot_subro_as       = 0;
        $tot_recov_awal_as  = 0;
        $tot_nominal_as     = 0;

        foreach ($tot as $t) {
            $tot_denda          += $t['denda'];
            $tot_bunga          += $t['bunga'];
            $tot_subro_as       += $t['subrogasi_as'];
            $tot_recov_awal_as  += $t['recoveries_awal_asuransi'];
            $tot_nominal_as     += $t['tot_nominal_as'];
        }

        $tot_tagihan        = $tot_subro_as + $tot_bunga + $tot_denda;
        $tot_shs            = ($tot_subro_as - $tot_recov_awal_as) - $tot_nominal_as;
        $tot_recov          = $tot_recov_awal_as + $tot_nominal_as;
        $tot_saldo_tagihan  = $tot_tagihan - $tot_recov;

        $data = [
            'tot_noa'           => $this->M_noa->get_tot_r_noa($dt, 'semua')->num_rows(),
            'tot_sudah_ots'     => $this->M_home->get_total_noa_2($dt)->num_rows(),
            'tot_blm_ots'       => $this->M_noa->get_tot_r_noa($dt, 'semua')->num_rows() - $this->M_home->get_total_noa_2($dt)->num_rows(),
            'tot_potensial'     => $this->M_noa->get_tot_r_noa($dt, 'potensial')->num_rows(),
            'tot_non_potensial' => $this->M_noa->get_tot_r_noa($dt, 'non_potensial')->num_rows(),
            'tot_subro_as'      => "Rp. ".number_format($tot_subro_as, '2',',','.'),
            'tot_tagihan'       => "Rp. ".number_format($tot_tagihan,'2',',','.'),
            'tot_recov'         => "Rp. ".number_format($tot_recov, '2',',','.'),
            'tot_shs'           => "Rp. ".number_format($tot_shs,'2',',','.'),
            'tot_saldo_tagihan' => "Rp. ".number_format($tot_saldo_tagihan,'2',',','.')
        ];

        echo json_encode($data);
    }

    // menampilkan data
    public function tampil_r_noa()
    {
        $level = $this->uri->segment(3);

        // cari tanggal awal pada tabel recov 
        $tgl_awal = $this->M_noa->cari_tgl_bayar_awal($level)->row_array();
        
        $dt = [ 'asuransi'          => $this->input->post('asuransi'),
                'cabang_asuransi'   => $this->input->post('cabang_asuransi'),
                'bank'              => $this->input->post('bank'),
                'cabang_bank'       => $this->input->post('cabang_bank'),
                'capem_bank'        => $this->input->post('capem_bank'),
                'tanggal_awal'      => $tgl_awal['tgl_bayar'],
                'tanggal_akhir'     => $this->input->post('tanggal_akhir'),
                'verifikator'       => $this->input->post('verifikator'),
                'spk'               => $this->input->post('spk'),
                'level'             => $level
        ];

        $list = $this->M_noa->get_data_noa($dt);

        $data = array();

        $no   = $this->input->post('start');

        foreach ($list as $o) {
            $no++;
            $tbody = array();      

            $recov_as   = $o['tot_nominal_as'];

            
                $shs        = ($o['subrogasi_as'] - $o['recoveries_awal_asuransi']) - $o['tot_nominal_as'];
            

            if ($o['potensial'] == 1) {
                $status = "<div align='center'><h6><span class='badge badge-info badge-pill'>Potensial</span></h6></div>";
            } else {
                if ($o['potensial'] != null) {
                   $status = "<div align='center'><h6><span class='badge badge-danger badge-pill'>Non Potensial</span></h6></div>"; 
                } else {
                    $status = '';
                }
            }

            $tbody[]    = "<div align='center'>".$no."</div>";
            $tbody[]    = $o['nama_debitur'];
            $tbody[]    = $o['no_klaim'];
            $tbody[]    = $o['bank'];
            $tbody[]    = $o['cabang_bank'];
            if ($this->cabang_as == '') :
            $tbody[]    = $o['cabang_asuransi'];
            endif;
            $tbody[]    = "<div align='right'>Rp. ".number_format($o['subrogasi_as'],'0','.','.')."</div>";
            $tbody[]    = "<div align='right'>Rp. ".number_format($recov_as,'0','.','.')."</div>";
            $tbody[]    = "<div align='right'>Rp. ".number_format($shs,'0','.','.')."</div>";
            $tbody[]    = $status;
            $data[]     = $tbody;
        }

        $output = [ "draw"             => $_POST['draw'],
                    "recordsTotal"     => $this->M_noa->jumlah_semua_noa($dt),
                    "recordsFiltered"  => $this->M_noa->jumlah_filter_noa($dt),   
                    "data"             => $data
                ];

        echo json_encode($output);
    }

    // menampilkan data summary 
    public function tampil_r_noa_as()
    {
        $dt = [ 'asuransi'          => $this->input->post('asuransi'),
                'cabang_asuransi'   => $this->input->post('cabang_asuransi'),
                'bank'              => $this->input->post('bank'),
                'cabang_bank'       => $this->input->post('cabang_bank'),
                'capem_bank'        => $this->input->post('capem_bank'),
                'tanggal_awal'      => $this->input->post('tanggal_awal'),
                'tanggal_akhir'     => $this->input->post('tanggal_akhir'),
                'verifikator'       => $this->input->post('verifikator'),
                'verifikator'       => $this->input->post('verifikator'),
                'spk'               => $this->input->post('spk'),
                'level'             => $this->uri->segment(3)
        ];
        
        $list = $this->M_noa->get_data_noa_as($dt);

        $data = array();

        $no   = $this->input->post('start');

        foreach ($list as $o) {
            $no++;
            $tbody = array();      

            $recov_as       = $o['recoveries_awal_as'] + $o['tot_recov_as'];
            $tot_tagihan    = $o['subrogasi_as'] + $o['bunga'] + $o['denda'];

            $shs        = ($o['subrogasi_as'] - $o['recoveries_awal_as']) - $o['tot_recov_as'];

            $tbody[]    = "<div align='center'>".$no."</div>";
            $tbody[]    = $o['nama_lengkap'];
            $tbody[]    = "<div align='center'>".$o['noa_kelolaan']."</div>";
            $tbody[]    = "<div align='right'>Rp. ".number_format($shs,'0','.','.')."</div>";
            $tbody[]    = "<div align='right'>Rp. ".number_format($recov_as,'0','.','.')."</div>";
            $data[]     = $tbody;
        }

        $output = [ "draw"             => $_POST['draw'],
                    "recordsTotal"     => $this->M_noa->jumlah_semua_noa_as($dt),
                    "recordsFiltered"  => $this->M_noa->jumlah_filter_noa_as($dt),   
                    "data"             => $data
                ];

        echo json_encode($output);
    }

}