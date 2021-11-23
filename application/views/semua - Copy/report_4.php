<!doctype html>
<html>
    <head>
        <title>Print Report Achievment</title>

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
        <form method="POST" target="_self" id="tab" action="<?= base_url('r_semua/unduh_data') ?>">
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
          <?php if (count($data_report) != 0): ?>
            <?php $no=1; foreach ($data_report as $d): ?>
              <tr>
                <td align="center"><?= $no++ ?></td>
                <td align="right"><?= $b= tgl_indo(substr($d['tgl_ots'], 0,10)) ?></td>
                <td align="right"><?= tgl_indo($d['tgl_bayar']) ?></td>
                <td><?= $d['nama_debitur'] ?></td>
                <td align="right">Rp. <?= number_format($d['tot_recov_as'],2,'.',',') ?></td>
                <td><?= $d['bank'] ?></td>
                <td><?= $d['cabang_bank'] ?></td>
                <td><?= $d['capem_bank'] ?></td>
                <td><?= $d['cabang_asuransi'] ?></td>
              </tr>
            <?php endforeach ?>
          <?php else: ?>
            <tr>
              <td align="center" colspan="9">Data Kosong</td>
            </tr>
          <?php endif; ?>

          
        </tbody>
      </table>

</body>
</html>