<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * HOME A-Care
 */
class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
        $this->load->model(array('M_home', 'M_ots'));
	}

    public function index()
    {

        if (isset($_POST['cari_verifikator'])) {
            $bank           = $this->input->post('bank');
            $tgl_awal       = $this->input->post('tgl_awal');
            $tgl_akhir      = $this->input->post('tgl_akhir');
            $verifikator    = $this->input->post('verifikator');

            if ($verifikator != 0) {
                $n_ver = $this->M_home->get_karyawan($verifikator, $bank);
            } else {
                $n_ver = [
                          'nama'        => '-',
                          'jml_recov'   => 0,
                          'jml_ots'     => 0
                        ];
            }

            $img = $this->M_home->get_karyawan($verifikator,$bank);

            if (!empty($img)) {
                define('UPLOAD_DIR', 'assets/img/');

                $name_file = 'pp.png';
                
                $image_base64 = base64_decode($img['file_foto']);
                $file = UPLOAD_DIR . $name_file;
                file_put_contents($file, $image_base64);
            }


            $data = [   'judul'             => 'Dashboard',
                        'home'              => 'aktif',
                        'data_ver'          => $n_ver,
                        'recov_cabang_b'    => $this->M_home->get_cari_recov_cabang('terbesar',$bank),
                        'recov_cabang_k'    => $this->M_home->get_cari_recov_cabang('terkecil',$bank),
                        'recov_capem_b'     => $this->M_home->get_cari_recov_capem('terbesar',$bank),
                        'recov_capem_k'     => $this->M_home->get_cari_recov_capem('terkecil',$bank),
                        'data_recov_buruk'  => $this->M_home->get_cari_data_recov('buruk',$bank),
                        'data_recov_baik'   => $this->M_home->get_cari_data_recov('baik',$bank),
                        'data_recov_cukup'  => $this->M_home->get_cari_data_recov_cukup($bank),
                        'data_bank'         => $this->M_home->get_bank()->result_array(),
                        'total_noa'         => $this->M_home->get_cari_total('debitur', $bank),
                        'total_ots'         => $this->M_home->get_cari_total('ots', $bank),
                        'total_shs_noa'     => $this->M_home->get_cari_shs_noa($bank),
                        'shs_sudah'         => $this->M_home->get_cari_shs_sudah($bank),
                        'verifikator'       => $this->M_home->get_cari_verifikator($bank)->result_array(),
                        'id_ver'            => $verifikator,
                        'id_bank'           => $bank,
                        'tgl_awal'          => $tgl_awal,
                        'tgl_akhir'         => $tgl_akhir,
                        'pie_cabang'        => $this->M_home->get_cari_recov_cabang_pie($bank, $tgl_awal, $tgl_akhir)->result_array()
                    ];

        } elseif (isset($_POST['cari_bulan'])) {

            $bank       = $this->input->post('bank');
            $tgl_awal   = $this->input->post('tgl_awal');
            $tgl_akhir  = $this->input->post('tgl_akhir');

            $verifikator = $this->input->post('verifikator');

            if ($verifikator != 0) {
                $n_ver = $this->M_home->get_karyawan($verifikator, $bank);
            } else {
                $n_ver = [
                          'nama'        => '-',
                          'jml_recov'   => 0,
                          'jml_ots'     => 0
                        ];
            }

            $img = $this->M_home->get_karyawan($verifikator,$bank);

            if (!empty($img)) {
                define('UPLOAD_DIR', 'assets/img/');

                $name_file = 'pp.png';
                
                $image_base64 = base64_decode($img['file_foto']);
                $file = UPLOAD_DIR . $name_file;
                file_put_contents($file, $image_base64);
            }

            $data = [
                'judul'             => 'Dashboard',
                'home'              => 'aktif',
                'data_ver'          => $n_ver,
                'recov_cabang_b'    => $this->M_home->get_cari_recov_cabang('terbesar',$bank),
                'recov_cabang_k'    => $this->M_home->get_cari_recov_cabang('terkecil',$bank),
                'recov_capem_b'     => $this->M_home->get_cari_recov_capem('terbesar',$bank),
                'recov_capem_k'     => $this->M_home->get_cari_recov_capem('terkecil',$bank),
                'data_recov_buruk'  => $this->M_home->get_cari_data_recov('buruk',$bank),
                'data_recov_baik'   => $this->M_home->get_cari_data_recov('baik',$bank),
                'data_recov_cukup'  => $this->M_home->get_cari_data_recov_cukup($bank),
                'data_bank'         => $this->M_home->get_bank()->result_array(),
                'total_noa'         => $this->M_home->get_cari_total('debitur', $bank),
                'total_ots'         => $this->M_home->get_cari_total('ots', $bank),
                'total_shs_noa'     => $this->M_home->get_cari_shs_noa($bank),
                'shs_sudah'         => $this->M_home->get_cari_shs_sudah($bank),
                'verifikator'       => $this->M_home->get_cari_verifikator($bank)->result_array(),
                'id_ver'            => $verifikator,
                'id_bank'           => $bank,
                'tgl_awal'          => $tgl_awal,
                'tgl_akhir'         => $tgl_akhir,
                'pie_cabang'        => $this->M_home->get_cari_recov_cabang_pie($bank, $tgl_awal, $tgl_akhir)->result_array()
            ];
            
        } elseif (isset($_POST['cari'])) {

            $bank       = $this->input->post('bank');
            $tgl_awal   = $this->input->post('tgl_awal');
            $tgl_akhir  = $this->input->post('tgl_akhir');

            $verifikator = $this->input->post('verifikator');

            if ($verifikator != 0) {
                $n_ver = $this->M_home->get_karyawan($verifikator, $bank);
            } else {
                $n_ver = [
                          'nama'        => '-',
                          'jml_recov'   => 0,
                          'jml_ots'     => 0
                        ];
            }

            $img = $this->M_home->get_karyawan($verifikator,$bank);

            if (!empty($img)) {
                define('UPLOAD_DIR', 'assets/img/');

                $name_file = 'pp.png';
                
                $image_base64 = base64_decode($img['file_foto']);
                $file = UPLOAD_DIR . $name_file;
                file_put_contents($file, $image_base64);
            }

            $data = [
                'judul'             => 'Dashboard',
                'home'              => 'aktif',
                'data_ver'          => $n_ver,
                'recov_cabang_b'    => $this->M_home->get_cari_recov_cabang('terbesar',$bank),
                'recov_cabang_k'    => $this->M_home->get_cari_recov_cabang('terkecil',$bank),
                'recov_capem_b'     => $this->M_home->get_cari_recov_capem('terbesar',$bank),
                'recov_capem_k'     => $this->M_home->get_cari_recov_capem('terkecil',$bank),
                'data_recov_buruk'  => $this->M_home->get_cari_data_recov('buruk',$bank),
                'data_recov_baik'   => $this->M_home->get_cari_data_recov('baik',$bank),
                'data_recov_cukup'  => $this->M_home->get_cari_data_recov_cukup($bank),
                'data_bank'         => $this->M_home->get_bank()->result_array(),
                'total_noa'         => $this->M_home->get_cari_total('debitur', $bank),
                'total_ots'         => $this->M_home->get_cari_total('ots', $bank),
                'total_shs_noa'     => $this->M_home->get_cari_shs_noa($bank),
                'shs_sudah'         => $this->M_home->get_cari_shs_sudah($bank),
                'verifikator'       => $this->M_home->get_cari_verifikator($bank)->result_array(),
                'id_ver'            => $verifikator,
                'id_bank'           => $bank,
                'tgl_awal'          => $tgl_awal,
                'tgl_akhir'         => $tgl_akhir,
                'pie_cabang'        => $this->M_home->get_cari_recov_cabang_pie($bank, $tgl_awal, $tgl_akhir)->result_array()
            ];
            
        } else {

            $kar = $this->M_home->get_karyawan_default();

            define('UPLOAD_DIR', 'assets/img/');

            $name_file = 'pp.png';
            
            $image_base64 = base64_decode($kar['file_foto']);
            $file = UPLOAD_DIR . $name_file;
            file_put_contents($file, $image_base64);

            $data = [
                'judul'             => 'Dashboard',
                'home'              => 'aktif',
                'data_ver'          => $kar,
                'recov_cabang_b'    => $this->M_home->get_recov_cabang('terbesar'),
                'recov_cabang_k'    => $this->M_home->get_recov_cabang('terkecil'),
                'recov_capem_b'     => $this->M_home->get_recov_capem('terbesar'),
                'recov_capem_k'     => $this->M_home->get_recov_capem('terkecil'),
                'data_recov_buruk'  => $this->M_home->get_data_recov('buruk'),
                'data_recov_baik'   => $this->M_home->get_data_recov('baik'),
                'data_recov_cukup'  => $this->M_home->get_data_recov_cukup(),
                'data_bank'         => $this->M_home->get_bank()->result_array(),
                'total_noa'         => $this->M_ots->get_total('debitur'),
                'total_ots'         => $this->M_ots->get_total('ots'),
                'total_shs_noa'     => $this->M_ots->get_shs_noa(),
                'shs_sudah'         => $this->M_ots->get_shs_sudah(),
                'verifikator'       => $this->M_ots->get_verifikator()->result_array(),
                'id_ver'            => '',
                'id_bank'           => '',
                'tgl_awal'          => '',
                'tgl_akhir'         => '',
                'pie_cabang'        => $this->M_home->get_recov_cabang_pie()->result_array()
            ];
        }

    	$this->template->load('template', 'v_home', $data);
    }


    /*public function get_data_cabang()
    {
        $kinerja = $this->input->post('kinerja');

         $data = $this->M_home->get_data_cabang_1($kinerja);

        echo json_encode($data);
    }*/
}