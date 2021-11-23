<!doctype html>
<html>
    <head>
        <title>Print R. Recov</title>

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
      <form method="POST" action="<?= base_url('r_recov/unduh_pdf') ?>">
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
              <th>Nilai Subrogasi</th>
              <th>Nilai Recoveries (Asuransi)</th>
              <th>SHS</th>
              <th>Nilai Tagihan</th>
              <th>Nilai Recoveries (Bank)</th>
              <th>Saldo Tagihan</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data_r_noa)): ?>
                  <?php $tot_shs = 0; $tot_subro = 0; $tot_saldo_tagihan = 0; $tot_recov = 0; $tot_tagihan = 0;

                  foreach ($data_r_noa as $r): ?>
                    <tr>
                      <td><?= $r['cabang_asuransi'] ?></td>
                      <td width="150"><?= $r['cabang_bank'] ?></td>
                      <td width="150"><?= $r['bank'] ?></td>
                      <td width="250"><?= $r['nama_debitur'] ?></td>
                      <td><?= $r['no_klaim'] ?></td>
                      <td align="right"><?= number_format($r['subrogasi'],0,'.','.') ?></td>
                      <td align="right"><?= ($r['recoveries'] != null) ? number_format($r['recoveries'],0,'.','.') : '-' ?></td>
                      <td align="right"><?= number_format($r['shs'],0,'.','.') ?></td>
                      <td align="right"><?= number_format($r['nilai_tagihan'],0,'.','.') ?></td>
                      <td>-</td>
                      <td align="right"><?= number_format($r['saldo_tagihan'],0,'.','.') ?></td>
                      <td>0</td>
                    </tr><?php $tot_shs += $r['shs']; $tot_saldo_tagihan += $r['saldo_tagihan'] ?>
                    <?php $tot_subro += $r['subrogasi']; $tot_recov += $r['recoveries']; $tot_tagihan += $r['nilai_tagihan'] ?>
                  <?php endforeach ?>
                  <tr>
                    <td colspan="5" align="right" style="font-weight: bold;">Jumlah Total :</td>
                    <td style="font-weight: bold; text-align: right;"><?= number_format($tot_subro,0,'.','.') ?></td>
                    <td style="font-weight: bold; text-align: right;"><?= number_format($tot_recov,0,'.','.') ?></td>
                    <td style="font-weight: bold; text-align: right;"><?= number_format($tot_shs,0,'.','.') ?></td>
                    <td style="font-weight: bold; text-align: right;"><?= number_format($tot_tagihan,0,'.','.') ?></td>
                    <td></td>
                    <td style="font-weight: bold; text-align: right;"><?= number_format($tot_saldo_tagihan,0,'.','.') ?></td>
                    <td></td>
                  </tr>
                </tbody>
              </table><?= br() ?>
              <?php if (!empty($bank )): ?>
                <table border="1" align="right" width="40%">
                <thead>
                  <tr>
                    <th>Resume Recoveries</th>
                    <th><?= $bank ?></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Total NOA</td>
                    <td align="right"><?= count($data_r_noa) ?></td>
                  </tr>
                  <tr>
                    <td>Nilai Klaim / Kredit</td>
                    <td align="right"><?= number_format($tot_subro,0,'.','.') ?></td>
                  </tr>
                  <tr>
                    <td>Recoveries</td>
                    <td align="right"><?= number_format($tot_recov,0,'.','.') ?></td>
                  </tr>
                  <tr>
                    <td>Saldo Tagihan Akhir</td>
                    <td align="right"><?= number_format($tot_shs,0,'.','.') ?></td>
                  </tr>
                  <tr>
                    <td>CRP (%)</td>
                    <td align="right"><?= number_format(($tot_recov/$tot_subro) * 100,2,',','.') ?> %</td>
                  </tr>
                </tbody>
              </table>
              <?php endif ?>
            <?php else: ?>
              <tr>
                <td colspan="11" align="center">Data Kosong</td>
              </tr>
            <?php endif ?>
            
        
</body>
</html>