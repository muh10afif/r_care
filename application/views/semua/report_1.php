<!doctype html>
<html>
    <head>
        <title>Print R. OTS</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>

    #ad thead tr th {
      vertical-align: middle;
      text-align: center;
    }

    th, td {
      padding: 5px;
      font-size: 10px;
    }

    th {
      text-align: center;
    }

    thead tr th {
      background-color: #122E5D; color: white;
    }
    .a tr td {
      font-weight: bold;
    }
    body {
      margin: 20px 20px 20px 20px;
      color: black;
    }
    h5, h6 {
      font-weight: bold;
      text-align: center;
    }
    #d th {
      background-color: #122E5D; color: white;
    }
    #tot {
      background-color: #d2e0f7; font-weight: bold;
    }
    #tot_1 {
      font-weight: bold;
    }
    </style>
    </head>
    <body>
      <?php if ($kondisi == 'lihat'): ?>
        <form method="POST" target="_self" id="tab" action="<?= base_url('r_semua/unduh_data') ?>">
          <input type="hidden" name="jenis_report" value="<?= $jenis_report ?>">
          <input type="hidden" name="bulan_awal" value="<?= $d_bulan_awal ?>">
          <input type="hidden" name="bulan_akhir" value="<?= $d_bulan_akhir ?>">
          <input type="hidden" name="verifikator" value="<?= $d_verifikator ?>">
          <input type="hidden" name="spk" value="<?= $spk_manager ?>">

          <button name="pdf" onclick="b()" class="btn btn-primary">UNDUH - PDF</button><?= nbs(5) ?>
          <button name="excel" onclick="b()" class="btn btn-success">UNDUH - EXCEL</button>

        </form><br>
      <?php endif ?>

      <h5>REKAP OTS DAN RECOVERIES BERDASARKAN HASIL OTS VERIFIKATOR</h5>
      <h6>PERIODE <?php echo $b = strtoupper(bln_indo($d_bulan_awal)); ?> - <?= strtoupper(bln_indo($d_bulan_akhir)) ?></h6>
      
      <?php $f = array(); ?>
      <?php for ($e = $awal; $e <= $akhir; $e++) : ?>
        <?php array_push($f, $e) ?>
      <?php endfor ?>

      <table border="1" id="ad" width="100%">
        <thead style="background-color: #122E5D; color: white;" class="a">
          <tr>
            <th rowspan="2">VERIFIKATOR</th>
            <th rowspan="2">BULAN</th>
            <th colspan="2">JUMLAH OTS</th>
            <th rowspan="2"><?php $a = "RECOVERIES KELOLAAN by INVOICE"; echo wordwrap($a,15,"<br>\n"); ?></th>
            <th colspan="3">HASIL OTS by RECOVERIES</th>
          </tr>
          <tr>
            <th>NOA</th>
            <th>SHS</th>
            <th><?php $b = "NOA yang pernah di OTS"; echo wordwrap($b,15,"<br>\n"); ?></th>
            <th><?php $c = "NOA Bayar (recoveries)"; echo wordwrap($c,15,"<br>\n"); ?></th>
            <th><?php $c = "Prosentase Hasil OTS yang Bayar ke Bank"; echo wordwrap($c,15,"<br>\n"); ?>
          </tr>
        </thead>
        <tbody>

          <?php $bulan = ['bulan','Januari', 'Februari', 'Maret' ,'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November' ,'Desember']; ?>

          <?php $tot_r1 = 0; $shs_r1 = 0; $tot_rec = 0; $tot_noa_s = 0; $tot_rec_s = 0; $tot_persen = 0;?>
          <?php $an = 0; foreach ($verifikator_1->result_array() as $v): ?>
              
            <?php 
            $jml_noa = array(); $jml_shs = array(); $jml_rec_inv = array(); 
            $jml_noa_recov = array(); $jml_shs_recov = array();
            ?>

            <?php $no=0; $t = 0; for ($i = $awal; $i <= $akhir; $i++) : ?>
              <?php if ($an%2 == 0): ?>
                <tr style="background-color: #d2e0f7" >
              <?php else: ?>
                <tr>
              <?php endif ?>
                <td align="center" style="font-weight: bold;"><?= ($no == 0) ? $v['nama_lengkap'] : '' ?></td>
                <td><?= $bulan[$i] ?></td>
                <?php $bln = tambah_bulan($d_bulan_awal, $no) ?>

                <!-- jumlah OTS -->
                <?php $jumlah_noa_ots = $this->M_all_report->get_jumlah_noa_ots($bln, $v['id_karyawan'])->num_rows(); ?>
                <?php $hasil = $this->M_all_report->get_jml_noa_report1($bln, $v['id_karyawan']) ?>
                <td align="right"><?= $jumlah_noa_ots ?></td>
                <td align="right"><?= number_format($hasil['shs_r1'],0,'.','.') ?></td>

                <?php array_push($jml_noa, $jumlah_noa_ots) ?>
                <?php array_push($jml_shs, $hasil['shs_r1']) ?>

                <!-- recoveries kelolaan by invoice -->
                <?php $hasil_2 = $this->M_all_report->get_recov_invo_report1($bln, $v['id_karyawan']) ?>
                <td align="right"><?= number_format($hasil_2['tot_recov'],0,'.','.') ?></td> 

                <?php array_push($jml_rec_inv, $hasil_2['tot_recov']) ?>

                <!-- hasil OTS by recoveries -->
                <?php $hasil_3 = $this->M_all_report->get_noa_recov_report1($bln, $v['id_karyawan']) ?>
                <td align="right"><?= $hasil_3['tot_noa'] ?></td>
                <td align="right"><?= number_format($hasil_3['tot_recov'],0,'.','.') ?></td>

                <?php array_push($jml_noa_recov, $hasil_3['tot_noa']) ?>
                <?php array_push($jml_shs_recov, $hasil_3['tot_recov']) ?>

                <td align="right">
                  <?php if ($hasil_2['tot_recov'] == 0): ?>
                    <?php $q = 0; ?>
                  <?php else: ?>
                    <?php $q = ($hasil_3['tot_recov'] / $hasil_2['tot_recov']) * 100;?>
                  <?php endif ?>
                  <?= number_format($q,2,',','.') ?>
                   %</td>
                   <?php $t += $q ?>
              </tr>
            <?php $no++; endfor ?>

           <?php if ($an%2 == 0): ?>
             <tr id="tot">
            <?php else: ?>
            <tr id="tot_1">
            <?php endif ?>
              <td colspan="2" style="text-align: center;">Total</td>
              <td align="right"><?= $tot_jml_noa = array_sum($jml_noa) ?></td>
              <td align="right"><?= number_format($tot_shs_noa = array_sum($jml_shs),0,'.','.') ?></td>

              <td align="right"><?= number_format($tot_rec_inv = array_sum($jml_rec_inv),0,'.','.') ?></td>

              <td align="right"><?= $tot_noa_recov = array_sum($jml_noa_recov) ?></td>
              <td align="right"><?= number_format($tot_shs_recov = array_sum($jml_shs_recov),0,'.','.') ?></td>

              <?php if (count($f) == 0) : ?>
                  <td align="right">0 %</td>
              <?php else : ?>
                  <td align="right"><?= number_format($v = $t / count($f),2,',','.') ?> %</td>
              <?php endif ?>

            </tr>

            <?php (count($f) == 0) ? $v = 0 : $v = $t / count($f) ?>

            <?php 

            $tot_r1     += $tot_jml_noa;
            $shs_r1     += $tot_shs_noa;
            $tot_rec    += $tot_rec_inv;
            $tot_noa_s  += $tot_noa_recov;
            $tot_rec_s  += $tot_shs_recov;
            $tot_persen += $v;

             ?>

          <?php $an++; endforeach ?>

            <?php $r = count($verifikator->result_array()) ?>

            <tr id="d">
              <th colspan="2">GRAND TOTAL</th>
              <th style="text-align: right;"><?= $tot_r1 ?></th>
              <th style="text-align: right;"><?= number_format($shs_r1,0,'.','.') ?></th>

              <th style="text-align: right;"><?= number_format($tot_rec,0,'.','.') ?></th>
              <th style="text-align: right;"><?= number_format($tot_noa_s,0,'.','.') ?></th>
              <th style="text-align: right;"><?= number_format($tot_rec_s,0,'.','.') ?></th>
              <th style="text-align: right;"><?= number_format($tot_persen / $r,2,',','.') ?> %</th>
            </tr>

        </tbody>
      </table>

</body>
</html>