<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class R_ots extends CI_controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
        $this->load->model(array('M_ots','M_noa','M_home', 'M_recov','M_eks_asset'));
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

        $data = ['judul'       => 'R-ots',
                'id_cabang_as' => $id_cabang_as,
                'bank'         => $bnk,
                'asuransi'     => $asr,
                'verifikator'  => $this->M_ots->get_verifikator_new()->result_array(),
                'level'        => $level,
                'akses'        => $akses,
                'nm_cabang'    => $this->M_home->cari_data('m_cabang_asuransi', array('id_cabang_asuransi' => $id_cabang_as))->row_array(),
                'no_spk'       => $this->M_home->cari_data('spk', array('id_spk' => $id_spk))->row_array(),
                'spk'          => $this->M_ots->cari_data('spk', array('status' => 1))->result_array()
                ];

        $this->template->load('layout/template', 'ots/V_ots_new', $data);
        
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
                $sp    = $this->M_ots->cari_data('spk', array('id_spk' => $dt['spk']))->row_array();
                $spk   = $sp['no_spk'];
            }
        } else {
            $spk    = ""; 
        }

        $all = "LAPORAN PENGELOLAAN SUBROGASI DAN RECOVERY";

        $data = [
                'judul'             => $all,
                'level'             => $this->uri->segment(4),
                'data_r_ots'        => $this->M_ots->get_cari_data_unduh_r_ots($dt)->result_array(),
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
                'report'            => 'OTS',
                'no_spk'            => $spk,
                'id_spk'            => $dt['spk']
            ];

        if ($aksi == 'lihat') {
            $this->load->view('ots/lihat_print', $data);
        } else {
            if (isset($_POST['pdf'])) {
                $data['kondisi'] = 'pdf';
                $this->template->load('template_pdf', 'ots/lihat_print', $data);
            } else {
                $data['kondisi'] = 'excel';
                $this->template->load('template_excel', 'ots/lihat_print', $data);
            }
        }
        
    }

    // menampilkan data ots dengan dataTable
    public function tampil_data_ots()
    {
        $level    = $this->uri->segment(3);
        
        $nama = ['asuransi'         => $this->input->post('asuransi'),
                'cabang_asuransi'   => $this->input->post('cabang_asuransi'),
                'bank'              => $this->input->post('bank'),
                'cabang_bank'       => $this->input->post('cabang_bank'),
                'capem_bank'        => $this->input->post('capem_bank'),
                'tanggal_awal'      => $this->input->post('tanggal_awal'),
                'tanggal_akhir'     => $this->input->post('tanggal_akhir'),
                'verifikator'       => $this->input->post('verifikator'),
                'status'            => $this->input->post('status'),
                'spk'               => $this->input->post('spk'),
                'level'             => $level
        ];

        $list = $this->M_ots->get_data_ots($nama);

        $data = array();

        $no   = $this->input->post('start');

        foreach ($list as $o) {
            $no++;
            $tbody = array();

            if ($o['potensial'] == 1) {
                $status = "<div align='center'><h4><span class='badge badge-info badge-pill'>Potensial</span></h4></div>";
            } else {
                if ($o['potensial'] == 0) {
                    $status = "<div align='center'><h4><span class='badge badge-danger badge-pill'>Non Potensial</span></h4></div>";
                } else {
                    $status = '';
                } 
            }

            $tbody[]    = "<div align='center'>".$no."</div>";
            $tbody[]    = $o['nama_debitur'];
            $tbody[]    = $o['no_klaim'];
            $tbody[]    = $o['bank'];
            $tbody[]    = $o['cabang_bank'];
            $tbody[]    = $o['capem_bank'];
            $tbody[]    = $o['alamat_awal'];
            if ($this->cabang_as == '') {
                $tbody[]    = $o['cabang_asuransi'];
            }
            $tbody[]    = $o['keterangan'];
            $tbody[]    = $status;
            $data[]     = $tbody;
        }

        $output = [ "draw"             => $_POST['draw'],
                    "recordsTotal"     => $this->M_ots->jumlah_semua_ots($nama),
                    "recordsFiltered"  => $this->M_ots->jumlah_filter_ots($nama),   
                    "data"             => $data
                ];

        echo json_encode($output);
        
    }

    // menampilkan data ots summary
    public function tampil_data_ots_summary()
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
        
        $list = $this->M_recov->get_data_recov_as($dt);

        $data = array();

        $no   = $this->input->post('start');

        foreach ($list as $o) {
            $no++;
            $tbody = array();      

            $cr = $this->M_recov->get_total_total($o['id_karyawan'])->num_rows();

            if ($o['noa_kelolaan'] == 0) {
                $persen_ots = 0;
            } else {
                $persen_ots = ($cr / $o['noa_kelolaan']) * 100;
            }

            $recov_as   = $o['recoveries_awal_as'] + $o['tot_recov_as'];

            $tbody[]    = "<div align='center'>".$no."</div>";
            $tbody[]    = $o['nama_lengkap'];
            $tbody[]    = "<div align='center'>".$o['noa_kelolaan']."</div>";
            $tbody[]    = "<div align='center'>".$cr."</div>";
            $tbody[]    = "<div align='right'>".number_format($persen_ots,'2',',','.')." %</div>";
            $tbody[]    = "<div align='right'>Rp. ".number_format($recov_as,'0','.','.')."</div>";
            $data[]     = $tbody;
        }

        $output = [ "draw"             => $_POST['draw'],
                    "recordsTotal"     => $this->M_recov->jumlah_semua_recov_as($dt),
                    "recordsFiltered"  => $this->M_recov->jumlah_filter_recov_as($dt),   
                    "data"             => $data
                ];

        echo json_encode($output);
        
    }

}