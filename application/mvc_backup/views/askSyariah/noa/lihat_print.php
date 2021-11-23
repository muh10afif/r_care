<!doctype html>
<html>
    <head>
        <title>Print R. NOA</title>

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
      <form method="POST" action="<?= base_url('r_noa/unduh_pdf') ?>">
        <input type="hidden" name="tgl_awal" value="<?= $tgl_awal ?>">
        <input type="hidden" name="tgl_akhir" value="<?= $tgl_akhir ?>">
        <input type="hidden" name="potensial" value="<?= $potensial ?>">
        <input type="hidden" name="bank" value="<?= $bank ?>">

        <button class="btn btn-primary">U N D U H - P D F</button>
      </form><br>

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
          <?php if (!empty($potensial)): ?>
            <tr>
              <td width="150px">Status Debitur</td>
              <td>:</td>
              <td><?= (!empty($potensial)) ? $potensial : '-' ?></td>
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
              <th>Nama Debitur</th>
              <th>Nomor Klaim</th>
              <th>SHS</th>
              <th>Saldo Tagihan</th>
              <th>Potensial</th>
              <th>Recoveries</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data_r_noa)): ?>
              <?php $tot_shs=0; $tot_saldo_tagihan=0; foreach ($data_r_noa as $r): ?>
                    <tr>
                      <td><?= $r['cabang_asuransi'] ?></td>
                      <td><?= $r['cabang_bank'] ?></td>
                      <td><?= $r['bank'] ?></td>
                      <td><?= $r['nama_debitur'] ?></td>
                      <td><?= $r['no_klaim'] ?></td>
                      <td align="right"><?= number_format($r['shs'],0,'.','.') ?></td>
                      <td align="right"><?= number_format($r['saldo_tagihan'],0,'.','.') ?></td>
                      <?php if ($r['status_deb'] == 'Potensial'): ?>
                      <td align="center"><?= ($r['status_deb'] == 'Potensial') ? 'Ya' : 'Tidak' ?></td>
                      <?php else: ?>
                      <td align="center" style="background-color: #ffb384"><?= ($r['status_deb'] == 'Potensial') ? 'Ya' : 'Tidak' ?></td>
                      <?php endif ?>
                      <td><?= ($r['status_deb'] != 'Potensial') ? 'Tunda' : $r['nama_proses'] ?></td>
                    </tr><?php $tot_shs += $r['shs']; $tot_saldo_tagihan += $r['saldo_tagihan'] ?>
                  <?php endforeach ?>
                  <tr>
                    <td colspan="5" align="right" style="font-weight: bold;">Jumlah Total :</td>
                    <td style="font-weight: bold; text-align: right;"><?= number_format($tot_shs,0,'.','.') ?></td>
                    <td style="font-weight: bold; text-align: right;"><?= number_format($tot_saldo_tagihan,0,'.','.') ?></td>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
              <br>
              <table border="1" align="right" style="margin-left: 20px; margin-bottom: 20px;">
                <thead>
                  <tr>
                    <th colspan="2">Resume :</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Total Noa</td>
                    <td><?= count($data_r_noa) ?> Debitur</td>
                  </tr>
                  <tr>
                    <td>Noa Potensional</td>
                    <td><?= count($jml_data_pot) ?> Debitur</td>
                  </tr>
                  <tr>
                    <td>Nilai SHS</td>
                    <td><?php foreach ($data_pot as $p): ?>
                      Rp. <?= number_format($p['tot_shs'],0,'.','.') ?>
                    <?php endforeach ?></td>
                  </tr>
                  <tr>
                    <td>Nilai Tagihan</td>
                    <td><?php foreach ($data_pot as $p): ?>
                      Rp. <?= number_format($p['tot_tagihan'],0,'.','.') ?>
                    <?php endforeach ?></td>
                  </tr>
                  <tr>
                    <td>Noa Non Potensional</td>
                    <td><?= count($jml_data_non_pot) ?> Debitur</td>
                  </tr>
                  <tr>
                    <td>Nilai SHS</td>
                    <td><?php foreach ($data_non_pot as $p): ?>
                      Rp. <?= number_format($p['tot_shs'],0,'.','.') ?>
                    <?php endforeach ?></td>
                  </tr>
                  <tr>
                    <td>Nilai Tagihan</td>
                    <td><?php foreach ($data_non_pot as $p): ?>
                      Rp. <?= number_format($p['tot_tagihan'],0,'.','.') ?>
                    <?php endforeach ?></td>
                  </tr>
                 
                </tbody>
              </table>
              <table border="1" align="right">
                <thead>
                  <tr>
                    <th colspan="2">Proses Recoveries :</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($data_noa_jml as $d): ?>
                    <?php if ($d['tindakan_hukum'] && $d['total_nominal_recoveries'] != null): ?>
                      <tr>
                        <td><?= $d['jml_tindakan_hukum'] ?> Debitur - <?= $d['tindakan_hukum'] ?></td>
                        <td><?= number_format($d['total_nominal_recoveries'],0,'.','.') ?></td>
                      </tr>
                    <?php endif ?>
                      
                  <?php endforeach ?>
                 
                </tbody>
            <?php else: ?>
              <tr>
                <td colspan="8" align="center">Data Kosong</td>
              </tr>
            <?php endif ?>
            
        </table>
</body>
</html>