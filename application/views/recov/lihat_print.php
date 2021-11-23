<!doctype html>
<html>
    <head>
        <title>Print R. Recov</title>

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
        <form method="POST" action="<?= base_url('r_recov/unduh_data/cetak') ?>">
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
              <th>Bank</th>
              <th>Cabang Bank</th>
              <th>Capem Bank</th>
              <th>Deal Reff</th>
              <th>Nama Debitur</th>
              <th>Nomor Klaim</th>
              <th>Tanggal Bayar</th>
              <th>Jumlah Bayar</th>
              <th>No Rekening</th>
              <th>Subrogasi</th>
              <th>Recoveries (Asuransi)</th>
              <th>SHS</th>
              <?php if ($id_cabang_as == ''): ?>
                <th>Nilai Tagihan</th>
                <th>Nilai Recoveries (Bank)</th>
                <th>Saldo Tagihan</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data_r_noa)): ?>
                  <?php 

                  $tot_jml_bayar      = 0;
                  $tot_shs            = 0; 
                  $tot_subro          = 0; 
                  $tot_saldo_tagihan  = 0; 
                  $tot_recov          = 0; 
                  $tot_tagihan        = 0;
                  $tot_recov_bank     = 0;

                  $no = 0;

                  foreach ($data_r_noa as $r): $no++;  
                  
                  // $recov_as   = $r['recoveries_awal_asuransi'] + $r['tot_nominal_as'];
                  // $recov_bank = $r['recoveries_awal_bank'] + $r['tot_nominal_bank'];

                  // $tot_tagihan = $subrogasi+$bunga+$denda;
                  // $saldo_tagihan = $tot_tagihan - $recoveries;

                  $nilai_tagihan1 = $r['subrogasi_as'] + $r['bunga'] + $r['denda'];
                  $saldo_tagihan1 = ($r['subrogasi_as'] + $r['bunga'] + $r['denda']) - ($r['recoveries_awal_asuransi'] + $r['tot_nominal_as']);

                  // if ($r['tot_nominal_as'] == 0) {
                  //     $shs_as = 0;
                  // } else {
                  //     $shs_as = ($r['subrogasi_as'] - $r['recoveries_awal_asuransi']) - $r['tot_nominal_as'];
                  // }

                  $shs_as1 = ($r['subrogasi_as'] - $r['recoveries_awal_asuransi']) - $r['tot_nominal_as'];

                  if ($kondisi == 'excel') {
                      $jumlah_bayar       = $r['nominal'];
                      $subrogasi_as       = $r['subrogasi_as'];
                      $recov_as           = $r['tot_nominal_as'];
                      $recov_bank         = $r['tot_nominal_bank'];
                      $nilai_tagihan      = $nilai_tagihan1;
                      $saldo_tagihan      = $saldo_tagihan1;
                      $shs_as             = $shs_as1;
                      $tot_jml_bayar1     = $tot_jml_bayar;
                      $tot_subro1         = $tot_subro;          
                      $tot_recov1         = $tot_recov;         
                      $tot_shs1           = $tot_shs;            
                      $tot_tagihan1       = $tot_tagihan;        
                      $tot_recov_bank1    = $tot_recov_bank; 
                      $tot_saldo_tagihan1 = $tot_saldo_tagihan;  
                  } else {
                      $jumlah_bayar       = number_format($r['nominal'],'2',',','.');
                      $subrogasi_as       = number_format($r['subrogasi_as'],'2',',','.');
                      $recov_as           = number_format($r['tot_nominal_as'],'2',',','.');
                      $recov_bank         = number_format($r['tot_nominal_bank'],'2',',','.');
                      $nilai_tagihan      = number_format($nilai_tagihan1,'2',',','.');
                      $saldo_tagihan      = number_format($saldo_tagihan1,'2',',','.');
                      $shs_as             = number_format($shs_as1,'2',',','.');
                      $tot_jml_bayar1     = number_format($tot_jml_bayar,'2',',','.');
                      $tot_subro1         = number_format($tot_subro,'2',',','.');           
                      $tot_recov1         = number_format($tot_recov,'2',',','.');         
                      $tot_shs1           = number_format($tot_shs,'2',',','.');            
                      $tot_tagihan1       = number_format($tot_tagihan,'2',',','.');         
                      $tot_recov_bank1    = number_format($tot_recov_bank,'2',',','.');  
                      $tot_saldo_tagihan1 = number_format($tot_saldo_tagihan,'2',',','.');   
                  }
                  
                  ?>
                    <tr>
                      <td align="center"><?= $no ?></td>
                      <td><?= $r['cabang_asuransi'] ?></td>
                      <td width="150"><?= $r['bank'] ?></td>
                      <td width="150"><?= $r['cabang_bank'] ?></td>
                      <td width="150"><?= $r['capem_bank'] ?></td>
                      <td><?= $r['no_reff'] ?></td>
                      <td width="250"><?= $r['nama_debitur'] ?></td>
                      <td><?= $r['no_klaim'] ?></td>
                      <td><?= nice_date($r['tgl_bayar'], 'd-M-Y') ?></td>
                      <td align="right"><?= $jumlah_bayar ?></td>
                      <td><?= $r['no_rek'] ?></td>
                      <td align="right"><?= $subrogasi_as ?></td>
                      <td align="right"><?= $recov_as ?></td>
                      <td align="right"><?= $shs_as ?></td>

                      <?php if ($id_cabang_as == ''): ?>
                      <td align="right"><?= $nilai_tagihan ?></td>
                      <td align="right"><?= $recov_bank ?></td>
                      <td align="right"><?= $saldo_tagihan ?></td>
                      <?php endif; ?>

                    </tr>
                    <?php 

                    $tot_jml_bayar      += $r['nominal'];
                    $tot_subro          += $r['subrogasi_as'];
                    $tot_recov          += $r['tot_nominal_as']; 
                    $tot_shs            += $shs_as1; 
                    $tot_tagihan        += $nilai_tagihan1;
                    $tot_recov_bank     += $r['tot_nominal_bank'];
                    $tot_saldo_tagihan  += $saldo_tagihan1;
                     
                    
                    ?>
                  <?php endforeach ?>
                  <tr>
                    <td colspan="9" align="right" style="font-weight: bold;">Jumlah Total :</td>
                    <td style="font-weight: bold; text-align: right;"><?= $tot_jml_bayar1 ?></td>
                    <td></td>
                    <td style="font-weight: bold; text-align: right;"><?= $tot_subro1 ?></td>
                    <td style="font-weight: bold; text-align: right;"><?= $tot_recov1 ?></td>
                    <td style="font-weight: bold; text-align: right;"><?= $tot_shs1 ?></td>
                    <?php if ($id_cabang_as == ''): ?>
                    <td style="font-weight: bold; text-align: right;"><?= $tot_tagihan1 ?></td>
                    <td style="font-weight: bold; text-align: right;"><?= $tot_recov_bank1 ?></td>
                    <td style="font-weight: bold; text-align: right;"><?= $tot_saldo_tagihan1 ?></td>
                    <?php endif; ?>
                  </tr>
                </tbody>
              </table><?= br() ?>
              <?php if ($bank != 'a'): ?>
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
                    <td align="right"><?= $tot_subro1 ?></td>
                  </tr>
                  <tr>
                    <td>Recoveries</td>
                    <td align="right"><?= $tot_recov1 ?></td>
                  </tr>
                  <tr>
                    <td>Saldo Tagihan Akhir</td>
                    <td align="right"><?= $tot_shs1 ?></td>
                  </tr>
                  <tr>
                    <td>CRP (%)</td>
                    <td align="right"><?= number_format(($tot_recov/$tot_subro),2,',','.') ?></td>
                  </tr>
                </tbody>
              </table>
              <?php endif ?>
            <?php else: ?>
              <tr>
                <?php if ($id_cabang_as == ''): ?>
                  <td colspan="12" align="center">Data Kosong</td>
                <?php else : ?>
                  <td colspan="15" align="center">Data Kosong</td>
                <?php endif; ?>
              </tr>
            <?php endif ?>
            
        
</body>
</html>