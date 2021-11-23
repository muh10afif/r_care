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

      <h5>LAPORAN DEBITUR YANG SUDAH DIKUNJUNGI <?php $a = substr($d_bulan_awal, -2); echo $b = strtoupper(bln($a)); ?> - <?= strtoupper(bln_indo($d_bulan_akhir)) ?></h5>

      <?php 

      $bulan = ['bulan','Januari', 'Februari', 'Maret' ,'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November' ,'Desember'];
      ?>
      <?php $f = array(); ?>
      <?php for ($e = $awal; $e <= $akhir; $e++) : ?>
        <?php array_push($f, $e) ?>
      <?php endfor ?>
      
      <table border="1" id="ad" width="100%">
        <thead>
          <tr>
            <th rowspan="3">Area</th>
            <th rowspan="3">Kelolaan</th>
            <th rowspan="2" colspan="2">TOTAL KELOLAAN</th>
            <?php $s = (count($f)*2) ?>
            <th colspan="<?= $s ?>">OTS</th>
            <th rowspan="3"><?php $c = "TOTAL NOA YG SUDAH DI OTS"; echo wordwrap($c,8,"<br>\n"); ?></th>
            <th rowspan="3"><?php $c = "TOTAL SHS YG SUDAH DI OTS"; echo wordwrap($c,16,"<br>\n"); ?></th>
            <th colspan="2" rowspan="2">YANG BELUM DI OTS</th>
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
            <th>NOA</th>
            <th>SHS</th>
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

          <?php $jml_noa_s = 0; $jml_shs_s = 0;$jl_noa_a = 0; $jl_shs_a = 0; $no=0; $ns_jml_noa=0; $ns_jml_shs=0; $j_shs_jml=0;
          $j_shs_sdh_jml = 0;

          foreach ($nama_ver_jabar as $v): ?>

            <tr>
              <td align="center"><?= ($no == 0) ? $v['area'] : '' ?></td>
              <td><?= $v['nama_lengkap'] ?></td>
              <td align="right"><?= $v['jml_noa'] ?></td>
              <td align="right"><?= $j_shs = number_format($v['shs'],0,'.','.') ?></td>

              <?php 

              $j_shs_r = str_replace('.','', $j_shs);
                            
              $j_shs_jml += $j_shs_r ?>

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

              <?php $noa_sudah = $this->M_all_report->get_data_jml_noa_sdh(1,$v['id_karyawan']); ?>
              <td align="right"><?= $noa_sudah['jml_noa_sdh'] ?></td>
              <td align="right"><?= $j_shs_sdh = number_format($noa_sudah['shs_sdh'],0,'.','.') ?></td>

              <?php 
                $j_shs_sdh_r = str_replace('.','', $j_shs_sdh);
                $j_shs_sdh_jml += $j_shs_sdh_r;
              ?>

              <td align="right"><?= $v['jml_noa'] - $noa_sudah['jml_noa_sdh'] ?></td>
              <td align="right"><?php $tt = $v['shs'] - $noa_sudah['shs_sdh']; echo number_format($tt,0,'.','.'); ?></td>

            </tr>

            <?php $jml_noa_s += $v['jml_noa'];  ?>
            <?php $jml_shs_s += $v['shs']; ?>
            <?php $jl_noa_a  += $jl_noa ?>
            <?php $jl_shs_a  += $jl_shs ?>

            <?php $ns_jml_noa += $noa_sudah['jml_noa_sdh'] ?>
            <?php $ns_jml_shs += $noa_sudah['shs_sdh'] ?>
            
          <?php $no++; endforeach ?>

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
                <th style="text-align: right;"><?= number_format($j_shs_jml,0,'.','.') ?></th>

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

                <?php $tot_noa_sdh = $this->M_all_report->get_data_tot_noa_sdh(1,$d_bulan_awal,$d_bulan_akhir); ?>
                <th style="text-align: right;"><?= $ttn = $ns_jml_noa ?></th>
                <th style="text-align: right;"><?= number_format($j_shs_sdh_jml,0,'.','.') ?></th><?php $ttn_shs = $j_shs_sdh_jml; ?>

                <th style="text-align: right;"><?= $ttn_bl = $jml_noa_s - $ns_jml_noa ?></th>
                <th style="text-align: right;"><?php $aa_bl = $j_shs_jml - $j_shs_sdh_jml; echo number_format($aa_bl,0,'.','.'); ?></th>
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

          <?php $jml_noa_x = 0; $jml_shs_x = 0; $jl_noa_a_x = 0; $jl_shs_a_x = 0; $no=0; $j_shs_jml_x=0; $j_shs_sdh_jml_x=0;

          foreach ($nama_ver_jateng as $v): ?>
            <tr>
              <td align="center"><?= ($no == 0) ? $v['area'] : '' ?></td>
              <td><?= $v['nama_lengkap'] ?></td>
              <td align="right"><?= $v['jml_noa'] ?></td>

              <td align="right"><?= $j_shs = number_format($v['shs'],0,'.','.') ?></td>

              <?php 

              $j_shs_r = str_replace('.','', $j_shs);
                            
              $j_shs_jml_x += $j_shs_r ?>

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

              <?php $noa_sudah = $this->M_all_report->get_data_jml_noa_sdh(3,$v['id_karyawan']); ?>

              <td align="right"><?= $noa_sudah['jml_noa_sdh'] ?></td>

              <td align="right"><?= $j_shs_sdh = number_format($noa_sudah['shs_sdh'],0,'.','.') ?></td>

              <?php 
                $j_shs_sdh_r = str_replace('.','', $j_shs_sdh);
                $j_shs_sdh_jml_x += $j_shs_sdh_r;
              ?>

              <td align="right"><?= $v['jml_noa'] - $noa_sudah['jml_noa_sdh'] ?></td>
              <td align="right"><?php $tt = $v['shs'] - $noa_sudah['shs_sdh']; echo number_format($tt,0,'.','.'); ?></td>


            </tr>

            <?php $jml_noa_x += $v['jml_noa'];  ?>
            <?php $jml_shs_x += $v['shs']; ?>
            <?php $jl_noa_a_x  += $jl_noa ?>
            <?php $jl_shs_a_x  += $jl_shs ?>
            
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
                <th style="text-align: right;"><?= number_format($j_shs_jml_x,0,'.','.') ?></th>

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

                <?php $tot_noa_sdh = $this->M_all_report->get_data_tot_noa_sdh(3,$d_bulan_awal,$d_bulan_akhir); ?>
                <th style="text-align: right;"><?= $ttn_t = $jl_noa_a_x?></th>
                <th style="text-align: right;"><?= number_format($j_shs_sdh_jml_x,0,'.','.') ?></th><?php $ttn_shs_t = $j_shs_sdh_jml_x; ?>

                <th style="text-align: right;"><?= $ttn_bl_t = $jml_noa_x - $jl_noa_a_x ?></th>
                <th style="text-align: right;"><?php $aa_bl_t = $j_shs_jml_x - $j_shs_sdh_jml_x; echo number_format($aa_bl_t,0,'.','.'); ?></th>

              </tr>

          <!-- GRAND TOTAL -->

          <tr id="d">
            <th colspan="2">Grand Total</th>
            <th style="text-align: right;"><?= $jml_noa_s + $jml_noa_x  ?></th>
            <th style="text-align: right;"><?= number_format($j_shs_jml + $j_shs_jml_x,0,'.','.')  ?></th>

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

            <th style="text-align: right;"><?= $ttn + $ttn_t  ?></th>
            <th style="text-align: right;"><?= number_format($ttn_shs + $ttn_shs_t,0,'.','.')  ?></th>

            <th style="text-align: right;"><?= $ttn_bl + $ttn_bl_t  ?></th>
            <th style="text-align: right;"><?= number_format($aa_bl + $aa_bl_t,0,'.','.')  ?></th>

          </tr>

        </tbody>
      </table>

</body>
</html>