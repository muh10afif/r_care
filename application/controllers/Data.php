<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_data', 'M_home', 'M_ots'));
    }

    public function tes()
    {
        //echo $batas = date("Y-m-d", strtotime("- 1 month"));

        // echo Date("Y-m-d", strtotime("2013-01-01 +1 Month -1 Day"));// 2013-01-31

        // echo Date("Y-m-d", strtotime("2013-01-31 +1 Month -3 Day")); // 2013-02-28

        // echo Date("Y-m-d", strtotime("2013-01-31 +2 Month")); // 2013-03-31

        // echo Date("Y-m-d", strtotime("2013-01-31 +3 Month -1 Day")); // 2013-04-30

        // echo Date("Y-m-d", strtotime("2013-12-31 -1 Month -1 Day")); // 2013-11-30

        // echo Date("Y-m-d", strtotime("2013-12-31 -2 Month")); // 2013-10-31

        // echo Date("Y-m-d", strtotime("2013-12-31 -3 Month")); // 2013-10-01

        // echo Date("Y-m-d", strtotime("2013-12-31 -3 Month -1 Day")); // 2013-09-30

        $bln = "2019-09";

        echo Date("Y-m", strtotime("$bln -12 Month"));
    }
    

    // menampilkan halaman foto dokumen
    public function foto()
    {
        $data = ['foto'             => 'aktif',
                 'judul'            => 'Foto Dokumen',
                 'd_verifikator'    => $this->M_data->get_data_verifikator()->result_array(),
                 'bank'             => $this->M_home->get_data('m_bank')->result_array(),
                 'asuransi'         => $this->M_home->get_data('m_asuransi')->result_array(),
                 'verifikator'      => $this->M_ots->get_verifikator_new()->result_array(),
                 'spk'              => $this->M_home->cari_data('spk', array('status' => 1))->result_array()
                ];

        $this->template->load('layout/template', 'foto/V_tampil_ver', $data);
    }

    // menampilkan list debitur
    public function tampil_list_debitur_foto()
    {
        $datanya = ['asuransi'          => $this->input->post('asuransi'),
                    'cabang_asuransi'   => $this->input->post('cabang_asuransi'),
                    'bank'              => $this->input->post('bank'),
                    'cabang_bank'       => $this->input->post('cabang_bank'),
                    'capem_bank'        => $this->input->post('capem_bank'),
                    'tanggal_awal'      => $this->input->post('tanggal_awal'),
                    'tanggal_akhir'     => $this->input->post('tanggal_akhir'),
                    'verifikator'       => $this->input->post('verifikator'),
                    'spk'               => $this->input->post('spk')
        ];

        $list = $this->M_data->get_list_deb($datanya);

        $data = array();

        $no   = $this->input->post('start');

        foreach ($list as $o) {
            $no++;
            $tbody = array();

            // sisa hutang (bank)
            if ($o['tot_nominal_bank'] == 0) {
                $sisa_hutang = 0;
            } else {
                $sisa_hutang = ($o['besar_klaim'] - $o['recoveries_awal_bank']) / $o['tot_nominal_bank'];
            }

            // shs
            if ($o['tot_nominal_as'] == 0) {
                $shs = 0;
            } else {
                $shs         = ($o['subrogasi_as'] - $o['recoveries_awal_asuransi']) / $o['tot_nominal_as'];
            }      

            $tbody[]    = "<div align='center'>".$no."</div>";
            $tbody[]    = $o['id_care'];
            $tbody[]    = $o['no_klaim'];
            $tbody[]    = $o['nama_debitur'];
            $tbody[]    = $o['capem_bank'];
            $tbody[]    = $o['cabang_asuransi'];
            $tbody[]    = $o['alamat_deb'];
            $tbody[]    = $o['alamat'];
            $tbody[]    = "<div align='right'>Rp. ".number_format($sisa_hutang, '0', '.','.')."</div>";
            $tbody[]    = "<div align='right'>Rp. ".number_format($shs, '0', '.','.')."</div>";
            $tbody[]    = "<div align='right'>Rp. ".number_format($o['total_harga'], '0', '.','.')."</div>";
            $tbody[]    = $o['status_asset'];
            $tbody[]    = "<div align='center'><button type='button' class='btn btn-success detail-deb' data-id=".$o['id_debitur']."><i class='fas fa-image'></i> </button></div>";
            $data[]     = $tbody;
        }

        $output = [ "draw"             => $_POST['draw'],
                    "recordsTotal"     => $this->M_data->jumlah_semua_deb($datanya),
                    "recordsFiltered"  => $this->M_data->jumlah_filter_deb($datanya),   
                    "data"             => $data
                ];

        echo json_encode($output);
    }

    // menampilkan view detail debitur
    public function form_detail_deb()
    {
        $id_debitur = $this->input->post('id_debitur');

        $jns_dok = $this->M_data->get_jns_dok($id_debitur)->result_array();

        $o = $this->M_data->get_data_deb($id_debitur)->row_array();

        // sisa hutang (bank)
        if ($o['tot_nominal_bank'] == 0) {
            $sisa_hutang = 0;
        } else {
            $sisa_hutang = ($o['besar_klaim'] - $o['recoveries_awal_bank']) / $o['tot_nominal_bank'];
        }

        // shs
        if ($o['tot_nominal_as'] == 0) {
            $shs = 0;
        } else {
            $shs         = ($o['subrogasi_as'] - $o['recoveries_awal_asuransi']) / $o['tot_nominal_as'];
        }      

        $data = ['data_deb'     => $o,
                 'sisa_hutang'  => $sisa_hutang,
                 'shs'          => $shs,
                 'jns_dok'      => $jns_dok
                ];

        foreach ($jns_dok as $j) {
            $data['foto'] = $this->M_data->get_foto_deb($id_debitur, $j['id_dokumen_asset'])->result_array();
        }

        

        $this->load->view('foto/V_detail_deb', $data);
        
        
    }

    // menampilkan list debitur dari verifikator
    public function list_debitur($id_ver)
    {
        $nama_karyawan = $this->M_data->cari_data('karyawan', array('id_karyawan' => $id_ver))->row_array();

        $d_debitur = $this->M_data->get_data_debitur_ver($id_ver)->result_array();

        $value = array();
        foreach ($d_debitur as $d) {
            $id_debitur = $d['id_debitur'];
            $no_klaim   = $d['no_klaim'];
            $nm_debitur = $d['nama_debitur'];

            $cek_id = $this->M_data->cek_id_deb($id_debitur)->num_rows();

            if ($cek_id > 0) {
                $cek_id = 'ada';
            } else {
                $cek_id = 'tidak';
            }
            

            $value[] = ['id_debitur'    => $id_debitur,
                        'no_klaim'      => $no_klaim,
                        'nama_debitur'  => $nm_debitur,
                        'status'        => $cek_id
                        ];
        }

        $data = ['foto'     => 'aktif',
                 'judul'    => 'List Debitur Verfikator',
                 'nama_kar' => $nama_karyawan['nama_lengkap'],
                 'id_ver'   => $id_ver,
                 'd_debitur'=> $value
                ];

        $this->template->load('layout/template', 'foto/V_tampil_deb', $data);
    }

    // menampilkan foto debitur
    public function foto_debitur($id_deb, $id_ver)
    {
        $nama_deb = $this->M_data->cari_data('debitur', array('id_debitur' => $id_deb))->row_array();

        $d_foto = $this->M_data->get_data_foto_deb($id_deb)->result_array();

        $value = array();

        foreach ($d_foto as $f) {
            $foto           = $f['foto'];
            $id_dok_asset   = $f['id_dokumen_asset'];
            $nm_tampak      = $f['tampak_asset'];

            $cari_jns_dok   = $this->M_data->cari_jns_dok(array('d.id_dokumen_asset' => $id_dok_asset, 'd.id_debitur' => $id_deb))->row_array();

            if (empty($nm_tampak)) {
                $nm_tampak = $nm_tampak;
            } else {
                $nm_tampak = "Tampak ".$nm_tampak;
            }
            

            $value[]    = ['foto'           => $foto,
                           'tampak_asset'   => $nm_tampak,
                           'jenis_dok'      => $cari_jns_dok['jenis_dokumen']
                            ];
        }

        $data = ['foto'     => 'aktif',
                 'judul'    => 'Foto Dokumen',
                 'nama_deb' => $nama_deb['nama_debitur'],
                 'id_ver'   => $id_ver,
                 'd_foto'   => $value
                ];

        $this->template->load('layout/template', 'foto/V_foto_dok', $data);
    }

}

/* End of file Data.php */
