<!doctype html>
<html>
    <head>
        <title>Print R. Proses</title>

    <!-- Bootstrap 3.3.7 -->
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

        <form method="POST" action="<?= base_url('r_proses/unduh_data/cetak') ?>">
          <input type="hidden" name="asuransi" value="<?= $asuransi_id ?>">
          <input type="hidden" name="cabang_asuransi" value="<?= $cbg_asuransi_id ?>">
          <input type="hidden" name="bank" value="<?= $bank_id ?>">
          <input type="hidden" name="cabang_bank" value="<?= $cbg_bank_id ?>">
          <input type="hidden" name="capem_bank" value="<?= $cpm_bank_id ?>">
          <input type="hidden" name="verifikator" value="<?= $verifikator_id ?>">
          <input type="hidden" name="tgl_awal" value="<?= $tgl_awal ?>">
          <input type="hidden" name="tgl_akhir" value="<?= $tgl_akhir ?>">
          <input type="hidden" name="spk" value="<?= $id_spk ?>">
          
          <button name="pdf" class="btn btn-primary">UNDUH - PDF</button><?= nbs(5) ?>
          <button name="excel" class="btn btn-success">UNDUH - EXCEL</button>
        </form><br>
      <?php endif ?>
      
        <h5 style="font-weight: bold;"><?= $judul ?></h5>
        <table class="a">
        <?php if(!empty($asuransi)) : ?>
              <tr>
                  <td width="150px">Asuransi</td>
                  <td>:</td>
                  <td><?= $asuransi ?></td>
              </tr>
          <?php endif ?>
          <?php if(!empty($cbg_asuransi)) : ?>
              <tr>
                  <td width="150px">Cabang Asuransi</td>
                  <td>:</td>
                  <td><?= $cbg_asuransi ?></td>
              </tr>
          <?php endif ?>
          <?php if(!empty($bank)) : ?>
              <tr>
                  <td width="150px">Bank</td>
                  <td>:</td>
                  <td><?= $bank ?></td>
              </tr>
          <?php endif ?>
          <?php if(!empty($cbg_bank)) : ?>
              <tr>
                  <td width="150px">Cabang Bank</td>
                  <td>:</td>
                  <td><?= $cbg_bank ?></td>
              </tr>
          <?php endif ?>
          <?php if(!empty($cpm_bank)) : ?>
              <tr>
                  <td width="150px">Capem Bank</td>
                  <td>:</td>
                  <td><?= $cpm_bank ?></td>
              </tr>
          <?php endif ?>
          <?php if(!empty($verifikator)) : ?>
              <tr>
                  <td width="150px">Verifikator</td>
                  <td>:</td>
                  <td><?= $verifikator ?></td>
              </tr>
          <?php endif ?>
          <?php if($tgl_awal != '') : ?>
              <tr>
                  <td width="150px">Tanggal Awal</td>
                  <td>:</td>
                  <td><?= ($tgl_awal) ?></td>
              </tr>
          <?php endif ?>
          <?php if($tgl_akhir != '') : ?>
              <tr>
                  <td width="150px">Tanggal Akhir</td>
                  <td>:</td>
                  <td><?= ($tgl_akhir) ?></td>
              </tr>
          <?php endif ?>
          <?php if($spk != '') : ?>
              <tr>
                  <td width="150px">SPK</td>
                  <td>:</td>
                  <td><?= $spk ?></td>
              </tr>
          <?php endif ?>
        </table><?= br() ?>
        <table border="1" width="100%">
          <thead>
            <tr>
              <th>No</th>
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
                <?php $no=1; foreach ($data_r_proses as $p): ?>
                  <tr>
                    <td><?= $no ?></td>
                    <td><?= $p['cabang_asuransi'] ?></td>
                    <td><?= $p['cabang_bank'] ?></td>
                    <td><?= $p['bank'] ?></td>
                    <td><?= $p['penyebab_klaim'] ?></td>
                    <td><?= $p['no_reff'] ?></td>
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
                    <?php else : ?>
                      <td></td>
                    <?php endif ?>
                    
                  </tr>
                <?php $no++; endforeach ?>
            <?php else: ?>

              <tr>
                <td colspan="9" align="center">DATA KOSONG</td>
              </tr>
              
            <?php endif ?>

            
          </tbody>
        </table>
        <br>
        <table border="1" align="right">
          <thead>
            <tr>
              <th>Proses Recoveries :</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <table>
                  <?php foreach ($jml_r_proses as $j): ?>
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