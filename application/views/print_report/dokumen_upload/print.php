<!doctype html>
<html>
    <head>
        <title>Print Report Dokumen Upload</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
    @media print 
    {
        @page {
          size: A4 landscape; /* DIN A4 standard, Europe */
          margin: 0;
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
              <th>Harga</th>
              <th>keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($data_dokumen)): ?>
              <tr>
                <td align="center" colspan="6">DATA KOSONG</td>
              </tr>
            <?php else: ?>
              <?php foreach ($data_dokumen as $d): ?>
                <tr>
                  <td><?= $d['cabang_asuransi'] ?></td>
                  <td><?= $d['cabang_bank'] ?></td>
                  <td><?= $d['jenis_asset'] ?></td>
                  <td><?= $d['jenis_dokumen'] ?></td>
                  <td align="right"><?= number_format($d['harga'],0,'.','.') ?></td>
                  <td><?= $d['keterangan'] ?></td>
                </tr>
              <?php endforeach ?>
            <?php endif; ?>
          </tbody>
        </table>
</body>
</html>