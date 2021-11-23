<!doctype html>
<html>
    <head>
        <title>Report Validasi Agunan</title>

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

      <h5>Laporan Validasi Agunan</h5>
      <h5>Periode <?= nice_date($d_bulan_awal, 'F-Y') ?> s/d <?= nice_date($d_bulan_akhir, 'F-Y') ?></h5>
      <?= br(2) ?>
      
      <table border="1" id="ad" width="100%">
        <thead style="background-color: #122E5D; color: white;">
          <tr>
            <th rowspan="3">Area</th>
            <th rowspan="3">Nama Verifikator</th>
            <th rowspan="2" colspan="2">TOTAL KELOLAAN</th>
            <?php $s = (count($bulan)*2) ?>
            <th colspan="<?= $s ?>">Validasi Agunan</th>
          </tr>
          <tr>
            <?php foreach ($bulan as $b) : ?>
              <th colspan="2"><?= nice_date($b, 'F-Y') ?></th>
            <?php endforeach ?>
          </tr>
          <tr>
            <th>NOA</th>
            <th>SHS</th>
            <?php foreach ($bulan as $b) : ?>
            <th>NOA</th>
            <th>SHS</th>
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
              <?php $tot_kelolaan = $this->M_all_report->get_total_kelolaan($v['id_karyawan']);

                  if ($kondisi == 'excel') {
                    $tot_kelolaan_shs = $tot_kelolaan['shs'];
                  } else {
                    $tot_kelolaan_shs = number_format($tot_kelolaan['shs'],'2',',','.');
                  }

              ?>
              <td align="center"><?= $tot_kelolaan['noa'] ?></td>
              <td align="right"><?= $tot_kelolaan_shs ?></td>

              <?php $tot_noa += $tot_kelolaan['noa'];
                    $tot_shs += $tot_kelolaan['shs'];
              
               ?>

              <!-- menampilkan noa shs validasi agunan -->
              <?php foreach ($bulan as $b) : ?>

                <?php $tot_validasi_agunan = $this->M_all_report->get_validasi_agunan($b, $v['id_karyawan'], 'validasi'); 

                    if ($kondisi == 'excel') {
                      $tot_validasi_shs = $tot_validasi_agunan['shs'];
                    } else {
                      $tot_validasi_shs = number_format($tot_validasi_agunan['shs'],'2',',','.');
                    }

                ?>
                <td align="center"><?= $tot_validasi_agunan['noa'] ?></td>
                <td align="right"><?= $tot_validasi_shs ?></td>
                  
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
            
            <!-- menampilkan noa shs validasi agunan -->
            <?php foreach ($bulan as $b) : ?>

              <?php $tot_validasi_agunan = $this->M_all_report->get_validasi_agunan($b, '', 'validasi'); 

                  if ($kondisi == 'excel') {
                    $tot_validasi_shs = $tot_validasi_agunan['shs'];
                  } else {
                    $tot_validasi_shs = number_format($tot_validasi_agunan['shs'],'2',',','.');
                  }

              ?>
              <th align="center"><?= $tot_validasi_agunan['noa'] ?></th>
              <th style="text-align: right"><?= $tot_validasi_shs ?></th>
              
            <?php endforeach ?>

          </tr>
          
        </tbody>
      </table>

</body>
</html>