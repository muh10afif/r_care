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
        $this->load->model(array('M_noa','M_ots'));
	}
	
    public function index()
    {
        if (isset($_POST['cari'])) {
            $tgl_awal   = $this->input->post('tgl_awal');
            $tgl_akhir  = $this->input->post('tgl_akhir');
            $potensial  = $this->input->post('potensial');
            $bank       = $this->input->post('bank');

            $data = [
                'noa'            => 'aktif',
                'judul'          => 'NOA',
                'potensial'      => $this->M_noa->get_status_debitur(),
                'bank'           => $this->M_noa->get_bank(),
                'data_r_noa'     => $this->M_noa->get_cari_data_r_noa($tgl_awal,$tgl_akhir,$potensial,$bank),
                'tot_noa'        => $this->M_noa->get_total('debitur', $tgl_awal,$tgl_akhir,$potensial,$bank),
                'sudah_ots'      => $this->M_noa->get_total('ots', $tgl_awal,$tgl_akhir,$potensial,$bank),
                'status_pot'     => $this->M_noa->get_status_pot('Potensial', $tgl_awal,$tgl_akhir,$potensial,$bank),
                'status_non_pot' => $this->M_noa->get_status_pot('Non Potensial', $tgl_awal,$tgl_akhir,$potensial,$bank),
                'tot_subrogasi'  => $this->M_noa->get_tot_subrogasi($tgl_awal,$tgl_akhir,$potensial,$bank),
                'denda'          => $this->M_noa->get_tot_denda($tgl_awal,$tgl_akhir,$potensial,$bank),
                'bunga'          => $this->M_noa->get_tot_bunga($tgl_awal,$tgl_akhir,$potensial,$bank),
                'tot_recov'      => $this->M_noa->get_tot_recov($tgl_awal,$tgl_akhir,$potensial,$bank),
                'p_tgl_awal'     => $tgl_awal,
                'p_tgl_akhir'    => $tgl_akhir,
                'p_potensial'    => $potensial,
                'p_bank'         => $bank
            ];

            // tanggal awal terisi
            if (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b></div>');
            }
            // tanggal akhir terisi
            elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($potensial) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
            }
            // potensial terisi
            elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($potensial) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Status Debitur <b>'.$potensial.'</b></div>');
            }
            // bank terisi
            elseif (empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Bank <b>'.$bank.'</b></div>');
            }
            // tanggal awal dengan tanggal akhir
            elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($potensial) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b></div>');
            }
            // tanggal awal dengan potensial
            elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($potensial) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan status debitur <b>'.$potensial.'</b></div>');
            }
            // tanggal awal dengan bank
            elseif (!empty($tgl_awal) && empty($tgl_akhir) && empty($potensial) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> dengan <b>'.$bank.'</b></div>');
            }
            // tanggal akhir dengan potensial
            elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($potensial) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan status debitur <b>'.$potensial.'</b></div>');
            }
            // tanggal akhir dengan bank
            elseif (empty($tgl_awal) && !empty($tgl_akhir) && empty($potensial) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan <b>'.$bank.'</b></div>');
            }
            // potensial dengan bank
            elseif (empty($tgl_awal) && empty($tgl_akhir) && !empty($potensial) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Status Debitur <b>'.$potensial.'</b> dengan <b>'.$bank.'</b></div>');
            }
            // potensial dengan bank
            elseif (!empty($tgl_awal) && empty($tgl_akhir) && !empty($potensial) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> Status Debitur <b>'.$potensial.'</b> dengan <b>'.$bank.'</b></div>');
            }
            // potensial dengan bank
            elseif (empty($tgl_awal) && !empty($tgl_akhir) && !empty($potensial) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_akhir).'</b> Status Debitur <b>'.$potensial.'</b> dengan <b>'.$bank.'</b></div>');
            }
            // tanggal akhir sampai tanggal akhir, potensial
            elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($potensial) && empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Status Debitur <b>'.$potensial.'</b></div>');
            }
            // tanggal akhir sampai tanggal akhir, dengan bank
            elseif (!empty($tgl_awal) && !empty($tgl_akhir) && empty($potensial) && !empty($bank)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dari <b>'.$bank.'</b></div>');
            }
            // tanggal akhir sampai tanggal akhir, dengan potensial dan bank
            elseif (!empty($tgl_awal) && !empty($tgl_akhir) && !empty($potensial) && !empty($bank)) {
                 $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-dismissible col-md-12"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-filter"></i>Menampilkan filter data Tanggal Awal Periode <b>'.tgl_indo($tgl_awal).'</b> sampai Tanggal Akhir Periode <b>'.tgl_indo($tgl_akhir).'</b> dengan Status Debitur <b>'.$potensial.'</b> dari <b>'.$bank.'</b></div>');
            }


            $this->template->load('template','noa/v_noa', $data);

        } elseif (isset($_POST['unduh'])) {
            $tgl_awal   = $this->input->post('tgl_awal');
            $tgl_akhir  = $this->input->post('tgl_akhir');
            $potensial  = $this->input->post('potensial');
            $bank       = $this->input->post('bank');

            $nama_bank  = strtoupper($bank);

            if ((empty($tgl_awal)) && (empty($tgl_akhir)) && (empty($potensial)) && (empty($bank))) {
                $all = "LAPORAN KESELURUHAN PENGELOLAAN SUBROGASI DAN RECOVERIES";
            } else {
                $all = "LAPORAN PENGELOLAAN SUBROGASI DAN RECOVERY $nama_bank";
            }

            $data = [
                'judul'             => $all,
                'data_r_noa'        => $this->M_noa->get_cari_print_data_r_noa($tgl_awal,$tgl_akhir,$potensial,$bank),
                'data_noa_jml'      => $this->M_noa->get_data_noa_jml($tgl_awal,$tgl_akhir,$potensial,$bank), 
                'data_pot'          => $this->M_noa->get_data_pot('Potensial',$tgl_awal,$tgl_akhir,$potensial,$bank), 
                'data_non_pot'      => $this->M_noa->get_data_pot('Non Potensial',$tgl_awal,$tgl_akhir,$potensial,$bank), 
                'jml_data_pot'      => $this->M_noa->get_tot_pot('Potensial',$tgl_awal,$tgl_akhir,$potensial,$bank),
                'jml_data_non_pot'  => $this->M_noa->get_tot_pot('Non Potensial',$tgl_awal,$tgl_akhir,$potensial,$bank),
                'tgl_awal'          => $tgl_awal,
                'tgl_akhir'         => $tgl_akhir,
                'potensial'         => $potensial,
                'bank'              => $bank,
                'kondisi'           => 'lihat'
            ];

            $this->load->view('noa/lihat_print', $data);

        } else {
           $data = [
                'noa'            => 'aktif',
                'judul'          => 'NOA',
                'potensial'      => $this->M_noa->get_status_debitur(),
                'bank'           => $this->M_noa->get_bank(),
                'data_r_noa'     => $this->M_noa->get_data_r_noa(),
                'tot_noa'        => $this->M_ots->get_total('debitur'),
                'sudah_ots'      => $this->M_ots->get_total('ots'),
                'status_pot'     => $this->M_noa->get_status_pot('Potensial', $tgl_awal = null,$tgl_akhir = null,$potensial = null,$bank = null),
                'status_non_pot' => $this->M_noa->get_status_pot('Non Potensial', $tgl_awal = null,$tgl_akhir = null,$potensial = null,$bank = null),
                'tot_subrogasi'  => $this->M_noa->get_tot_subrogasi($tgl_awal = null,$tgl_akhir = null,$potensial = null,$bank = null),
                'denda'          => $this->M_noa->get_tot_denda($tgl_awal = null,$tgl_akhir = null,$potensial = null,$bank = null),
                'bunga'          => $this->M_noa->get_tot_bunga($tgl_awal = null,$tgl_akhir = null,$potensial = null,$bank = null),
                'tot_recov'      => $this->M_noa->get_tot_recov($tgl_awal = null,$tgl_akhir = null,$potensial = null,$bank = null),
                'p_tgl_awal'     => '',
                'p_tgl_akhir'    => '',
                'p_potensial'    => '',
                'p_bank'         => ''
            ];

            $this->template->load('template','noa/v_noa', $data); 
        }

        
    }

    public function unduh_pdf()
    {
        $tgl_awal   = $this->input->post('tgl_awal');
        $tgl_akhir  = $this->input->post('tgl_akhir');
        $potensial  = $this->input->post('potensial');
        $bank       = $this->input->post('bank');

        $nama_bank  = strtoupper($bank);

        if ((empty($tgl_awal)) && (empty($tgl_akhir)) && (empty($potensial)) && (empty($bank))) {
            $all = "LAPORAN KESELURUHAN PENGELOLAAN SUBROGASI DAN RECOVERIES";
        } else {
            $all = "LAPORAN PENGELOLAAN SUBROGASI DAN RECOVERIES $nama_bank";
        }

        $data = [
            'judul'             => $all,
            'data_r_noa'        => $this->M_noa->get_cari_print_data_r_noa($tgl_awal,$tgl_akhir,$potensial,$bank),
            'data_noa_jml'      => $this->M_noa->get_data_noa_jml($tgl_awal,$tgl_akhir,$potensial,$bank), 
            'data_pot'          => $this->M_noa->get_data_pot('Potensial',$tgl_awal,$tgl_akhir,$potensial,$bank), 
            'data_non_pot'      => $this->M_noa->get_data_pot('Non Potensial',$tgl_awal,$tgl_akhir,$potensial,$bank), 
            'jml_data_pot'      => $this->M_noa->get_tot_pot('Potensial',$tgl_awal,$tgl_akhir,$potensial,$bank),
            'jml_data_non_pot'  => $this->M_noa->get_tot_pot('Non Potensial',$tgl_awal,$tgl_akhir,$potensial,$bank),
            'tgl_awal'          => $tgl_awal,
            'tgl_akhir'         => $tgl_akhir,
            'potensial'         => $potensial,
            'bank'              => $bank,  
            'report'            => 'NOA'
        ];

        if (isset($_POST['pdf'])) {
            $data['kondisi'] = 'pdf';
            $this->template->load('template_pdf', 'noa/lihat_print', $data);
        } else {
            $data['kondisi'] = 'excel';
            $this->template->load('template_excel', 'noa/lihat_print', $data);
        }
    }

}