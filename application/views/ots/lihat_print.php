<!doctype html>
<html>
    <head>
        <title>Print R. OTS</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>
    th, td {
      padding: 5px;
      font-size: 10px;
    }

    th {
      text-align: center;
    }
    thead tr th {
      background-color: #eee;
    }
    .a tr td {
      font-weight: bold;
    }
    body {
      margin: 20px 20px 20px 20px;
      color: black;
    }
    </style>
    </head>
    <body>
      <?php if ($kondisi == 'lihat'): ?>
      <form method="POST" action="<?= base_url('r_ots/unduh_data/cetak') ?>">
          <input type="hidden" name="asuransi" value="<?= $asuransi_id ?>">
          <input type="hidden" name="cabang_asuransi" value="<?= $cbg_asuransi_id ?>">
          <input type="hidden" name="bank" value="<?= $bank_id ?>">
          <input type="hidden" name="cabang_bank" value="<?= $cbg_bank_id ?>">
          <input type="hidden" name="capem_bank" value="<?= $cpm_bank_id ?>">
          <input type="hidden" name="verifikator" value="<?= $verifikator_id ?>">
          <input type="hidden" name="tgl_awal" value="<?= $tgl_awal ?>">
          <input type="hidden" name="tgl_akhir" value="<?= $tgl_akhir ?>">
          <input type="hidden" name="spk" value="<?= $id_spk ?>">
          
          <button name="pdf" class="btn btn-primary">UNDUH - PDF</button><?= nbs(5) ?>
          <button name="excel" class="btn btn-success">UNDUH - EXCEL</button>
        </form><br>
      <?php endif ?>
      
        <h5 style="font-weight: bold;"><?= $judul ?></h5><br>
        <table class="a">
          <?php if(!empty($asuransi)) : ?>
              <tr>
                  <td width="150px">Asuransi</td>
                  <td>:</td>
                  <td><?= $asuransi ?></td>
              </tr>
          <?php endif ?>
          <?php if(!empty($cbg_asuransi)) : ?>
              <tr>
                  <td width="150px">Cabang Asuransi</td>
                  <td>:</td>
                  <td><?= $cbg_asuransi ?></td>
              </tr>
          <?php endif ?>
          <?php if(!empty($bank)) : ?>
              <tr>
                  <td width="150px">Bank</td>
                  <td>:</td>
                  <td><?= $bank ?></td>
              </tr>
          <?php endif ?>
          <?php if(!empty($cbg_bank)) : ?>
              <tr>
                  <td width="150px">Cabang Bank</td>
                  <td>:</td>
                  <td><?= $cbg_bank ?></td>
              </tr>
          <?php endif ?>
          <?php if(!empty($cpm_bank)) : ?>
              <tr>
                  <td width="150px">Capem Bank</td>
                  <td>:</td>
                  <td><?= $cpm_bank ?></td>
              </tr>
          <?php endif ?>
          <?php if(!empty($verifikator)) : ?>
              <tr>
                  <td width="150px">Verifikator</td>
                  <td>:</td>
                  <td><?= $verifikator ?></td>
              </tr>
          <?php endif ?>
          <?php if($tgl_awal != '') : ?>
              <tr>
                  <td width="150px">Tanggal Awal</td>
                  <td>:</td>
                  <td><?= ($tgl_awal) ?></td>
              </tr>
          <?php endif ?>
          <?php if($tgl_akhir != '') : ?>
              <tr>
                  <td width="150px">Tanggal Akhir</td>
                  <td>:</td>
                  <td><?= ($tgl_akhir) ?></td>
              </tr>
          <?php endif ?>
          <?php if($no_spk != '') : ?>
              <tr>
                  <td width="150px">SPK</td>
                  <td>:</td>
                  <td><?= $no_spk ?></td>
              </tr>
          <?php endif ?>
            
        </table><?= br() ?>
        <table border="1" width="100%">
          <thead>
            <tr>
              <th>No</th>
              <th>Asuransi</th>
              <th>Bank</th>
              <th>Cabang Bank</th>
              <th>Capem Bank</th>
              <th>Deal Reff</th>
              <th>Nama Debitur</th>
              <th>SHS</th>
              <th>OTS</th>
              <th>FU</th>
              <th>Bertemu</th>
              <th>Alamat</th>
              <th>Nomor Telp</th>
              <th>Informasi Debitur</th>
              <th>Agunan</th>
              <th>Status</th>
              <th>Keterangan</th>
              <th>Tindakan Hukum</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data_r_ots)): ?>
                <?php $shs=0; $no=0; foreach ($data_r_ots as $d): $no++; 
                
                $ag   = $this->M_ots->get_agunan_ots($d['id_debitur'])->row_array();

                $f    = $this->M_ots->get_fu_ots($d['id_debitur'])->row_array();

                $stp  = $this->M_ots->get_stp_ots($d['id_kunjungan'], 'st_proses')->row_array();

                $th   = $this->M_ots->get_stp_ots($d['id_kunjungan'], 'td_hukum')->row_array();

                $sh  = ($d['tot_subro'] - $d['tot_recov_awal']) - $d['tot_nominal_as'];

                if ($kondisi == 'excel') {
                    $shs = $sh;
                } else {
                    $shs = number_format($sh, '2', ',', '.');
                }

                ?>
                  <tr>
                    <td><?= $no ?></td>
                    <td><?= $d['cabang_asuransi'] ?></td>
                    <td><?= $d['bank'] ?></td>
                    <td><?= $d['cabang_bank'] ?></td>
                    <td><?= $d['capem_bank'] ?></td>
                    <td><?= $d['no_reff'] ?></td>
                    <td><?= $d['nama_debitur'] ?></td>
                    <td align="right"><?= $shs ?></td>
                    <td><?= nice_date($d['add_time'], 'd-M-Y') ?></td>
                    <td><?= $f['fu'] ?></td>
                    <td><?= $d['pic'] ?></td>
                    <td><?= $d['alamat'] ?></td>
                    <td><?= $d['telp_pic'] ?></td>
                    <td><?= $d['keterangan'] ?></td>
                    <td><?= ($ag['jenis_asset'] != null) ? $ag['jenis_asset'] : 'Tidak ada agunan' ?></td>
                    <td><?= ($d['potensial'] == 1) ? 'Potensial' : 'Non Potensial' ?></td>
                    <td><?= (!empty($stp['status_proses'])) ? $stp['status_proses'] : 'Belum ada status proses' ?></td>
                    <td><?= (!empty($th['tindakan_hukum'])) ? $th['tindakan_hukum'] : 'Belum ada tindakan hukum' ?></td>
                  </tr>
                <?php endforeach ?>
            <?php else: ?>  
                <tr>
                  <td colspan="15" align="center">Data Kosong</td>
                </tr>
            <?php endif ?>
              
          </tbody>
        </table>


        <!-- jQuery 3 -->
        <script src="<?= base_url() ?>template/assets/libs/jquery/dist/jquery.min.js"></script>

        <script type="text/javascript">
          function a() {
            var x = document.getElementById('tab');

            if (x.hasAttribute("target")) {
              x.setAttribute("target", "_blank");
            }
          }

          function b() {
            var x = document.getElementById('tab');

            if (x.hasAttribute("target")) {
              x.setAttribute("target", "_self");
            }
          }
        </script>

</body>
</html>