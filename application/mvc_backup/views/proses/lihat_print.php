<!doctype html>
<html>
    <head>
        <title>Print R. Proses</title>

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
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
        <form method="POST" action="<?= base_url('r_proses/unduh_pdf') ?>">
          <input type="hidden" name="tgl_awal" value="<?= $tgl_awal ?>">
          <input type="hidden" name="tgl_akhir" value="<?= $tgl_akhir ?>">
          <input type="hidden" name="tindakan" value="<?= $tindakan ?>">
          <input type="hidden" name="bank" value="<?= $bank ?>">

          <button name="pdf" onclick="b()" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i><?= nbs(2) ?>UNDUH - PDF</button><?= nbs(5) ?>
          <button name="excel" onclick="b()" class="btn btn-success"><i class="fa fa-file-excel-o"></i><?= nbs(2) ?>UNDUH - EXCEL</button>

        </form><br>
      <?php endif ?>
      
        <h5 style="font-weight: bold;"><?= $judul ?></h5>
        <table class="a">
          <?php if (!empty($tgl_awal)): ?>
            <tr>
              <td width="150px">Tanggal Awal Periode</td>
              <td>:</td>
              <td><?= (!empty($tgl_awal)) ? tgl_indo($tgl_awal) : '-' ?></td>
            </tr>
          <?php endif ?>
          <?php if (!empty($tgl_akhir)): ?>
            <tr>
              <td width="150px">Tanggal Akhir Periode</td>
              <td>:</td>
              <td><?= (!empty($tgl_akhir)) ? tgl_indo($tgl_akhir) : '-' ?></td>
            </tr>
          <?php endif ?>
          <?php if (!empty($tindakan)): ?>
            <tr>
              <td width="150px">Tindakan Hukum</td>
              <td>:</td>
              <td><?= (!empty($tindakan)) ? $tindakan : '-' ?></td>
            </tr>
          <?php endif ?>
          <?php if (!empty($bank)): ?>
            <tr>
              <td width="150px">Bank</td>
              <td>:</td>
              <td><?= (!empty($bank)) ? $bank : '-' ?></td>
            </tr>
          <?php endif ?>
        </table><?= br() ?>
        <table border="1" width="100%">
          <thead>
            <tr>
              <th>Asuransi</th>
              <th>Cabang Bank</th>
              <th>Bank</th>
              <th>Penyebab Klaim</th>
              <th>Deal Reff</th>
              <th>Nama Debitur</th>
              <th>Nomor Klaim</th>
              <th>Proses Recoveries</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data_r_proses)): ?>
                <?php foreach ($data_r_proses as $p): ?>
                  <tr>
                    <td><?= $p['cabang_asuransi'] ?></td>
                    <td><?= $p['cabang_bank'] ?></td>
                    <td><?= $p['bank'] ?></td>
                    <td><?= $p['penyebab_klaim'] ?></td>
                    <td><?= $p['deal_reff'] ?></td>
                    <td><?= $p['nama_debitur'] ?></td>
                    <td><?= $p['no_klaim'] ?></td>
                    <?php $t = $p['tindakan_hukum'] ?>
                    <?php if ($t == 'Penagihan Rutin'): ?>
                      <td style="background-color: #70e68c; color: black;"><?= $t ?></td>
                    <?php elseif ($t == 'Somasi 1'): ?>
                      <td style="background-color: #f2a28c; color: black;"><?= $t ?></td>
                    <?php elseif ($t == 'Somasi 2'): ?>
                      <td style="background-color: #e6f243; color: black;"><?= $t ?></td>
                    <?php elseif ($t == 'Litigasi'): ?>
                      <td style="background-color: #2243ad; color: white;"><?= $t ?></td>
                    <?php elseif ($t == 'Non Potensial'): ?>
                      <td style="background-color: #e51934; color: black;"><?= $t ?></td>
                    <?php elseif ($t == 'Eksekusi Jaminan'): ?>
                      <td style="background-color: #03a335; color: black;"><?= $t ?></td>
                    <?php elseif ($t == 'Mediasi'): ?>
                      <td style="background-color: #758de6; color: black;"><?= $t ?></td>
                    <?php endif ?>
                    <td></td>
                  </tr>
                <?php endforeach ?>
            <?php else: ?>

              <tr>
                <td colspan="8" align="center">DATA KOSONG</td>
              </tr>
              
            <?php endif ?>

            
          </tbody>
        </table>
        <br>
        <table border="1" align="right">
          <thead>
            <tr>
              <th colspan="3">Proses Recoveries :</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
            <table>
            <?php foreach ($jml_r_proses->result_array() as $j): ?>
              <tr>
                <td><?= $j['jumlah_tindakan'] ?> Debitur</td>
                <td>-</td>
                <td><?= $j['tindakan_hukum'] ?></td>
              </tr>
            <?php endforeach ?>
          </table>
        </td>
      </tr>
          </tbody>
        </table>
</body>
</html>