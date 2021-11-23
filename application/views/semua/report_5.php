<!doctype html>
<html>
    <head>
        <title>Report Recoveries</title>

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
      <?php endif ?>

      <h5>Laporan Recoveries</h5>
      <h5>Periode <?= nice_date($d_bulan_awal, 'F-Y') ?> s/d <?= nice_date($d_bulan_akhir, 'F-Y') ?></h5>
      <?= br(2) ?>
      
      <table border="1" id="ad" width="100%">
        <thead style="background-color: #122E5D; color: white;">
          <tr>
            <th rowspan="3">Area</th>
            <th rowspan="3">Nama Verifikator</th>
            <th rowspan="2" colspan="2">TOTAL KELOLAAN</th>
            <?php $s = (count($bulan)*2) ?>
            <th colspan="<?= $s ?>">Recoveries</th>
          </tr>
          <tr>
            <?php foreach ($bulan as $b) : ?>
              <th colspan="2"><?= nice_date($b, 'F-Y') ?></th>
            <?php endforeach ?>
          </tr>
          <tr>
            <th>NOA</th>
            <th>Recoveries</th>
            <?php foreach ($bulan as $b) : ?>
            <th>NOA</th>
            <th>Recoveries</th>
            <?php endforeach ?>
          </tr>
        </thead>
        <tbody>
          
          <!-- ------------ -->
          <!--- JAWA BARAT --->
          <!-- ------------ -->

          <?php $no=0; $tot_noa = 0; $tot_shs = 0;
           foreach ($nama_ver as $v): $no++?>
            <tr>
              <td align='center'><?= ($no == 1) ? 'Jawa Barat' : '' ?></td>
              <td><?= $v['nama_lengkap'] ?></td>

              <!-- menampilkan total kelolaan -->
              <?php $tot_kelolaan = $this->M_all_report->get_total_kelolaan_recov($v['id_karyawan']);

                  if ($kondisi == 'excel') {
                    $tot_kelolaan_recov = $tot_kelolaan['recov'];
                  } else {
                    $tot_kelolaan_recov = number_format($tot_kelolaan['recov'],'2',',','.');
                  }

              ?>
              <td align="center"><?= $tot_kelolaan['noa'] ?></td>
              <td align="right"><?= $tot_kelolaan_recov ?></td>

              <?php $tot_noa += $tot_kelolaan['noa'];
                    $tot_shs += $tot_kelolaan['recov'];
              
               ?>

              <!-- menampilkan noa recov -->
              <?php foreach ($bulan as $b) : ?>

                <?php $tot_recov_bulan      = $this->M_all_report->get_recov_bulan($b, $v['id_karyawan']); 

                    if ($kondisi == 'excel') {
                      $tot_recov_b = $tot_recov_bulan['recov'];
                    } else {
                      $tot_recov_b = number_format($tot_recov_bulan['recov'],'2',',','.');
                    }

                ?>
                <td align="center"><?= $tot_recov_bulan['noa'] ?></td>
                <td align="right"><?= $tot_recov_b ?></td>
                  
              <?php endforeach ?>


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
            <th style="text-align: right"><?= $tot_shs ?></th>
            
            <!-- menampilkan noa recov -->
            <?php foreach ($bulan as $b) : ?>

            <?php $tot_recov_bulan      = $this->M_all_report->get_recov_bulan($b, ""); 

                if ($kondisi == 'excel') {
                  $tot_recov_b = $tot_recov_bulan['recov'];
                } else {
                  $tot_recov_b = number_format($tot_recov_bulan['recov'],'2',',','.');
                }

            ?>
            <th align="center"><?= $tot_recov_bulan['noa'] ?></th>
            <th style="text-align: right"><?= $tot_recov_b ?></th>
              
            <?php endforeach ?>

          </tr>
          
        </tbody>
      </table>

</body>
</html>