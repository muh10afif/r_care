<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Katalog
 */
class R_eks_asset extends CI_controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
        $this->load->model(array('M_eks_asset', 'M_noa','M_recov'));
	}

    public function index()
    {
        if (isset($_POST['cari'])) {
            $tgl_awal       = $this->input->post('tgl_awal');
            $tgl_akhir      = $this->input->post('tgl_akhir');
            $jenis_asset    = $this->input->post('jenis_asset');
            $bank           = $this->input->post('bank');

            $data = [
                'asset'             => 'aktif',
                'judul'             => 'Eks Asset',
                'bank'              => $this->M_noa->get_bank(),
                'jenis_asset'       => $this->M_eks_asset->get_jenis_asset(),
                'data_r_eks_asset'  => $this->M_eks_asset->get_cari_data_r_eks_asset($tgl_awal,$tgl_akhir,$jenis_asset,$bank),
                'wilayah_ver'       => $this->M_recov->get_data_wilayah_ver(),
                'p_tgl_awal'        => $tgl_awal,
                'p_tgl_akhir'       => $tgl_akhir,
                'p_jns_asset'       => $jenis_asset,
                'p_bank'            => $bank
            ];

            // tanggal awal terisi
            if (!empty($tgl_awal) && empty($tgl_akhir) && empty($jenis_asset) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b></div>');
            }
            // tanggal akhir terisi
            elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($jenis_asset) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
            }
            // jenis_asset terisi
            elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($jenis_asset) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Jenis Asset <b>'.$jenis_asset.'</b></div>');
            }
            // bank terisi
            elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($jenis_asset) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Bank <b>'.$bank.'</b></div>');
            }
            // tanggal awal dengan tanggal akhir
            elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($jenis_asset) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
            }
            // tanggal awal dengan jenis_asset
            elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($jenis_asset) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan Jenis Asset <b>'.$jenis_asset.'</b></div>');
            }
            // tanggal awal dengan bank
            elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($jenis_asset) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan <b>'.$bank.'</b></div>');
            }
            // tanggal akhir dengan jenis_asset
            elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($jenis_asset) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Jenis Asset <b>'.$jenis_asset.'</b></div>');
            }
            // tanggal akhir dengan bank
            elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($jenis_asset) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan <b>'.$bank.'</b></div>');
            }
            // jenis_asset dengan bank
            elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($jenis_asset) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Jenis Asset <b>'.$jenis_asset.'</b> dengan <b>'.$bank.'</b></div>');
            }
            // jenis_asset dengan bank
            elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($jenis_asset) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> Jenis Asset <b>'.$jenis_asset.'</b> dengan <b>'.$bank.'</b></div>');
            }
            // jenis_asset dengan bank
            elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($jenis_asset) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_akhir).'</b> Jenis Asset <b>'.$jenis_asset.'</b> dengan <b>'.$bank.'</b></div>');
            }
            // tanggal awal sampai tanggal akhir, jenis_asset
            elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($jenis_asset) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Jenis Asset <b>'.$jenis_asset.'</b></div>');
            }
            // tanggal awal sampai tanggal akhir, dengan bank
            elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($jenis_asset) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dari <b>'.$bank.'</b></div>');
            }
            // tanggal akhir sampai tanggal akhir, dengan jenis_asset dan bank
            elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($jenis_asset) && !empty($bank)) {
                 $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Jenis Asset <b>'.$jenis_asset.'</b> dari <b>'.$bank.'</b></div>');
            }


            $this->template->load('template','eks_asset/v_eks_asset', $data);

        } elseif (isset($_POST['unduh'])) {
            $tgl_awal       = $this->input->post('tgl_awal');
            $tgl_akhir      = $this->input->post('tgl_akhir');
            $jenis_asset    = $this->input->post('jenis_asset');
            $bank           = $this->input->post('bank');

            $nama_bank  = strtoupper($bank);

            if ((empty($tgl_awal)) && (empty($tgl_akhir)) && (empty($jenis_asset)) && (empty($bank))) {
                $all = "LAPORAN KESELURUHAN PENGELOLAAN SUBROGASI DAN RECOVERY";
            } else {
                $all = "LAPORAN PENGELOLAAN SUBROGASI DAN RECOVERY $nama_bank";
            }

            $data = [
                'judul'             => $all,
                'data_r_eks_asset'  => $this->M_eks_asset->get_cari_data_r_eks_asset($tgl_awal,$tgl_akhir,$jenis_asset,$bank),
                'tgl_awal'          => $tgl_awal,
                'tgl_akhir'         => $tgl_akhir,
                'jenis_asset'       => $jenis_asset,
                'bank'              => $bank,
                'kondisi'           => 'lihat'
            ];

            $this->load->view('eks_asset/lihat_print', $data);

        } elseif (isset($_POST['wilayah'])) {
            
            $kalimat = $this->input->post('wil');

            if (!empty($kalimat)) {
                $array  = explode("&&&", $kalimat);
                $ver    = $array[0];
                $capem  = $array[1];
                
                $data = [
                    'asset'             => 'aktif',
                    'judul'             => 'Eks Asset',
                    'bank'              => $this->M_noa->get_bank(),
                    'jenis_asset'       => $this->M_eks_asset->get_jenis_asset(),
                    'data_r_eks_asset'  => $this->M_eks_asset->get_data_r_eks_asset(),
                    'wilayah_ver'       => $this->M_recov->get_data_wilayah_ver(),
                    'data_ver'          => $this->M_eks_asset->get_cari_data_wilayah_ver($ver,$capem),
                    'wil'               => $kalimat,
                    'p_tgl_awal'        => '',
                    'p_tgl_akhir'       => '',
                    'p_jns_asset'       => '',
                    'p_bank'            => ''
                ];

                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Verifikator <b>'.$ver.'</b> dari capem bank <b>'.$capem.'</b> </div>');

            } else {
                
                $data = [
                    'asset'             => 'aktif',
                    'judul'             => 'Eks Asset',
                    'bank'              => $this->M_noa->get_bank(),
                    'jenis_asset'       => $this->M_eks_asset->get_jenis_asset(),
                    'data_r_eks_asset'  => $this->M_eks_asset->get_data_r_eks_asset(),
                    'wilayah_ver'       => $this->M_recov->get_data_wilayah_ver(),
                    'wil'               => $kalimat,
                    'p_tgl_awal'        => '',
                    'p_tgl_akhir'       => '',
                    'p_jns_asset'       => '',
                    'p_bank'            => ''
                ];

                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Pilih dahulu Wilayah Verifikator </div>');

            }

            $this->template->load('template','eks_asset/v_eks_asset', $data);

        } else {
           $data = [
                'asset'             => 'aktif',
                'judul'             => 'Eks Asset',
                'bank'              => $this->M_noa->get_bank(),
                'jenis_asset'       => $this->M_eks_asset->get_jenis_asset(),
                'data_r_eks_asset'  => $this->M_eks_asset->get_data_r_eks_asset(),
                'wilayah_ver'       => $this->M_recov->get_data_wilayah_ver(),
                'p_tgl_awal'        => '',
                'p_tgl_akhir'       => '',
                'p_jns_asset'       => '',
                'p_bank'            => ''
            ];


            $this->template->load('template','eks_asset/v_eks_asset', $data);
        }

    }

    public function unduh_pdf()
    {
        $tgl_awal       = $this->input->post('tgl_awal');
        $tgl_akhir      = $this->input->post('tgl_akhir');
        $jenis_asset    = $this->input->post('jenis_asset');
        $bank           = $this->input->post('bank');

        $nama_bank  = strtoupper($bank);

        if ((empty($tgl_awal)) && (empty($tgl_akhir)) && (empty($jenis_asset)) && (empty($bank))) {
            $all = "LAPORAN KESELURUHAN PENGELOLAAN SUBROGASI DAN RECOVERY";
        } else {
            $all = "LAPORAN PENGELOLAAN SUBROGASI DAN RECOVERY $nama_bank";
        }

        $data = [
            'judul'             => $all,
            'data_r_eks_asset'  => $this->M_eks_asset->get_cari_data_r_eks_asset($tgl_awal,$tgl_akhir,$jenis_asset,$bank),
            'tgl_awal'          => $tgl_awal,
            'tgl_akhir'         => $tgl_akhir,
            'jenis_asset'       => $jenis_asset,
            'bank'              => $bank,
            'report'            => 'Eks Asset'
        ];

        if (isset($_POST['pdf'])) {
            $data['kondisi'] = 'pdf';
            $this->template->load('template_pdf', 'eks_asset/lihat_print', $data);
        } else {
            $data['kondisi'] = 'excel';
            $this->template->load('template_excel', 'eks_asset/lihat_print', $data);
        }
    }
}