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

      <h5>LAPORAN OTS <?php $a = substr($d_bulan_awal, -2); echo $b = strtoupper(bln($a)); ?> - <?= strtoupper(bln_indo($d_bulan_akhir)) ?></h5>

      <?php 

      $bulan = ['bulan','Januari', 'Februari', 'Maret' ,'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November' ,'Desember'];
      ?>
      <?php $f = array(); ?>
      <?php for ($e = $awal; $e <= $akhir; $e++) : ?>
        <?php array_push($f, $e) ?>
      <?php endfor ?>
      
      <table border="1" id="ad" width="100%">
        <thead style="background-color: #122E5D; color: white;">
          <tr>
            <th rowspan="3">Area</th>
            <th rowspan="3">Kelolaan</th>
            <th rowspan="2" colspan="2">TOTAL KELOLAAN</th>
            <?php $s = (count($f)*2) ?>
            <th colspan="<?= $s ?>">OTS</th>
          </tr>
          <tr>
            <?php for ($i = $awal; $i <= $akhir; $i++) : ?>
              <th colspan="2"><?= $bulan[$i] ?></th>
            <?php endfor ?>
          </tr>
          <tr>
            <th>NOA</th>
            <th>SHS</th>
            <?php for ($h = $awal; $h <= $akhir; $h++) : ?>
              <th>NOA</th>
              <th>SHS</th>
            <?php endfor ?>
          </tr>
        </thead>
        <tbody>
          
          <!-- ------------ -->
          <!--- JAWA BARAT --->
          <!-- ------------ -->

          <?php $j=0; for ($h = $awal; $h <= $akhir; $h++) : ?>

                <?php 

                  $jml_noa = 'jml_noa_'.$j;
                  $jml_shs = 'jml_shs_'.$j;

                  $$jml_noa = array();
                  $$jml_shs = array();

                ?>

          <?php $j++; endfor ?>

          <?php $jml_noa_s = 0; $jml_shs_s = 0;$jl_noa_a = 0; $jl_shs_a = 0; $no=0;

          foreach ($nama_ver_jabar as $v): ?>

            <tr>
              <td align="center"><?= ($no == 0) ? $v['area'] : '' ?></td>
              <td><?= $v['nama_lengkap'] ?></td>
              <td align="right"><?= $v['jml_noa'] ?></td>
              <td align="right"><?= number_format($v['shs'],0,'.','.') ?></td>

              <?php $jl_noa = 0; $jl_shs = 0; $a = array(); $b = array(); ?>

              <?php $j=0; for ($h = $awal; $h <= $akhir; $h++) : ?>

                <?php $bln = tambah_bulan($d_bulan_awal, $j) ?>
                <?php $noa = $this->M_all_report->get_data_jml_noa(1,$bln, $v['id_karyawan']); ?>
                <td align="right"><?= $noa['jml_noa_jbr'] ?></td>
                <td align="right"><?= number_format($noa['shs_jbr'],0,'.','.') ?></td>

                <?php $jl_noa += $noa['jml_noa_jbr']; $jl_shs += $noa['shs_jbr']; ?>

              <?php array_push($a, $noa['jml_noa_jbr']) ?>
              <?php array_push($b, $noa['shs_jbr']) ?>

              <?php $j++; endfor ?>

              <!-- <?php $xx = $a[1] ?>
              <?php $sh = $b[1] ?>
              <?php array_push($d, $xx) ?>
              <?php array_push($e, $sh) ?> -->

              <?php $j=0; for ($h = $awal; $h <= $akhir; $h++) : ?>

                <?php 

                  $xx = $a[$j];
                  $zz = $b[$j];

                  $jml_noa = 'jml_noa_'.$j;
                  $jml_shs = 'jml_shs_'.$j;

                  array_push($$jml_noa, $xx); 
                  array_push($$jml_shs, $zz); 

                ?>

              <?php $j++; endfor ?>

            </tr>

            <?php $jml_noa_s += $v['jml_noa'];  ?>
            <?php $jml_shs_s += $v['shs']; ?>
            
          <?php $no++; endforeach ?>

          <!-- <?php $s = array_sum($d); ?>
          <?php $v = array_sum($e); ?> -->

          <?php $j=0; for ($h = $awal; $h <= $akhir; $h++) : ?>

                <?php 

                  $jml_noa = 'jml_noa_'.$j;
                  $jml_shs = 'jml_shs_'.$j;

                  $s = 'tot_noa_'.$j;
                  $v = 'tot_shs_'.$j;

                  $$s = array_sum($$jml_noa);
                  $$v = array_sum($$jml_shs);

                ?>

          <?php $j++; endfor ?>

          <tr id="d">
            <th colspan="2">Jawa Barat Total</th>
            <th style="text-align: right;"><?= $jml_noa_s ?></th>
            <th style="text-align: right;"><?= number_format($jml_shs_s,0,'.','.') ?></th>

            <?php $k=0; for ($p = $awal; $p <= $akhir; $p++) : ?>

              <?php $bln_2 = tambah_bulan($d_bulan_awal, $k) ?>
              <?php $j_noa = $this->M_all_report->get_data_jml_noa_s(1,$bln_2); ?>
              <?php 

              $jml_noa = 'tot_noa_'.$k;
              $jml_shs = 'tot_shs_'.$k;

               ?>
              <th style="text-align: right;"><?= $$jml_noa ?></th>
              <th style="text-align: right;"><?= number_format($$jml_shs,0,'.','.') ?></th>

            <?php $k++; endfor ?>

          </tr>


          <!-- ------------ -->
          <!-- JAWA TENGAH --->
          <!-- ------------ -->

          <?php $j=0; for ($h = $awal; $h <= $akhir; $h++) : ?>

                <?php 

                  $jml_noa_t = 'jml_noa_t_'.$j;
                  $jml_shs_t = 'jml_shs_t_'.$j;

                  $$jml_noa_t = array();
                  $$jml_shs_t = array();

                ?>

          <?php $j++; endfor ?>

          <?php $jml_noa_x = 0; $jml_shs_x = 0; $jl_noa_a_x = 0; $jl_shs_a_x = 0; $no=0;

          foreach ($nama_ver_jateng as $v): ?>
            <tr>
              <td align="center"><?= ($no == 0) ? $v['area'] : '' ?></td>
              <td><?= $v['nama_lengkap'] ?></td>
              <td align="right"><?= $v['jml_noa'] ?></td>
              <td align="right"><?= number_format($v['shs'],0,'.','.') ?></td>

              <?php $jl_noa = 0; $jl_shs = 0; $a_t = array(); $b_t = array(); ?>

              <?php $j=0; for ($h = $awal; $h <= $akhir; $h++) : ?>

                <?php $bln = tambah_bulan($d_bulan_awal, $j) ?>
                <?php $noa = $this->M_all_report->get_data_jml_noa(3,$bln, $v['id_karyawan']); ?>
                <td align="right"><?= $noa['jml_noa_jbr'] ?></td>
                <td align="right"><?= number_format($noa['shs_jbr'],0,'.','.') ?></td>

                <?php $jl_noa += $noa['jml_noa_jbr']; $jl_shs += $noa['shs_jbr']; ?>

                <?php array_push($a_t, $noa['jml_noa_jbr']) ?>
                <?php array_push($b_t, $noa['shs_jbr']) ?>

              <?php $j++; endfor ?>

              <?php $j=0; for ($h = $awal; $h <= $akhir; $h++) : ?>

                <?php 

                  $xx_t = $a_t[$j];
                  $zz_t = $b_t[$j];

                  $jml_noa_t = 'jml_noa_t_'.$j;
                  $jml_shs_t = 'jml_shs_t_'.$j;

                  array_push($$jml_noa_t, $xx_t); 
                  array_push($$jml_shs_t, $zz_t); 

                ?>

              <?php $j++; endfor ?>

            </tr>

            <?php $jml_noa_x += $v['jml_noa'];  ?>
            <?php $jml_shs_x += $v['shs']; ?>
            
          <?php $no++; endforeach ?>

          <?php $j=0; for ($h = $awal; $h <= $akhir; $h++) : ?>

                <?php 

                  $jml_noa_t = 'jml_noa_t_'.$j;
                  $jml_shs_t = 'jml_shs_t_'.$j;

                  $s_t = 'tot_noa_t_'.$j;
                  $v_t = 'tot_shs_t_'.$j;

                  $$s_t = array_sum($$jml_noa_t);
                  $$v_t = array_sum($$jml_shs_t);

                ?>

          <?php $j++; endfor ?>

          <tr id="d">
            <th colspan="2">Jawa Tengah Total</th>
            <th style="text-align: right;"><?= $jml_noa_x ?></th>
            <th style="text-align: right;"><?= number_format($jml_shs_x,0,'.','.') ?></th>

            <?php $k=0; for ($p = $awal; $p <= $akhir; $p++) : ?>

              <?php $bln_2 = tambah_bulan($d_bulan_awal, $k) ?>
              <?php $j_noa = $this->M_all_report->get_data_jml_noa_s(3,$bln_2); ?>
              <?php 

              $jml_noa_t = 'tot_noa_t_'.$k;
              $jml_shs_t = 'tot_shs_t_'.$k;

               ?>
              <th style="text-align: right;"><?= $$jml_noa_t ?></th>
              <th style="text-align: right;"><?= number_format($$jml_shs_t,0,'.','.') ?></th>

            <?php $k++; endfor ?>

          </tr>


          <!-- GRAND TOTAL -->

          <tr id="d">
            <th colspan="2">Grand Total</th>
            <th style="text-align: right;"><?= $jml_noa_s + $jml_noa_x  ?></th>
            <th style="text-align: right;"><?= number_format($jml_shs_s + $jml_shs_x,0,'.','.')  ?></th>

            <?php $u=0; for ($e = $awal; $e <= $akhir; $e++) : ?>

            <?php $data = ['n.id_bank'  => 1, 'n.id_bank' => 3]; ?>
            <?php $bln_t = tambah_bulan($d_bulan_awal, $u) ?>
            <?php $noa_t = $this->M_all_report->get_data_jml_noa_tot($bln_t); ?>
            <?php 

              $jml_noa_t = 'tot_noa_t_'.$u;
              $jml_shs_t = 'tot_shs_t_'.$u;

              $jml_noa = 'tot_noa_'.$u;
              $jml_shs = 'tot_shs_'.$u;

            ?>
            <th style="text-align: right;"><?= $$jml_noa_t + $$jml_noa ?></th>
            <th style="text-align: right;"><?= number_format($$jml_shs_t + $$jml_shs,0,'.','.') ?></th>

            <?php $u++; endfor ?>

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