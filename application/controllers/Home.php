<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
        $this->load->model(array('M_home', 'M_ots', 'M_noa'));
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

        if ($akses == 'kanwil_asuransi' || $akses == 'user_asuransi') {
            if ($id_spk == '' && $id_cabang_as == '') {
                if ($level == 'kanwil_asuransi') {
                    $level_a = 14;
                } else {
                    $level_a = 13;
                }
            } else {
                $level_a = 13;
            }
            
        } else {
            $level_a = 14;
        }

        $id_cabang_as   = $this->session->userdata('cabang_as');  
        $korwil         = $this->session->userdata('kanwil_as');
        
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

        $kar = $this->M_home->get_karyawan_default($dt)->row_array();

        if (!defined('UPLOAD_DIR')) define('UPLOAD_DIR', 'assets/img/');

        $name_file = 'pp.png';
        
        $image_base64 = base64_decode($kar['file_foto']);
        $file = UPLOAD_DIR . $name_file;
        file_put_contents($file, $image_base64);

        $bln = array();

        $skrg = date("Y-m");

        $bln_s = date("Y-m", strtotime("$skrg +1 months"));

        for ($i=1; $i <= 5; $i++) { 
            $a = date('Y-m', strtotime("$skrg -$i months"));
            array_push($bln, array('id' => $a));
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
            $bnk = $this->M_home->get_data_order('m_bank', 'bank', 'asc')->result_array();
        }

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
        $tot_shs            = ($tot_subro_as) - $tot_nominal_as;
        $tot_recov          = $tot_nominal_as;
        $tot_saldo_tagihan  = $tot_tagihan - $tot_recov;

        $tag_b1 = $this->M_home->get_tagihan_recov_bank($dt, 'besar_klaim')->row_array();
        $tag_b2 = $this->M_home->get_tagihan_recov_bank($dt, 'recov_awal')->row_array();
        $tag_b3 = $this->M_home->get_tagihan_recov_bank($dt, 'nominal')->row_array();

        $tag_bank = ($tag_b1['tot_besar_klaim']) - $tag_b3['tot_nominal_bank'];
        $rec_bank = $tag_b3['tot_nominal_bank'];

        $sh_as1 = $this->M_home->get_shs_recov_asuransi($dt, 'subro')->row_array();
        $sh_as2 = $this->M_home->get_shs_recov_asuransi($dt, 'recov_awal')->row_array();
        $sh_as3 = $this->M_home->get_shs_recov_asuransi($dt, 'nominal')->row_array();

        $subro_as = $sh_as1['tot_subrogasi'];
        $shs_as   = ($sh_as1['tot_subrogasi']) - $sh_as3['tot_nominal_as'];
        $rec_as   = $sh_as3['tot_nominal_as'];

        $data = ['judul'                => 'Dashboard',
                 'id_cabang_as'         => $id_cabang_as,
                 'data_ver'             => $kar,
                //  'bank'                 => $this->M_home->get_data('m_bank')->result_array(),
                 'bank'                 => $bnk,
                 'asuransi'             => $asr,
                 'verifikator'          => $this->M_ots->get_verifikator_new($id_cabang_as)->result_array(),
                 'data_recov_baik'      => $this->M_home->get_data_recov($dt, 'baik'),
                 'data_recov_kurang'    => $this->M_home->get_data_recov($dt, 'kurang'),
                 'recov_cabang_b'       => $this->M_home->get_recov_cabang($dt, 'terbesar'),
                 'recov_cabang_k'       => $this->M_home->get_recov_cabang($dt, 'terkecil'),
                 'total_noa'            => $this->M_home->get_total_noa($dt, 'tot_noa')->num_rows(),
                 'tot_potensial'        => $this->M_noa->get_tot_r_noa($dt, 'potensial')->num_rows(),
                 'tot_non_potensial'    => $this->M_noa->get_tot_r_noa($dt, 'non_potensial')->num_rows(),
                 'tot_subro_as'         => "Rp. ".number_format($subro_as, '2',',','.'),
                 'tot_recov'            => "Rp. ".number_format($rec_as, '2',',','.'),
                 'tot_shs'              => "Rp. ".number_format($shs_as,'2',',','.'),
                //  'total_sdh_ots'        => $this->M_home->get_total_noa($dt, 'sdh_ots')->num_rows(),
                 'total_sdh_ots'        => $this->M_home->get_total_noa_2($dt)->num_rows(),
                 'pie_asuransi'         => $this->M_home->get_recov_asuransi_pie($dt)->result_array(),
                 'pie_bank'             => $this->M_home->get_recov_bank_pie($dt)->result_array(),
                 'tagihan_bank'         => $tag_bank,
                 'recov_bank'           => $rec_bank,
                 'shs_asuransi'         => $shs_as,
                 'recov_asuransi'       => $rec_as,
                 'bulan'                => $bln,
                 'kanwil_spk'           => $this->M_home->get_kanwil_spk($korwil)->result_array(),
                 'id_spk'               => $id_spk,
                 'akses'                => $akses,
                 'nm_cabang'            => $this->M_home->cari_data('m_cabang_asuransi', array('id_cabang_asuransi' => $id_cabang_as))->row_array(),
                 'no_spk'               => $this->M_home->cari_data('spk', array('id_spk' => $id_spk))->row_array(),
                 'level'                => $level_a,
                 'spk'                  => $this->M_home->get_data('spk')->result_array()
                ];

        if ($akses == 'kanwil_asuransi') {
            if ($id_spk != '') {
                $this->template->load('layout/template', 'V_home_new', $data);
            } else {
                $this->template->load('layout/template', 'V_home_kanwil', $data);
            }
        } else {
            $this->template->load('layout/template', 'V_home_new', $data);
        }

    }

    public function filter_home()
    {
        $lvl = $this->session->userdata('level');

        if ($lvl == 10) {
            $v = 'syariah';
        } elseif ($lvl == 13) {
            $v = 'asuransi';
        } else {
            $v = "";
        }

        $id_spk         = $this->input->post('id_spk');
        
        $id_cabang_as   = $this->session->userdata('cabang_as'); 
        
        // $id_cabang_as   = $this->input->post('id_cabang_as');

        if ($id_spk == '' && $id_cabang_as == '') {
            $level = 14;
            $v     = '';
        } else {
            $level = 13;
            $v     = 'asuransi';
        }

        $dt  = ['asuransi'          => $this->input->post('asuransi'),
                'cabang_asuransi'   => $this->input->post('cabang_asuransi'),
                'bank'              => $this->input->post('bank'),
                'cabang_bank'       => $this->input->post('cabang_bank'),
                'capem_bank'        => $this->input->post('capem_bank'),
                'tanggal_awal'      => $this->input->post('tgl_awal'),
                'tanggal_akhir'     => $this->input->post('tgl_akhir'),
                'verifikator'       => $this->input->post('verifikator'),
                'level'             => $v,
                'spk'               => $this->input->post('spk_manager')
        ];

        $kar = $this->M_home->get_karyawan_default($dt)->row_array();

        if (!defined('UPLOAD_DIR')) define('UPLOAD_DIR', 'assets/img/');

        $name_file = 'pp.png';
        
        $image_base64 = base64_decode($kar['file_foto']);
        $file = UPLOAD_DIR . $name_file;
        file_put_contents($file, $image_base64);

        $bln = array();

        $skrg = date("Y-m");

        $bln_s = date("Y-m", strtotime("$skrg +1 months"));

        for ($i=1; $i <= 5; $i++) { 
            $a = date('Y-m', strtotime("$skrg -$i months"));
            array_push($bln, array('id' => $a));
        }

        $cr = $this->M_home->cari_id_asuransi($id_cabang_as)->row_array();

        if ($v == "syariah") {
            $asr = $this->M_home->cari_data('m_asuransi', array('id_asuransi' => 2))->result_array();
            $bnk = $this->M_home->get_data_bank('m_bank', 'x', 'syariah')->result_array();
        } elseif ($v == 'asuransi') {
            $asr = $this->M_home->cari_data('m_asuransi', array('id_asuransi' => $cr['id_asuransi']))->result_array();
            $bnk = $this->M_home->get_data_bank('m_bank', $id_cabang_as, 'asuransi')->result_array();
        } else {
            $asr = $this->M_home->get_data('m_asuransi')->result_array();
            $bnk = $this->M_home->get_data_order('m_bank', 'bank', 'asc')->result_array();
        }

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

        $tag_b1 = $this->M_home->get_tagihan_recov_bank($dt, 'besar_klaim')->row_array();
        $tag_b2 = $this->M_home->get_tagihan_recov_bank($dt, 'recov_awal')->row_array();
        $tag_b3 = $this->M_home->get_tagihan_recov_bank($dt, 'nominal')->row_array();

        $tag_bank = ($tag_b1['tot_besar_klaim']) - $tag_b3['tot_nominal_bank'];
        $rec_bank = $tag_b3['tot_nominal_bank'];

        $sh_as1 = $this->M_home->get_shs_recov_asuransi($dt, 'subro')->row_array();
        $sh_as2 = $this->M_home->get_shs_recov_asuransi($dt, 'recov_awal')->row_array();
        $sh_as3 = $this->M_home->get_shs_recov_asuransi($dt, 'nominal')->row_array();

        $subro_as = $sh_as1['tot_subrogasi'];
        $shs_as   = ($sh_as1['tot_subrogasi']) - $sh_as3['tot_nominal_as'];
        $rec_as   = $sh_as3['tot_nominal_as'];

        $data = ['judul'                => 'Dashboard',
                 'id_cabang_as'         => $id_cabang_as,
                 'data_ver'             => $kar,
                 'bank'                 => $bnk,
                 'asuransi'             => $asr,
                 'verifikator'          => $this->M_ots->get_verifikator_new()->result_array(),
                 'data_recov_baik'      => $this->M_home->get_data_recov($dt, 'baik'),
                 'data_recov_kurang'    => $this->M_home->get_data_recov($dt, 'kurang'),
                 'recov_cabang_b'       => $this->M_home->get_recov_cabang($dt, 'terbesar'),
                 'recov_cabang_k'       => $this->M_home->get_recov_cabang($dt, 'terkecil'),
                 'total_noa'            => $this->M_home->get_total_noa($dt, 'tot_noa')->num_rows(),
                //  'total_sdh_ots'        => $this->M_home->get_total_noa($dt, 'sdh_ots')->num_rows(),
                'tot_potensial'        => $this->M_noa->get_tot_r_noa($dt, 'potensial')->num_rows(),
                'tot_non_potensial'    => $this->M_noa->get_tot_r_noa($dt, 'non_potensial')->num_rows(),
                'tot_subro_as'      => "Rp. ".number_format($subro_as, '2',',','.'),
                'tot_recov'         => "Rp. ".number_format($rec_as, '2',',','.'),
                'tot_shs'           => "Rp. ".number_format($shs_as,'2',',','.'),
                 'total_sdh_ots'        => $this->M_home->get_total_noa_2($dt)->num_rows(),
                 'pie_asuransi'         => $this->M_home->get_recov_asuransi_pie($dt)->result_array(),
                 'pie_bank'             => $this->M_home->get_recov_bank_pie($dt)->result_array(),
                 'tagihan_bank'         => $tag_bank,
                 'recov_bank'           => $rec_bank,
                 'shs_asuransi'         => $shs_as,
                 'recov_asuransi'       => $rec_as,
                 'bulan'                => $bln,
                 'asuransi'             => $this->input->post('asuransi'),
                 'cabang_asuransi'      => $this->input->post('cabang_asuransi'),
                 'bank'                 => $this->input->post('bank'),
                 'cabang_bank'          => $this->input->post('cabang_bank'),
                 'capem_bank'           => $this->input->post('capem_bank'),
                 'tanggal_awal'         => $this->input->post('tgl_awal'),
                 'tanggal_akhir'        => $this->input->post('tgl_akhir'),
                 'verifikator'          => $this->input->post('verifikator'),
                 'level'                => $level,
                 'spk_manager'          => $dt['spk']
                ];

        $this->load->view('V_home_filter', $data);
    }

    public function tes()
    {
        // $bln = array();

        // $skrg = date("Y-m");

        // $bln_s = date("Y-m", strtotime("$skrg -1 months"));

        // for ($i=1; $i <= 5; $i++) { 
        //     $a = date('Y-m', strtotime("$bln_s -$i months"));
        //     array_push($bln, $a);
        // }

        $bln = array();

        $skrg = date("Y-m");

        $bln_s = date("Y-m", strtotime("$skrg +1 months"));

        for ($i=1; $i <= 5; $i++) { 
            $a = date('Y-m', strtotime("$skrg -$i months"));
            array_push($bln, array('id' => $a));
        }

        print_r($bln);
    }

    // ambil cabang asuransi
    public function ambil_cabang_asuransi()
    {
        $id_asuransi    = $this->input->post('id_asuransi');
        $id_cabang_as   = $this->input->post('id_cabang_as');

        if ($id_asuransi == "a") {
            $option = "<option value='a'>-- Pilih Cabang Asuransi --</option>";
        } else {
            $list_as = $this->M_home->cari_cab_asuransi($id_asuransi, $id_cabang_as)->result_array();

            $option = "<option value='a'>-- Pilih Cabang Asuransi --</option>";

            foreach ($list_as as $a) {
                $option .= "<option value='".$a['id_cabang_asuransi']."'>".$a['cabang_asuransi']."</option>";
            }
        }
        $data = ['cabang_as'    => $option];

        echo json_encode($data);
        
    }

    // menampilkan cabang bank 
    public function ambil_cabang_bank()
    {
        $id_bank = $this->input->post('id_bank');
        
        if ($id_bank == "a") {
            $option = "<option value='a'>-- Pilih Cabang Bank --</option>";
        } else {
            $list_bank = $this->M_home->cari_cab_bank($id_bank)->result_array();

            $option = "<option value='a'>-- Pilih Cabang Bank --</option>";

            foreach ($list_bank as $a) {
                $option .= "<option value='".$a['id_cabang_bank']."'>".$a['cabang_bank']."</option>";
            }
        }

        $option1 = "<option value='a'>-- Pilih Capem Bank --</option>";

        $data = ['cabang_bank'    => $option, 'option1' => $option1];

        echo json_encode($data);
    }

    // menampilkan capem bank
    public function ambil_capem_bank()
    {
        $id_cabang_bank = $this->input->post('id_cabang_bank');

        if ($id_cabang_bank == "a") {
            $option = "<option value='a'>-- Pilih Capem Bank --</option>";
        } else {
            $list_cap_b = $this->M_home->cari_cap_bank($id_cabang_bank)->result_array();

            $option = "<option value='a'>-- Pilih Capem Bank --</option>";

            foreach ($list_cap_b as $a) {
                $option .= "<option value='".$a['id_capem_bank']."'>".$a['capem_bank']."</option>";
            }
        }
        $data = ['capem_bank'    => $option];

        echo json_encode($data);
    }

    // menampilkan verifikator
    public function ambil_verifikator()
    {
        $id_capem_bank = $this->input->post('id_capem_bank');
        
        if ($id_capem_bank == "a") {

            $ver = $this->M_ots->get_verifikator_new()->result_array();

            $option = "<option value='a'>-- Pilih Verfikator --</option>";

            foreach ($ver as $a) {
                $option .= "<option value='".$a['id_karyawan']."'>".$a['nama_lengkap']."</option>";
            }
            
        } else {
            $list_ver = $this->M_home->cari_ver($id_capem_bank)->result_array();

            $option = "<option value='a'>-- Pilih Verfikator --</option>";

            foreach ($list_ver as $a) {
                $option .= "<option value='".$a['id_karyawan']."'>".$a['nama_lengkap']."</option>";
            }
        }
        
        $data = ['ver'    => $option];

        echo json_encode($data);
    }
}