<!doctype html>
<html>
    <head>
        <title>Print R. NOA</title>

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
        <form method="POST" action="<?= base_url('r_noa/unduh_data/cetak') ?>">
          <input type="hidden" name="asuransi" value="<?= $asuransi_id ?>">
          <input type="hidden" name="cabang_asuransi" value="<?= $cbg_asuransi_id ?>">
          <input type="hidden" name="bank" value="<?= $bank_id ?>">
          <input type="hidden" name="cabang_bank" value="<?= $cbg_bank_id ?>">
          <input type="hidden" name="capem_bank" value="<?= $cpm_bank_id ?>">
          <input type="hidden" name="verifikator" value="<?= $verifikator_id ?>">
          <input type="hidden" name="tgl_awal" value="<?= $tgl_awal ?>">
          <input type="hidden" name="tanggal_akhir" value="<?= $tgl_akhir ?>">
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
          <!-- <?php if($tgl_awal != '') : ?>
              <tr>
                  <td width="150px">Tanggal Awal</td>
                  <td>:</td>
                  <td><?= ($tgl_awal) ?></td>
              </tr>
          <?php endif ?> -->
          <?php if($tgl_akhir != '') : ?>
              <tr>
                  <td width="150px">Tanggal Akhir</td>
                  <td>:</td>
                  <td><?= ($tgl_akhir) ?></td>
              </tr>
          <?php endif ?>
          <?php if($spk != '') : ?>
              <tr>
                  <td width="150px">Nomer SPK</td>
                  <td>: <?= ($spk == 'No SPK') ? 'Tidak ada SPK' : $spk ?></td>
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
              <th>Subrogasi</th>
              <th>Recoveries</th>
              <th>SHS</th>
              <?php if ($id_cabang_as == '') : ?>
              <th>Saldo Tagihan</th>
              <?php endif ?>
              <th>Status</th>
              <!-- <th>Recoveries</th> -->
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data_r_noa->result_array())): ?>
              <?php $tot_shs1=0; $tot_subro=0; $t_recov=0; $tot_saldo_tagihan=0; $no=0; foreach ($data_r_noa->result_array() as $r): 
                
                $no++;
                
                $shs1 = ($r['subrogasi_as'] - $r['recoveries_awal_asuransi']) - $r['tot_nominal_as'];

                $tot_tagihan = $r['subrogasi_as'] + $r['bunga'] + $r['denda'];

                $tot_recov   = $r['recoveries_awal_asuransi'] + $r['tot_nominal_as'];

                $saldo_tagihan = $tot_tagihan + $tot_recov;

                if ($kondisi == 'excel') {
                    $shs        = $shs1;
                    $tot_shs    = $tot_shs1;
                    $recov1     = $tot_recov;
                    $subro      = $r['subrogasi_as'];
                    $tot_subro1 = $tot_subro;
                    $tot_recov1 = $t_recov;
                } else {
                    $shs          = number_format($shs1, '2', ',', '.');
                    $tot_shs      = number_format($tot_shs1, '2', ',', '.');
                    $recov1       = number_format($tot_recov, '2',',','.');
                    $subro        = number_format($r['subrogasi_as'],'2',',','.');
                    $tot_subro1   = number_format($tot_subro,'2',',','.');
                    $tot_recov1   = number_format($t_recov,'2',',','.');
                }

                ?>
                    <tr>
                      <td><?= $no ?></td>
                      <td><?= $r['cabang_asuransi'] ?></td>
                      <td><?= $r['bank'] ?></td>
                      <td><?= $r['cabang_bank'] ?></td>
                      <td><?= $r['capem_bank'] ?></td>
                      <td><?= $r['no_reff'] ?></td>
                      <td><?= $r['nama_debitur'] ?></td>
                      <td><?= $r['no_klaim'] ?></td>
                      <td align="right"><?= $subro ?></td>
                      <td align="right"><?= $recov1 ?></td>
                      <td align="right"><?= $shs ?></td>
                      <td align="center">
                        <?php 
                          if ($r['potensial'] == 1) {
                              echo "Potensial";
                          } elseif ($r['potensial'] == 0) {
                              echo "Non Potensial";
                          } elseif ($r['potensial'] == '') {
                              echo "";
                          }
                         ?></td>

                      <!-- <td align="right"><?= number_format($saldo_tagihan,0,'.','.') ?></td> -->

                      <!-- <?php if ($r['potensial'] == 1): ?>
                      <td align="center"><?= ($r['potensial'] == 1) ? 'Ya' : 'Tidak' ?></td>
                      <?php else: ?>
                      <td align="center" style="background-color: #ffb384"><?= ($r['potensial'] == 1) ? 'Ya' : 'Tidak' ?></td>
                      <?php endif ?> -->

                      <!-- <td><?= ($r['potensial'] != 1) ? 'Tunda' : $r['nama_proses'] ?></td> -->
                      <td></td>
                    </tr>
                    
                      <?php 
                      
                      $tot_shs1           += $shs1; 
                      $tot_saldo_tagihan  += $saldo_tagihan;
                      $tot_subro          += $r['subrogasi_as'];
                      $t_recov            += $tot_recov; 

                      ?>

                  <?php endforeach ?>
                  <tr>
                    <td colspan="8" align="right" style="font-weight: bold;">Jumlah Total :</td>
                    <td style="font-weight: bold; text-align: right;"><?= $tot_subro1 ?></td>
                    <td style="font-weight: bold; text-align: right;"><?= $tot_recov1 ?></td>
                    <td style="font-weight: bold; text-align: right;"><?= $tot_shs ?></td>
                    <!-- <td style="font-weight: bold; text-align: right;"><?= number_format($tot_saldo_tagihan,0,'.','.') ?></td> -->
                    <td></td>
                    <!-- <td></td> -->
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
                    <td><?= count($data_r_noa->result_array()) ?> Debitur</td>
                  </tr>
                  <tr>
                    <td>Noa Potensial</td>
                    <td><?= $noa_potensial->num_rows() ?> Debitur</td>
                  </tr>
                  <tr>
                    <td>Nilai SHS</td>
                    <td><?php $shs_p = 0; foreach ($noa_potensial->result_array() as $p): 

                        $shs_p += ($p['subrogasi_as'] - $p['recoveries_awal_asuransi']) - $p['tot_nominal_as']; 
                      ?>
                          
                    <?php endforeach ?>
                    Rp. <?= number_format($shs_p,2,',','.') ?>
                  </td>
                  </tr>
                  <!-- <tr>
                    <td>Nilai Tagihan</td>
                    <td><?php $tot_tagihan_p = 0; foreach ($noa_potensial->result_array() as $c): 
                      
                      $tot_tagihan_p = $c['subrogasi_as'] + $c['bunga'] + $c['denda'];
                      ?>
                      
                    <?php endforeach ?>
                    Rp. <?= number_format($tot_tagihan_p,2,',','.') ?></td>
                  </tr> -->
                  <tr>
                    <td>Noa Non Potensial</td>
                    <td><?= $noa_non_potensial->num_rows() ?> Debitur</td>
                  </tr>
                  <tr>
                    <td>Nilai SHS</td>
                    <td><?php $shs_n = 0; foreach ($noa_non_potensial->result_array() as $d):
                    $shs_n += ($d['subrogasi_as'] - $d['recoveries_awal_asuransi']) - $d['tot_nominal_as']; 
                      ?>
                          
                    <?php endforeach ?>
                    Rp. <?= number_format($shs_n,2,',','.') ?></td>
                  </tr>
                  <!-- <tr>
                    <td>Nilai Tagihan</td>
                    <td><?php $tot_tagihan_n = 0; foreach ($noa_non_potensial->result_array() as $e): 
                        $tot_tagihan_n = $e['subrogasi_as'] + $e['bunga'] + $e['denda'];
                      ?>
                      
                    <?php endforeach ?>
                    Rp. <?= number_format($tot_tagihan_n,2,',','.') ?></td>
                  </tr> -->
                 
                </tbody>
              </table>
              <table border="1" align="right">
                <thead>
                  <tr>
                    <th colspan="2">Proses Recoveries :</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($data_noa_jml as $d): 
                    
                    $tot_recov = $d['tot_recov_awal_as'] + $d['tot_nominal_as'];
                    
                    ?>
                    

                    <tr>
                        <td><?= $d['jml_tindakan_hukum'] ?> Debitur - <?= $d['tindakan_hukum'] ?></td>
                        <td>Rp. <?= number_format($tot_recov, 2,',','.') ?></td>
                      </tr>
                      
                  <?php endforeach ?>
                 
                </tbody>
            <?php else: ?>
              <tr>
                <td colspan="11" align="center">Data Kosong</td>
              </tr>
            <?php endif ?>
            
        </table>
</body>
</html>