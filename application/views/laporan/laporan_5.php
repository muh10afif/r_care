<!doctype html>
<html>
    <head>
        <title>Laporan Keuangan 5</title>

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

    tr th {
      background-color: #122E5D; color: white;
    }
    .a tr td {
      font-weight: bold;
    }
    body {
      margin: 20px 20px 20px 20px;
      color: black;
    }
    h4, h5, h6 {
      font-weight: bold;
      text-align: center;
    }
    #d th {
      background-color: #122E5D; color: white;
    }
    #tot {
      background-color: #d2e0f7; font-weight: bold;
    }
    #tot_1 {
      font-weight: bold;
    }
    </style>
    </head>
    <body>
      <?php if ($kondisi == 'lihat'): ?>
        <form method="POST" target="_self" id="tab" action="<?= base_url('r_laporan/unduh_data') ?>">

          <input type="hidden" name="jenis_laporan" value="<?= $jenis_laporan ?>">
          <input type="hidden" name="start" value="<?= $d_bulan_awal ?>">
          <input type="hidden" name="end" value="<?= $d_bulan_akhir ?>">
          <input type="hidden" name="tgl_awal" value="<?= $tgl_awal ?>">
          <input type="hidden" name="tgl_akhir" value="<?= $tgl_akhir ?>">

          <button name="pdf" onclick="b()" class="btn btn-primary">UNDUH - PDF</button><?= nbs(5) ?>
          <button name="excel" onclick="b()" class="btn btn-success">UNDUH - EXCEL</button>

        </form><br>
      <?php endif ?>

      <h4>PENGELUARAN PT. SOLUSI PRIMA SELINDO</h4>
      <h6>SUBROGASI BANK BJB</h6>
      <h6>PERIODE <?= nice_date($tgl_awal, 'd-F-Y') ?> s/d <?= nice_date($tgl_akhir, 'd-F-Y') ?></h6>

      <br>  

      <table border="1" id="ad" width="100%">
        <thead style="background-color: #122E5D; color: white;" class="a">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th width="25%">Keterangan</th>
                <th>PIC</th>
                <th>COA</th>
                <th>Deskripsi COA</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data_report)): ?>
                <tr>
                    <td align="center" colspan="10">DATA KOSONG</td>
                </tr>
            <?php endif; ?>

            <?php $no=0; foreach ($data_report as $d): $no++;

                if (isset($_POST['excel'])) {
                $nominal = $d['debit'];
                } else {
                $nominal = number_format($d['debit'], '2',',','.');
                }

            ?>
                <tr>
                    <td align="center"><?= $no ?></td>
                    <td align='center'><?= nice_date($d['tgl_transaksi'], 'd-M-Y') ?></td>
                    <td><?= $d['keterangan'] ?></td>
                    <td><?= ucwords($d['pengguna']) ?></td>
                    <td><?= $d['no_coa_des'] ?></td>
                    <td><?= $d['deskripsi_coa'] ?></td>
                    <td align="right"><?= $nominal ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
      </table>

</body>
</html>