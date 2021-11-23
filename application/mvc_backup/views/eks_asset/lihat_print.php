<!doctype html>
<html>
    <head>
        <title>Print R. EKS ASSET</title>

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
        <form method="POST" action="<?= base_url('r_eks_asset/unduh_pdf') ?>">
          <input type="hidden" name="tgl_awal" value="<?= $tgl_awal ?>">
          <input type="hidden" name="tgl_akhir" value="<?= $tgl_akhir ?>">
          <input type="hidden" name="potensial" value="<?= $jenis_asset ?>">
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
            <?php if (!empty($jenis_asset)): ?>
              <tr>
              <td width="150px">Jenis Asset</td>
              <td>:</td>
              <td><?= (!empty($jenis_asset)) ? $jenis_asset : '-' ?></td>
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
              <th>Deal Reff</th>
              <th>Nama Debitur</th>
              <th>Nama Klaim</th>
              <th>Asset / Jaminan</th>
              <th>Marketable</th>
              <th>Harga Taksiran</th>
              <th>Tindakan</th>
              <th>Proses</th>
            </tr>
          </thead>
          <tbody>

            <?php if ($data_r_eks_asset->result_array()): ?>
                <?php foreach ($data_r_eks_asset->result_array() as $a): ?>
                  <tr>
                    <td><?= $a['cabang_asuransi'] ?></td>
                    <td><?= $a['cabang_bank'] ?></td>
                    <td><?= $a['bank'] ?></td>
                    <td><?= $a['no_reff'] ?></td>
                    <td><?= $a['nama_debitur'] ?></td>
                    <td><?= $a['no_klaim'] ?></td>
                    <td><?= $a['jenis_asset'] ?></td>
                    <td><?= $a['sifat_asset'] ?></td>
                    <td align="right"><?= number_format($a['harga'],0,'.','.') ?></td>
                    <td><?= $a['status_asset'] ?></td>
                    <td></td>
                  </tr>
                <?php endforeach ?>
            <?php else: ?>

              <tr>
                <td colspan="11" align="center">DATA KOSONG</td>
              </tr>
              
            <?php endif ?>
            
          </tbody>
        </table>
</body>
</html>