<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class R_recov extends CI_controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Cek_login_lib');
		$this->cek_login_lib->logged_in();
		$this->load->model(array('M_noa','M_recov','M_ots', 'M_home','M_eks_asset'));
	}

    public function tes()
    {
        $kalimat = "Joni&&&KCU Bandung";
        $array = explode("&&&", $kalimat);
        echo $array[1];
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

        $d_tot = $this->M_recov->get_cari_data_wilayah_ver($dt);

        foreach ($d_tot as $d) {
            $tot_nominal    = $d['tot_nominal'];
            $crp            = $d['crp'];
            $potensi_recov  = $d['potensi_recov'];
        }

        $d_tot_b = $this->M_recov->get_cari_data_wilayah_ver_bank($dt);

        foreach ($d_tot_b as $db) {
            $tot_nominal_b    = $db['tot_nominal'];
            $crp_b            = $db['crp'];
            $potensi_recov_b  = $db['potensi_recov'];
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
            'judul' 		 => 'Recoveries',
            'id_cabang_as'   => $id_cabang_as,
            'bank'           => $bnk,
            'asuransi'       => $asr,
            'verifikator'    => $this->M_ots->get_verifikator_new()->result_array(),
            'tot_nominal'    => $tot_nominal,
            'crp'            => $crp,
            'potensi_recov'  => $potensi_recov,
            'tot_nominal_b'  => $tot_nominal_b,
            'crp_b'          => $crp_b,
            'potensi_recov_b'=> $potensi_recov_b,
            'level'          => $level,
            'akses'          => $akses,
            'nm_cabang'      => $this->M_home->cari_data('m_cabang_asuransi', array('id_cabang_asuransi' => $id_cabang_as))->row_array(),
            'no_spk'         => $this->M_home->cari_data('spk', array('id_spk' => $id_spk))->row_array(),
            'spk'            => $this->M_recov->cari_data('spk', array('status' => 1))->result_array()
        ];

        $this->template->load('layout/template','recov/V_recov', $data);
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

        $id_cabang_as = $this->session->userdata('cabang_as');

        $data = [
                'judul'             => $all,
                'id_cabang_as'      => $id_cabang_as,
                'level'             => $this->uri->segment(4),
                'data_r_noa'        => $this->M_recov->get_cari_print_data_r_recov($dt)->result_array(),
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
                'report'            => 'Recov',
                'id_spk'            => $dt['spk'],
                'spk'               => $spk
            ];

        if ($aksi == 'lihat') {
            $this->load->view('recov/lihat_print', $data);
        } else {
            if (isset($_POST['pdf'])) {
                $data['kondisi'] = 'pdf';
                $this->template->load('template_pdf', 'recov/lihat_print', $data);
            } else {
                $data['kondisi'] = 'excel';
                $this->template->load('template_excel', 'recov/lihat_print', $data);
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

        $d_tot = $this->M_recov->get_cari_data_wilayah_ver($dt);

        foreach ($d_tot as $d) {
            $tot_nominal        = $d['tot_nominal'];
            $crp                = $d['crp'];
            $potensi_recov      = $d['potensi_recov'];
        }

        $d_tot_b = $this->M_recov->get_cari_data_wilayah_ver_bank($dt);

        foreach ($d_tot_b as $db) {
            $tot_nominal_b    = $db['tot_nominal'];
            $crp_b            = $db['crp'];
            $potensi_recov_b  = $db['potensi_recov'];
        }

        $data = ['tot_nominal'      => "Rp. ".number_format($tot_nominal, '0', '.','.'),
                 'crp'              => number_format($crp,'2','.','.'),
                 'potensi_recov'    => "Rp. ".number_format($potensi_recov, '0', '.','.'),
                 'tot_nominal_b'    => "Rp. ".number_format($tot_nominal_b, '0', '.','.'),
                 'crp_b'            => number_format($crp_b,'2','.','.'),
                 'potensi_recov_b'  => "Rp. ".number_format($potensi_recov_b, '0', '.','.')
        ];

        echo json_encode($data);
    }

    // tampil data recov summary
    public function tampil_data_recov_as()
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

            if ($o['noa_kelolaan'] == 0) {
                $persen_ots = 0;
            } else {
                $persen_ots = ($o['jml_ots'] / $o['noa_kelolaan']) * 100;
            }

            $recov_as   = $o['recoveries_awal_as'] + $o['tot_recov_as'];

            $tbody[]    = "<div align='center'>".$no."</div>";
            $tbody[]    = $o['nama_lengkap'];
            $tbody[]    = "<div align='center'>".$o['noa_kelolaan']."</div>";
            $tbody[]    = "<div align='center'>".$o['jml_ots']."</div>";
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

    // tampil data recov
    public function tampil_data_recov()
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

        $id_cabang_as = $this->session->userdata('cabang_as');

        $list = $this->M_recov->get_data_recov($dt);

        $data = array();

        $no   = $this->input->post('start');

        if ($id_cabang_as != '') {

            foreach ($list as $o) {
                $no++;
                $tbody = array();      
    
                // $recov_as   = $o['recoveries_awal_asuransi'] + $o['tot_nominal_as'];
    
                $tbody[]    = "<div align='center'>".$no."</div>";
                $tbody[]    = $o['no_reff'];
                $tbody[]    = $o['no_klaim'];
                $tbody[]    = $o['nama_debitur'];
                $tbody[]    = $o['bank'];
                $tbody[]    = $o['capem_bank'];
                $tbody[]    = nice_date($o['tgl_bayar'], 'd-M-Y');
                $tbody[]    = "<div align='right'>".number_format($o['jml_bayar'],2,'.','.')."</div>";
                $tbody[]    = "<div align='right'>".number_format($o['tot_nominal_as'],2,'.','.')."</div>";
                $data[]     = $tbody;
            }
            
        } else {
            
            foreach ($list as $o) {
                $no++;
                $tbody = array();      
    
                $tbody[]    = "<div align='center'>".$no."</div>";
                $tbody[]    = $o['no_reff'];
                $tbody[]    = $o['no_klaim'];
                $tbody[]    = $o['nama_debitur'];
                $tbody[]    = $o['bank'];
                $tbody[]    = $o['capem_bank'];
                $tbody[]    = $o['cabang_asuransi'];
                $tbody[]    = nice_date($o['tgl_bayar'], 'd-M-Y');
                $tbody[]    = "<div align='right'>".number_format($o['jml_bayar'],2,',','.')."</div>";
                $tbody[]    = "<div align='right'>".number_format($o['tot_nominal_bank'],2,',','.')."</div>";
                $tbody[]    = "<div align='right'>".number_format($o['tot_nominal_as'],2,',','.')."</div>";
                $data[]     = $tbody;
            }

        }

        $output = [ "draw"             => $_POST['draw'],
                    "recordsTotal"     => $this->M_recov->jumlah_semua_recov($dt),
                    "recordsFiltered"  => $this->M_recov->jumlah_filter_recov($dt),   
                    "data"             => $data
                ];

        echo json_encode($output);
    }

    public function unduh_pdf()
    {
        $tgl_awal   = $this->input->post('tgl_awal');
        $tgl_akhir  = $this->input->post('tgl_akhir');
        $potensial  = $this->input->post('potensial');
        $bank       = $this->input->post('bank');

        $nama_bank  = strtoupper($bank);
        
        if ((empty($tgl_awal)) && (empty($tgl_akhir)) && (empty($potensial)) && (empty($bank))) {
            $all = "LAPORAN KESELURUHAN PENGELOLAAN SUBROGASI DAN RECOVERY";
        } else {
            $all = "LAPORAN PENGELOLAAN SUBROGASI DAN RECOVERY $nama_bank";
        }

        $data = [
            'judul'         => $all,
            'data_r_noa'    => $this->M_recov->get_cari_print_data_r_recov($tgl_awal,$tgl_akhir,$potensial,$bank),
            'tgl_awal'      => $tgl_awal,
            'tgl_akhir'     => $tgl_akhir,
            'potensial'     => $potensial,
            'bank'          => $bank,
            'report'        => 'recoveries'
        ];

        if (isset($_POST['pdf'])) {
            $data['kondisi'] = 'pdf';
            $this->template->load('template_pdf', 'recov/lihat_print', $data);
        } else {
            $data['kondisi'] = 'excel';
            $this->template->load('template_excel', 'recov/lihat_print', $data);
        }
    }
}