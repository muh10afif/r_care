<!doctype html>
<html>
    <head>
        <title>Print Report Recoveries</title>

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

      <h5>Laporan Recoveries</h5>
      <h6>PERIODE <?= nice_date($d_bulan_awal, 'F-Y') ?> s/d <?= nice_date($d_bulan_akhir, 'F-Y') ?></h6>

      <table border="1" width="100%" id="ad">
        <thead style="background-color: #122E5D; color: black;">
          <tr>
            <th>NO</th>
            <th>Deal Reff</th>
            <th>NO KLAIM</th>
            <th>NAMA DEBITUR</th>
            <th>BANK</th>
            <th>CAPEM BANK</th>
            <th>ASURANSI</th>
            <th>TANGGAL BAYAR</th>
            <th>JUMLAH BAYAR</th>
            <th>RECOVERIES</th>
          </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach ($data_recov as $r): 
                
                if ($kondisi == 'excel') {
                    $t_recov = $r['tot_recov'];
                    $j_bayar = $r['jml_bayar'];
                } else {
                    $t_recov = number_format($r['tot_recov'], '2', ',', '.');
                    $j_bayar = number_format($r['jml_bayar'], '2', ',', '.');
                }
                    
            ?>
                
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $r['no_reff'] ?></td>
                <td><?= $r['no_klaim'] ?></td>
                <td><?= $r['nama_debitur'] ?></td>
                <td><?= $r['bank'] ?></td>
                <td><?= $r['capem_bank'] ?></td>
                <td><?= $r['cabang_asuransi'] ?></td>
                <td align='center'><?= nice_date($r['tgl_bayar'], 'd-M-Y') ?></td>
                <td align="right"><?= $j_bayar ?></td>
                <td align="right"><?= $t_recov ?></td>
            </tr>

            <?php endforeach; ?>
        </tbody>
      </table>

</body>
</html>