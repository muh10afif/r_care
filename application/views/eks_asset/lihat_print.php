<!doctype html>
<html>
    <head>
        <title>Print R. EKS ASSET</title>

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
        <form method="POST" action="<?= base_url('r_eks_asset/unduh_data/cetak') ?>">
          <input type="hidden" name="asuransi" value="<?= $asuransi_id ?>">
          <input type="hidden" name="cabang_asuransi" value="<?= $cbg_asuransi_id ?>">
          <input type="hidden" name="bank" value="<?= $bank_id ?>">
          <input type="hidden" name="cabang_bank" value="<?= $cbg_bank_id ?>">
          <input type="hidden" name="capem_bank" value="<?= $cpm_bank_id ?>">
          <input type="hidden" name="verifikator" value="<?= $verifikator_id ?>">
          <input type="hidden" name="tgl_awal" value="<?= $tgl_awal ?>">
          <input type="hidden" name="tgl_akhir" value="<?= $tgl_akhir ?>">
          <input type="hidden" name="spk" value="<?= $id_spk ?>">
          
          <button name="pdf" onclick="b()" class="btn btn-primary">UNDUH - PDF</button><?= nbs(5) ?>
          <button name="excel" onclick="b()" class="btn btn-success">UNDUH - EXCEL</button>

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
              <th>Deal Reff</th>
              <th>Nama Debitur</th>
              <th>Nama Klaim</th>
              <th>Asset / Jaminan</th>
              <th>Marketable</th>
              <th>Harga Taksiran</th>
              <th>Tindakan</th>
              <th>Proses</th>
            </tr>
          </thead>
          <tbody>

            <?php if ($data_r_eks_asset->result_array()): ?>
                <?php $no=0; foreach ($data_r_eks_asset->result_array() as $a): $no++?>
                  <tr>
                    <td><?= $no ?></td>
                    <td><?= $a['cabang_asuransi'] ?></td>
                    <td><?= $a['cabang_bank'] ?></td>
                    <td><?= $a['bank'] ?></td>
                    <td><?= $a['no_reff'] ?></td>
                    <td><?= $a['nama_debitur'] ?></td>
                    <td><?= $a['no_klaim'] ?></td>
                    <td><?= $a['jenis_asset'] ?></td>
                    <td><?= $a['sifat_asset'] ?></td>
                    <td align="right">Rp. <?= number_format($a['harga'],0,'.','.') ?></td>
                    <td><?= $a['status_asset'] ?></td>
                    <td></td>
                  </tr>
                <?php endforeach ?>
            <?php else: ?>

              <tr>
                <td colspan="12" align="center">DATA KOSONG</td>
              </tr>
              
            <?php endif ?>
            
          </tbody>
        </table>
</body>
</html>