<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Katalog
 */
class R_proses extends CI_controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
        $this->load->model(array('M_noa', 'M_proses','M_recov'));
	}
	
    public function index()
    {
        if (isset($_POST['cari'])) {
            $tgl_awal   = $this->input->post('tgl_awal');
            $tgl_akhir  = $this->input->post('tgl_akhir');
            $tindakan   = $this->input->post('tindakan');
            $bank       = $this->input->post('bank');

            $data = [
                'proses'        => 'aktif',
                'bank'          => $this->M_noa->get_bank(),
                'tindakan'      => $this->M_proses->get_tindakan(),
                'data_r_proses' => $this->M_proses->get_cari_data_r_proses($tgl_awal,$tgl_akhir,$tindakan,$bank),
                'wilayah_ver'   => $this->M_recov->get_data_wilayah_ver(),
                'p_tgl_awal'    => $tgl_awal,
                'p_tgl_akhir'   => $tgl_akhir,
                'p_tindakan'    => $tindakan,
                'p_bank'        => $bank
            ];

            // tanggal awal terisi
            if (!empty($tgl_awal) && empty($tgl_akhir) && empty($tindakan) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b></div>');
            }
            // tanggal akhir terisi
            elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($tindakan) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
            }
            // tindakan terisi
            elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($tindakan) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tindakan Hukum <b>'.$tindakan.'</b></div>');
            }
            // bank terisi
            elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($tindakan) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Bank <b>'.$bank.'</b></div>');
            }
            // tanggal awal dengan tanggal akhir
            elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($tindakan) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
            }
            // tanggal awal dengan tindakan
            elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($tindakan) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan Tindakan Hukum <b>'.$tindakan.'</b></div>');
            }
            // tanggal awal dengan bank
            elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($tindakan) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan <b>'.$bank.'</b></div>');
            }
            // tanggal akhir dengan tindakan
            elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($tindakan) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Tindakan Hukum <b>'.$tindakan.'</b></div>');
            }
            // tanggal akhir dengan bank
            elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($tindakan) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan <b>'.$bank.'</b></div>');
            }
            // tindakan dengan bank
            elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($tindakan) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tindakan Hukum <b>'.$tindakan.'</b> dengan <b>'.$bank.'</b></div>');
            }
            // tindakan dengan bank
            elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($tindakan) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> Tindakan Hukum <b>'.$tindakan.'</b> dengan <b>'.$bank.'</b></div>');
            }
            // tindakan dengan bank
            elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($tindakan) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_akhir).'</b> Tindakan Hukum <b>'.$tindakan.'</b> dengan <b>'.$bank.'</b></div>');
            }
            // tanggal akhir sampai tanggal akhir, tindakan
            elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($tindakan) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Tindakan Hukum <b>'.$tindakan.'</b></div>');
            }
            // tanggal akhir sampai tanggal akhir, dengan bank
            elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($tindakan) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dari <b>'.$bank.'</b></div>');
            }
            // tanggal akhir sampai tanggal akhir, dengan tindakan dan bank
            elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($tindakan) && !empty($bank)) {
                 $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Tindakan Hukum <b>'.$tindakan.'</b> dari <b>'.$bank.'</b></div>');
            }

            $this->template->load('template','proses/v_proses', $data);
            
        } elseif (isset($_POST['unduh'])) {
            $tgl_awal   = $this->input->post('tgl_awal');
            $tgl_akhir  = $this->input->post('tgl_akhir');
            $tindakan   = $this->input->post('tindakan');
            $bank       = $this->input->post('bank');

            $nama_bank  = strtoupper($bank);

            if ((empty($tgl_awal)) && (empty($tgl_akhir)) && (empty($tindakan)) && (empty($bank))) {
                $all = "LAPORAN KESELURUHAN PENGELOLAAN SUBROGASI DAN RECOVERY";
            } else {
                $all = "LAPORAN PENGELOLAAN SUBROGASI DAN RECOVERY $nama_bank";
            }

            $data = [
                'judul'         => $all,
                'data_r_proses' => $this->M_proses->get_cari_data_r_proses($tgl_awal,$tgl_akhir,$tindakan,$bank),
                'jml_r_proses'  => $this->M_proses->get_jml_r_proses($tgl_awal,$tgl_akhir,$tindakan,$bank),
                'tgl_awal'      => $tgl_awal,
                'tgl_akhir'     => $tgl_akhir,
                'tindakan'      => $tindakan,
                'bank'          => $bank,
                'wilayah_ver'   => $this->M_recov->get_data_wilayah_ver(),
                'kondisi'       => 'lihat'
            ];

            $this->load->view('proses/lihat_print', $data);

        } elseif (isset($_POST['wilayah'])) {
            $kalimat = $this->input->post('wil');

            if (!empty($kalimat)) {
                $array = explode("&&&", $kalimat);
                $ver    = $array[0];
                $capem  = $array[1];

                $data = [
                    'proses'        => 'aktif',
                    'bank'          => $this->M_noa->get_bank(),
                    'tindakan'      => $this->M_proses->get_tindakan(),
                    'data_r_proses' => $this->M_proses->get_data_r_proses(),
                    'wilayah_ver'   => $this->M_recov->get_data_wilayah_ver(),
                    'data_ver'      => $this->M_proses->get_cari_data_wilayah_ver($ver,$capem),
                    'wil'           => $kalimat,
                    'p_tgl_awal'    => '',
                    'p_tgl_akhir'   => '',
                    'p_tindakan'    => '',
                    'p_bank'        => ''
                ];

                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Verifikator <b>'.$ver.'</b> dari capem bank <b>'.$capem.'</b> </div>');

            } else {
                
                $data = [
                    'proses'        => 'aktif',
                    'bank'          => $this->M_noa->get_bank(),
                    'tindakan'      => $this->M_proses->get_tindakan(),
                    'data_r_proses' => $this->M_proses->get_data_r_proses(),
                    'wilayah_ver'   => $this->M_recov->get_data_wilayah_ver(),
                    'wil'           => $kalimat,
                    'p_tgl_awal'    => '',
                    'p_tgl_akhir'   => '',
                    'p_tindakan'    => '',
                    'p_bank'        => ''
                ];

                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Pilih dahulu Wilayah Verifikator </div>');
            }

            $this->template->load('template','proses/v_proses', $data);

        } else {
            $data = [
                'proses'        => 'aktif',
                'bank'          => $this->M_noa->get_bank(),
                'tindakan'      => $this->M_proses->get_tindakan(),
                'data_r_proses' => $this->M_proses->get_data_r_proses(),
                'wilayah_ver'   => $this->M_recov->get_data_wilayah_ver(),
                'p_tgl_awal'    => '',
                'p_tgl_akhir'   => '',
                'p_tindakan'    => '',
                'p_bank'        => ''
            ];

            $this->template->load('template','proses/v_proses', $data);
        }
        
    }

    public function unduh_pdf()
    {
        $tgl_awal   = $this->input->post('tgl_awal');
        $tgl_akhir  = $this->input->post('tgl_akhir');
        $tindakan   = $this->input->post('tindakan');
        $bank       = $this->input->post('bank');

        $nama_bank  = strtoupper($bank);

        if ((empty($tgl_awal)) && (empty($tgl_akhir)) && (empty($tindakan)) && (empty($bank))) {
            $all = "LAPORAN KESELURUHAN PENGELOLAAN SUBROGASI DAN RECOVERY";
        } else {
            $all = "LAPORAN PENGELOLAAN SUBROGASI DAN RECOVERY $nama_bank";
        }

        $data = [
            'judul'         => $all,
            'data_r_proses' => $this->M_proses->get_cari_data_r_proses($tgl_awal,$tgl_akhir,$tindakan,$bank),
            'jml_r_proses'  => $this->M_proses->get_jml_r_proses($tgl_awal,$tgl_akhir,$tindakan,$bank),
            'tgl_awal'      => $tgl_awal,
            'tgl_akhir'     => $tgl_akhir,
            'tindakan'      => $tindakan,
            'bank'          => $bank,
            'report'        => 'proses'
        ];

       if (isset($_POST['pdf'])) {
            $data['kondisi'] = 'pdf';
            $this->template->load('template_pdf', 'proses/lihat_print', $data);
        } else {
            $data['kondisi'] = 'excel';
            $this->template->load('template_excel', 'proses/lihat_print', $data);
        }
    }

}