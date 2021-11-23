<!doctype html>
<html>
    <head>
        <title>Print Report Dokumen Upload</title>

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">

    <style>
    @media print 
    {
        @page {
          size: A4 landscape; /* DIN A4 standard, Europe */
        }
        html, body {
            font-size: 11px;
            background: #FFF;
            overflow:visible;
        }
        .a {
          align-content: center;
        }
    }

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
      color: black;
    }
    </style>
    </head>
    <body onload ="window.print()" style="margin: 20px 20px 20px 20px;">
        <h4 style="font-weight: bold;"><?= $jenis ?></h4>
        <table class="a">
            <tr>
              <td width="150px">Nama Debitur</td>
              <td>:</td>
              <td><?= (!empty($debitur)) ? $debitur : '-' ?></td>
            </tr>
            <tr>
              <td width="150px">No Klaim</td>
              <td>:</td>
              <td><?= (!empty($no_klaim)) ? $no_klaim : '-' ?></td>
            </tr>
        </table><?= br() ?>
        <table border="1" width="100%">
          <thead>
            <tr>
              <th>Asuransi</th>
              <th>Cabang Bank</th>
              <th>Jenis Asset</th>
              <th>Jenis Dokumen</th>
              <th>Status Asset</th>
              <th>Total Harga</th>
              <th>keterangan</th>
            </tr>
          </thead>
          <tbody>
              <?php foreach ($data_agunan as $d): ?>
                <tr>
                  <td><?= $d['cabang_asuransi'] ?></td>
                  <td><?= $d['cabang_bank'] ?></td>
                  <td><?= $d['jenis_asset'] ?></td>
                  <td><?= $d['jenis_dok'] ?></td>
                  <td><?= $d['status_asset'] ?></td>
                  <td align="right"><?= number_format($d['total_harga'],0,'.','.') ?></td>
                  <td><?= $d['keterangan'] ?></td>
                </tr>
              <?php endforeach ?>
          </tbody>
        </table>
</body>
</html>