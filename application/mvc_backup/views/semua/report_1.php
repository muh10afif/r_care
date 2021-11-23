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
        <form method="POST" target="_self" id="tab" action="<?= base_url('r_semua/unduh_data') ?>">
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

          <?php $bulan = ['bulan','Januari', 'Februari', 'Maret' ,'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November' ,'Desember']; ?>

          <?php $tot_r1 = 0; $shs_r1 = 0; $tot_rec = 0; $tot_noa_s = 0; $tot_rec_s = 0; $tot_persen = 0;?>
          <?php $an = 0; foreach ($verifikator->result_array() as $v): ?>
              
            <?php $no=0; $t = 0; for ($i = $awal; $i <= $akhir; $i++) : ?>
              <?php if ($an%2 == 0): ?>
                <tr style="background-color: #d2e0f7" >
              <?php else: ?>
                <tr>
              <?php endif ?>
                <td align="center" style="font-weight: bold;"><?= ($no == 0) ? $v['nama_lengkap'] : '' ?></td>
                <td><?= $bulan[$i] ?></td>
                <?php $bln = tambah_bulan($d_bulan_awal, $no) ?>
                <?php $hasil = $this->M_all_report->get_jml_noa_report1($bln, $v['id_karyawan']) ?>
                <td align="right"><?= $hasil['jml_noa_r1'] ?></td>
                <td align="right"><?= number_format($hasil['shs_r1'],0,'.','.') ?></td>

                <?php $hasil_2 = $this->M_all_report->get_recov_invo_report1($bln, $v['id_karyawan']) ?>
                <td align="right"><?= number_format($hasil_2['tot_recov'],0,'.','.') ?></td> 

                <?php $hasil_3 = $this->M_all_report->get_noa_recov_report1($bln, $v['id_karyawan']) ?>
                <td align="right"><?= $hasil_3['tot_noa'] ?></td>
                <td align="right"><?= number_format($hasil_3['tot_recov'],0,'.','.') ?></td>

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
              <?php $tot_n = $this->M_all_report->get_total_noa_r1($d_bulan_awal, $d_bulan_akhir, $v['id_karyawan']); ?>
              <td align="right"><?= $tot_n['jml_tot_noa_r1'] ?></td>
              <td align="right"><?= number_format($tot_n['shs_tot_r1'],0,'.','.') ?></td>

              <?php $hasil_3 = $this->M_all_report->get_recov_invo_t_report1($d_bulan_awal, $d_bulan_akhir, $v['id_karyawan']) ?>
              <td align="right"><?= number_format($hasil_3['tot_recov'],0,'.','.') ?></td>

              <?php $hasil4 = $this->M_all_report->get_tot_noa_recov_report1($d_bulan_awal, $d_bulan_akhir, $v['id_karyawan']) ?>
              <td align="right"><?= $hasil4['tot_noa'] ?></td>
              <td align="right"><?= number_format($hasil4['tot_recov'],0,'.','.') ?></td>
              <td align="right"><?= number_format($v = $t / count($f),2,',','.') ?> %</td>

            </tr>

            <?php 

            $tot_r1     += $tot_n['jml_tot_noa_r1'];
            $shs_r1     += $tot_n['shs_tot_r1'];
            $tot_rec    += $hasil_3['tot_recov'];
            $tot_noa_s  += $hasil4['tot_noa'];
            $tot_rec_s  += $hasil4['tot_recov'];
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