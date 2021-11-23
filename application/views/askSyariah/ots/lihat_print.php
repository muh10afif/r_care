<!doctype html>
<html>
    <head>
        <title>Print R. OTS</title>

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">

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
      <form method="POST" target="_self" id="tab" action="<?= base_url('r_ots/unduh_pdf') ?>">
        <input type="hidden" name="tgl_awal" value="<?= $tgl_awal ?>">
        <input type="hidden" name="tgl_akhir" value="<?= $tgl_akhir ?>">
        <input type="hidden" name="wilayah" value="<?= $wilayah ?>">
        <input type="hidden" name="verifikator" value="<?= $ver ?>">
        <input type="hidden" name="bank" value="<?= $bank ?>">
        <input type="hidden" name="asuransi" value="<?= $id_asuransi ?>">

        <button name="pdf" onclick="b()" class="btn btn-primary">U N D U H - P D F</button>
        <!-- <button name="print" onclick="a()" class="btn btn-info">P R I N T</button> -->
      </form><br>
        <h5 style="font-weight: bold;"><?= $Judul ?></h5>
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
          <?php if (!empty($wilayah)): ?>
            <tr>
              <td width="150px">Cabang</td>
              <td>:</td>
              <td><?= (!empty($wilayah)) ? $wilayah : '-' ?></td>
            </tr>
          <?php endif ?>
          <?php if (!empty($ver)): ?>
            <tr>
              <td width="150px">Petugas</td>
              <td>:</td>
              <td><?= (!empty($ver)) ? $ver : '-' ?></td>
            </tr>
          <?php endif ?>
          <?php if (!empty($bank)): ?>
            <tr>
              <td width="150px">Bank</td>
              <td>:</td>
              <td><?= (!empty($bank)) ? $bank : '-' ?></td>
            </tr>
          <?php endif ?>
          <?php if (!empty($asuransi)): ?>
            <tr>
              <td width="150px">Asuransi</td>
              <td>:</td>
              <td><?= (!empty($asuransi)) ? $asuransi : '-' ?></td>
            </tr>
          <?php endif ?>
            
        </table><?= br() ?>
        <table border="1" width="100%">
          <thead>
            <tr>
              <th>Asuransi</th>
              <th>Cabang Bank</th>
              <th>Bank</th>
              <th>Nama Debitur</th>
              <th>OTS</th>
              <th>Bertemu</th>
              <th>Alamat</th>
              <th>Nomor Telp</th>
              <th>Informasi Debitur</th>
              <th>Potensi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data_r_ots)): ?>
                <?php foreach ($data_r_ots as $d): ?>
                  <tr>
                    <td><?= $d['cabang_asuransi'] ?></td>
                    <td><?= $d['cabang_bank'] ?></td>
                    <td><?= $d['bank'] ?></td>
                    <td><?= $d['nama_debitur'] ?></td>
                    <td><?= tgl_indo(substr($d['add_time'],0,10)) ?></td>
                    <td><?= $d['narasumber'] ?></td>
                    <td><?= $d['alamat'] ?></td>
                    <td><?= $d['telp'] ?></td>
                    <td><?= $d['keterangan'] ?></td>
                    <td><?= (!empty($d['potensi'])) ? $d['potensi'] : 'Belum ada tindakan' ?></td>
                  </tr>
                <?php endforeach ?>
            <?php else: ?>  
                <tr>
                  <td colspan="11" align="center">Data Kosong</td>
                </tr>
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