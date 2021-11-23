<!doctype html>
<html>
    <head>
        <title>Print R. OTS</title>

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
        <h4 style="font-weight: bold;"><?= $Judul ?></h4>
        <table class="a">
            <tr>
              <td width="150px">Tanggal Awal Periode</td>
              <td>:</td>
              <td><?= (!empty($tgl_awal)) ? tgl_indo($tgl_awal) : '-' ?></td>
            </tr>
            <tr>
              <td width="150px">Tanggal Akhir Periode</td>
              <td>:</td>
              <td><?= (!empty($tgl_akhir)) ? tgl_indo($tgl_akhir) : '-' ?></td>
            </tr>
            <tr>
              <td width="150px">Wilayah Capem</td>
              <td>:</td>
              <td><?= (!empty($wilayah)) ? $wilayah : '-' ?></td>
            </tr>
            <tr>
              <td width="150px">Petugas</td>
              <td>:</td>
              <td><?= (!empty($ver)) ? $ver : '-' ?></td>
            </tr>
        </table><?= br() ?>
        <table border="1" width="100%">
          <thead>
            <tr>
              <th>Asuransi</th>
              <th>Cabang Bank</th>
              <th>Nama Debitur</th>
              <th>OTS</th>
              <th>Bertemu</th>
              <th>Alamat</th>
              <th>Nomor Telp</th>
              <th>Informasi Debitur</th>
              <th>Agunan</th>
              <th>Potensi</th>
              <th>Proses</th>
            </tr>
          </thead>
          <tbody>
              <?php foreach ($data_r_ots->result_array() as $d): ?>
                <tr>
                  <td><?= $d['cabang_asuransi'] ?></td>
                  <td><?= $d['cabang_bank'] ?></td>
                  <td><?= $d['nama_debitur'] ?></td>
                  <td><?= tgl_indo(substr($d['add_time'],0,10)) ?></td>
                  <td><?= $d['narasumber'] ?></td>
                  <td><?= $d['alamat'] ?></td>
                  <td><?= $d['telp'] ?></td>
                  <td><?= $d['keterangan'] ?></td>
                  <td>
                  <?php 
                    if ($d['jenis_asset'] != null) {
                      echo $d['jenis_asset'];
                    } else {
                      echo 'Tidak Ada';
                    }
                    ?></td>
                  <td><?= (!empty($d['potensi'])) ? $d['potensi'] : 'Belum ada tindakan' ?></td>
                  <td><?= (!empty($d['proses'])) ? $d['proses'] : 'Belum ada proses' ?></td>
                </tr>
              <?php endforeach ?>
          </tbody>
        </table>
</body>
</html>