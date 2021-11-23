<!doctype html>
<html>
    <head>
        <title>Report Debitur yang sudah Dikunjungi</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>

    #ad thead tr th {
      vertical-align: middle;
      text-align: center;
    }

    th, td {
      padding: 5px;
      font-size: 10px;
    }

    th {
      text-align: center;
    }

    thead tr th {
      background-color: #122E5D; color: white;
    }
    .a tr td {
      font-weight: bold;
    }
    body {
      margin: 20px 20px 20px 20px;
      color: black;
    }
    h5, h6 {
      font-weight: bold;
      text-align: center;
    }
    #d th {
      background-color: #122E5D; color: white;
    }
    </style>
    </head>
    <body>
      <?php if ($kondisi == 'lihat'): ?>
        <form method="POST" target="_self" id="tab" action="<?= base_url('r_semua/unduh_data') ?>">
          <input type="hidden" name="jenis_report" value="<?= $jenis_report ?>">
          <input type="hidden" name="bulan_awal" value="<?= $d_bulan_awal ?>">
          <input type="hidden" name="bulan_akhir" value="<?= $d_bulan_akhir ?>">
          <input type="hidden" name="verifikator" value="<?= $d_verifikator ?>">

          <button name="pdf" onclick="b()" class="btn btn-primary">UNDUH - PDF</button><?= nbs(5) ?>
          <button name="excel" onclick="b()" class="btn btn-success">UNDUH - EXCEL</button>

        </form><br>
      <?php endif;?>

      <h5>Laporan Debitur yang sudah Dikunjungi</h5>
      <h5>Periode <?= nice_date($d_bulan_awal, 'F-Y') ?> s/d <?= nice_date($d_bulan_akhir, 'F-Y') ?></h5>
      <?= br(2) ?>
      
      <table border="1" id="ad" width="100%">
        <thead style="background-color: #122E5D; color: white;">
          <tr>
            <th rowspan="3">Area</th>
            <th rowspan="3">Nama Verifikator</th>

            <?php if ($id_cabang_as == '') : ?>
              <th rowspan="2" colspan="2">TOTAL KELOLAAN</th>
            <?php else : ?>
              <th rowspan="2">TOTAL KELOLAAN</th>
            <?php endif ?>

            
            <?php $s = (count($bulan)*2) ?>

              <?php if ($id_cabang_as == '') : ?>
                <th colspan="<?= $s ?>">OTS</th>
              <?php else : ?>
                <th colspan="<?= count($bulan) ?>">OTS</th>
              <?php endif ?>

            
            <th rowspan="3"><?php $c = "TOTAL NOA YG SUDAH DI OTS"; echo wordwrap($c,8,"<br>\n"); ?></th>
            <?php if ($id_cabang_as == '') : ?>
              <th rowspan="3"><?php $c = "TOTAL SHS YG SUDAH DI OTS"; echo wordwrap($c,16,"<br>\n"); ?></th>
            <?php endif ?>

            <?php if ($id_cabang_as == '') : ?>
              <th colspan="2" rowspan="2">YANG BELUM DI OTS</th>
            <?php else : ?>
              <th colspan="1" rowspan="2">YANG BELUM DI OTS</th>
            <?php endif ?>
            

          </tr>
          <tr>
          
            <?php foreach ($bulan as $b) : ?>
              <?php if ($id_cabang_as == '') : ?>
                <th colspan="2"><?= nice_date($b, 'F-Y') ?></th>
              <?php else : ?>
                <th><?= nice_date($b, 'F-Y') ?></th>
              <?php endif ?>
              
            <?php endforeach ?>
          </tr>
          <tr>
            <th>NOA</th>
            <?php if ($id_cabang_as == '') : ?>
            <th>SHS</th>
            <?php endif ?>
            <?php foreach ($bulan as $b) : ?>
            <th>NOA</th>
              <?php if ($id_cabang_as == '') : ?>
              <th>SHS</th>
              <?php endif ?>
            <?php endforeach ?>
            <th>NOA</th>
            <?php if ($id_cabang_as == '') : ?>
            <th>SHS</th>
            <?php endif ?>
          </tr>
        </thead>

        <tbody>
          
          <!-- ------------ -->
          <!--- JAWA BARAT --->
          <!-- ------------ -->

          <?php $no=0; $tot_noa = 0; $tot_shs = 0; $total_semua_sdh_ots=0; $total_semua_shs_ots=0; $total_belum_ots=0; $tot_shs_blm_ots=0; $total_belum_shs_ots=0;
           foreach ($nama_ver as $v): $no++?>
            <tr>
              <td align='center'><?= ($no == 1) ? 'Jawa Barat' : '' ?></td>
              <td><?= $v['nama_lengkap'] ?></td>

              <!-- menampilkan total kelolaan -->
              <?php $tot_kelolaan = $this->M_all_report->get_total_kelolaan($v['id_karyawan']);

                  if ($kondisi == 'excel') {
                    $tot_kelolaan_shs = $tot_kelolaan['shs'];
                  } else {
                    $tot_kelolaan_shs = number_format($tot_kelolaan['shs'],'2',',','.');
                  }

              ?>
              <td align="center"><?= $tot_kelolaan['noa'] ?></td>

              <?php if ($id_cabang_as == '') : ?>
                <td align="right"><?= $tot_kelolaan_shs ?></td>
              <?php endif ?>

              
              

              <?php $tot_noa += $tot_kelolaan['noa'];
                    $tot_shs += $tot_kelolaan['shs'];
              
               ?>

              <!-- menampilkan noa shs validasi agunan -->
              <?php $tot_sdh_ots=0; $tot_shs_ots1=0; $tot_shs_ots=0; foreach ($bulan as $b) : ?>

                <?php $tot_validasi_agunan = $this->M_all_report->get_validasi_agunan($b, $v['id_karyawan'], 'kunjungan'); 

                    if ($kondisi == 'excel') {
                      $tot_validasi_shs = $tot_validasi_agunan['shs'];
                    } else {
                      $tot_validasi_shs = number_format($tot_validasi_agunan['shs'],'2',',','.');
                    }

                ?>
                <td align="center"><?= $tot_validasi_agunan['noa'] ?></td>

                <?php if ($id_cabang_as == '') : ?>
                  <td align="right"><?= $tot_validasi_shs ?></td>
                <?php endif ?>
                

                <?php

                  $tot_sdh_ots += $tot_validasi_agunan['noa'];
                  $tot_shs_ots1 += $tot_validasi_agunan['shs'];
                  
                ?>
                  
              <?php endforeach ?>

              <!-- total noa yang sudah di ots -->
              <td align="center"><?= $tot_sdh_ots ?></td>

              <?php 

                if ($kondisi == 'excel') {
                  $tot_shs_ots = $tot_shs_ots1;
                } else {
                  $tot_shs_ots = number_format($tot_shs_ots1, '2',',','.');
                }
              
              ?>

              <?php if ($id_cabang_as == '') : ?>
                <!-- total shs yang sudah diots -->
                <td align="right"><?= $tot_shs_ots ?></td>
              <?php endif ?>

              <?php 

                $total_semua_sdh_ots += $tot_sdh_ots;
                $total_semua_shs_ots += $tot_shs_ots1;

                $tot_blm_ots      = $tot_kelolaan['noa'] - $tot_sdh_ots;
                $tot_shs_blm_ots  = $tot_kelolaan['shs'] - $tot_shs_ots1;

                if ($kondisi == 'excel') {
                  $tot_shs_blm_ots1 = $tot_shs_blm_ots;
                } else {
                  $tot_shs_blm_ots1 = number_format($tot_shs_blm_ots, '2',',','.');
                }

              ?>

              <!-- yang belum di ots -->
              <td align='center'><?= $tot_blm_ots ?></td>

              <?php if ($id_cabang_as == '') : ?>
                <td align="right"><?= $tot_shs_blm_ots1 ?></td>
              <?php endif ?>

              

              <?php
              $total_belum_ots      += $tot_blm_ots;
              $total_belum_shs_ots  += $tot_shs_blm_ots;
              ?>

            </tr>
          <?php endforeach; ?>

          <tr id="d">
            <?php 

              if ($kondisi == 'excel') {
                  $tot_shs = $tot_shs;
              } else {
                  $tot_shs = number_format($tot_shs,'2',',','.');
              }
            
            ?>

            <th colspan="2">Total</th>
            <th align="center"><?= $tot_noa ?></th>

            <?php if ($id_cabang_as == '') : ?>
              <th style="text-align: right"><?= $tot_shs ?></th>
            <?php endif ?>

            
            
            <!-- menampilkan noa shs validasi agunan -->
            <?php foreach ($bulan as $b) : ?>

              <?php $tot_validasi_agunan = $this->M_all_report->get_validasi_agunan($b, '', 'kunjungan'); 

                  if ($kondisi == 'excel') {
                    $tot_validasi_shs = $tot_validasi_agunan['shs'];
                  } else {
                    $tot_validasi_shs = number_format($tot_validasi_agunan['shs'],'2',',','.');
                  }

              ?>
              <th align="center"><?= $tot_validasi_agunan['noa'] ?></th>

              <?php if ($id_cabang_as == '') : ?>
                <th style="text-align: right"><?= $tot_validasi_shs ?></th>
              <?php endif ?>
              
              
            <?php endforeach ?>

            <!-- total noa yang sudah di ots -->
            <th align="center"><?= $total_semua_sdh_ots ?></th>

            <?php

              if ($kondisi == 'excel') {
                $total_semua_shs_ots1 = $total_semua_shs_ots;
                $total_belum_shs_ots1 = $total_belum_shs_ots;
              } else {
                $total_semua_shs_ots1 = number_format($total_semua_shs_ots,'2',',','.');
                $total_belum_shs_ots1 = number_format($total_belum_shs_ots,'2',',','.');
              }
            
            ?>

            <?php if ($id_cabang_as == '') : ?>
              <th style="text-align: right"><?= $total_semua_shs_ots1 ?></th>
            <?php endif ?>

            <th><?= $total_belum_ots ?></th>

            <?php if ($id_cabang_as == '') : ?>
              <th style="text-align: right"><?= $total_belum_shs_ots1 ?></th>
            <?php endif ?>

          </tr>
          
        </tbody>
      </table>

</body>
</html>