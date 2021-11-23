<!doctype html>
<html>
    <head>
        <title>Print R. OTS</title>

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
    </style>
    </head>
    <body>
      <?php if ($kondisi == 'lihat'): ?>
        <form method="POST" target="_self" id="tab" action="<?= base_url('askSyariah/R_semuaSyariah/unduh_data') ?>">
          <input type="hidden" name="jenis_report" value="<?= $jenis_report ?>">
          <input type="hidden" name="bulan_awal" value="<?= $d_bulan_awal ?>">
          <input type="hidden" name="bulan_akhir" value="<?= $d_bulan_akhir ?>">
          <input type="hidden" name="verifikator" value="<?= $d_verifikator ?>">

          <button name="pdf" onclick="b()" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i><?= nbs(2) ?>UNDUH - PDF</button><?= nbs(5) ?>
          <button name="excel" onclick="b()" class="btn btn-success"><i class="fa fa-file-excel-o"></i><?= nbs(2) ?>UNDUH - EXCEL</button>

        </form><br>
      <?php endif ?>

      <h5>REPORT ACHIEVMENT RECOVERIES & OTS BY VERIFIKATOR</h5>
      <h6>KELOLAAN : <?= strtoupper($nama_ver['nama_lengkap']) ?></h6>
      <h6>PERIODE PEMBAYARAN <?php $a = substr($d_bulan_awal, -2); echo $b = strtoupper(bln($a)); ?> - <?= strtoupper(bln_indo($d_bulan_akhir)) ?></h6>

      <table border="1" width="100%" id="ad">
        <thead style="background-color: #122E5D; color: black;">
          <tr>
            <th>NO</th>
            <th>TANGGAL OTS</th>
            <th>TANGGAL BAYAR</th>
            <th>NAMA DEBITUR</th>
            <th>RECOVERIES</th>
            <th>BANK</th>
            <th>CABANG</th>
            <th>KANTOR CABANG BJB</th>
            <th>ASKRINDO</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($data_report)): ?>

            <tr>
              <td colspan="9" align="center">DATA KOSONG</td>
            </tr>

          <?php else: ?>

          <?php $no=1; foreach ($data_report as $d): ?>
            <tr>
              <td align="center"><?= $no++ ?></td>
              <td align="right"><?= $b= tgl_indo(substr($d['tgl_ots'], 0,10)) ?></td>
              <td align="right"><?= tgl_indo($d['tgl_bayar']) ?></td>
              <td><?= $d['nama_debitur'] ?></td>
              <td align="right"><?= number_format($d['recoveries'],0,'.','.') ?></td>
              <td><?= $d['bank'] ?></td>
              <td><?= $d['cabang_bank'] ?></td>
              <td><?= $d['kantor_cabang_bjb'] ?></td>
              <td><?= $d['asuransi'] ?></td>
            </tr>
          <?php endforeach ?>

          <?php endif ?>
        </tbody>
      </table>

        <!-- jQuery 3 -->
        <script src="<?= base_url() ?>assets/bower_components/jquery/dist/jquery.min.js"></script>

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