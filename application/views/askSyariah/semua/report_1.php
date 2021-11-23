<!doctype html>
<html>
    <head>
        <title>Print R. OTS</title>

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/bower_components/font-awesome/css/font-awesome.min.css">

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
        <form method="POST" target="_self" id="tab" action="<?= base_url('askSyariah/R_semuaSyariah/unduh_data') ?>">
          <input type="hidden" name="jenis_report" value="<?= $jenis_report ?>">
          <input type="hidden" name="bulan_awal" value="<?= $d_bulan_awal ?>">
          <input type="hidden" name="bulan_akhir" value="<?= $d_bulan_akhir ?>">
          <input type="hidden" name="verifikator" value="<?= $d_verifikator ?>">

          <button name="pdf" onclick="b()" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i><?= nbs(2) ?>UNDUH - PDF</button><?= nbs(5) ?>
          <button name="excel" onclick="b()" class="btn btn-success"><i class="fa fa-file-excel-o"></i><?= nbs(2) ?>UNDUH - EXCEL</button>

        </form><br>
      <?php endif ?>

      <h5>REKAP OTS DAN RECOVERIES BERDASARKAN HASIL OTS VERIFIKATOR</h5>
      <h6>PERIODE <?php $a = substr($d_bulan_awal, -2); echo $b = strtoupper(bln($a)); ?> - <?= strtoupper(bln_indo($d_bulan_akhir)) ?></h6>
      
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


          <?php if (empty($verifikator_1->result_array())): ?>

            <tr>
              <td colspan="8" align="center">DATA KOSONG</td>
            </tr>

          <?php else: ?>

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
                <?php $hasil = $this->M_all_report_syariah->get_jml_noa_report1($bln, $v['id_karyawan']) ?>
                <td align="right"><?= $hasil['jml_noa_r1'] ?></td>
                <td align="right"><?= number_format($hasil['shs_r1'],0,'.','.') ?></td>

                <?php array_push($jml_noa, $hasil['jml_noa_r1']) ?>
                <?php array_push($jml_shs, $hasil['shs_r1']) ?>

                <?php $hasil_2 = $this->M_all_report_syariah->get_recov_invo_report1($bln, $v['id_karyawan']) ?>
                <td align="right"><?= number_format($hasil_2['tot_recov'],0,'.','.') ?></td> 

                <?php array_push($jml_rec_inv, $hasil_2['tot_recov']) ?>

                <?php $hasil_3 = $this->M_all_report_syariah->get_noa_recov_report1($bln, $v['id_karyawan']) ?>
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
              <td align="right"><?= number_format($v = $t / count($f),2,',','.') ?> %</td>
            </tr>

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