<!doctype html>
<html>
    <head>
        <title>Report Debitur yang sudah Dikunjungi</title>

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
    h4, h5, h6 {
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
        <form method="POST" target="_self" id="tab" action="<?= base_url('r_laporan/unduh_data') ?>">
          <input type="hidden" name="jenis_laporan" value="<?= $jenis_laporan ?>">
          <input type="hidden" name="start" value="<?= $d_bulan_awal ?>">
          <input type="hidden" name="end" value="<?= $d_bulan_akhir ?>">
          <input type="hidden" name="tgl_awal" value="<?= $tgl_awal ?>">
          <input type="hidden" name="tgl_akhir" value="<?= $tgl_akhir ?>">

          <button name="pdf" onclick="b()" class="btn btn-primary">UNDUH - PDF</button><?= nbs(5) ?>
          <button name="excel" onclick="b()" class="btn btn-success">UNDUH - EXCEL</button>

        </form><br>
      <?php endif ?>

      <h4>Rekap Pengeluaran Verifikator</h4>
      <h5>Periode <?= nice_date($tgl_awal, 'd-F-Y') ?> s/d <?= nice_date($tgl_akhir, 'd-F-Y') ?></h5>
      <?= br(2) ?>
      
      <table border="1" id="ad" width="100%">
        <thead style="background-color: #122E5D; color: white;">
          <tr>
            <th rowspan="2">AREA</th>
            <th rowspan="2">NAMA</th>
            <?php $s = count($bulan) + 1 ?>
            <th colspan="<?= $s ?>">PENGELUARAN PER BULAN</th>
            <th rowspan="2">RECOVERIES BY INVOICE</th>
            <th rowspan="2">FEE RECOVERIES BY INVOICE</th>
            <th rowspan="2">%</th>
          </tr>
          <tr>
            <?php foreach ($bulan as $b) : ?>
                <th><?= nice_date($b, 'F-Y') ?></th>
            <?php endforeach ?>
            <th>GRAND TOTAL</th>
          </tr>
        </thead>

        <tbody>
          
          <!-- ------------ -->
          <!--- JAWA BARAT --->
          <!-- ------------ -->

          <?php $no=0;

            $tot_recov_aju3   = 0;
            $tot_komisi_aju3  = 0;
            $tot_peng4        = 0;
            
            $nom = 1;
            foreach ($bulan as $b) {
            
              $a = 'tot_p_bulan'.$nom;

              $$a = array();

              $nom++;
            }
            
          foreach ($nama_ver as $v): $no++?>
            <tr>
              <td align='center'><?= ($no == 1) ? 'Jawa Barat' : '' ?></td>
              <td><?= $v['nama_lengkap'] ?></td>

              <?php $tot_peng2=0; $nor=1;
              foreach ($bulan as $b) : 

                $data = ['bulan_awal'   => $d_bulan_awal,
                         'bulan_akhir'  => $d_bulan_akhir,
                         'tgl_awal'     => $tgl_awal,
                         'tgl_akhir'    => $tgl_akhir,
                         'bulan'        => $b,
                         'nik'          => $v['nik'],
                         'jumlah'       => count($bulan),
                         'total'        => 'tidak'
                        ];
              
              ?>
                <?php $hasil = $this->M_laporan->get_pengeluran_sps($data)->row_array();

                  if (isset($_POST['excel'])) {
                    $tot_peng = ($hasil['tot_pengeluaran'] == '') ? 0 : $hasil['tot_pengeluaran'];
                  } else {
                    $tot_peng = number_format($hasil['tot_pengeluaran'],'2',',','.');
                  }

                  $h_peng_bln = 'tot_p_bulan'.$nor;

                  array_push($$h_peng_bln, $hasil['tot_pengeluaran']);
                    
                ?>
                  <td align="right"><?= $tot_peng ?></td>

                <?php

                $tot_peng2 += $hasil['tot_pengeluaran'];

                $nor++;
              
                endforeach;
                
                if (isset($_POST['excel'])) {
                  $tot_peng_3 = $tot_peng2;
                } else {
                  $tot_peng_3 = number_format($tot_peng2,'2',',','.');
                }


                $tot_peng4 += $tot_peng2;

                ?>

                <td align="right"><?= $tot_peng_3 ?></td>

                <?php

                  $tot_recov_aju  = 0;
                  $tot_komisi_aju = 0;

                  $hasil_2 = $this->M_laporan->get_recov_invoice($v['id_karyawan'])->result_array();

                  foreach ($hasil_2 as $h) {
                      $tot_recov_aju  += $h['recoveries_aju'];
                      $tot_komisi_aju += $h['komisi_diajukan'];
                  }

                  if (isset($_POST['excel'])) {
                    $tot_recov_aju2  = $tot_recov_aju;
                    $tot_komisi_aju2 = $tot_komisi_aju;
                  } else {
                    $tot_recov_aju2  = number_format($tot_recov_aju,'2',',','.');
                    $tot_komisi_aju2 = number_format($tot_komisi_aju,'2',',','.');
                  }

                  $tot_recov_aju3   += $tot_recov_aju;
                  $tot_komisi_aju3  += $tot_komisi_aju;

                ?>

                <td align="right"><?= $tot_recov_aju2 ?></td>
                <td align="right"><?= $tot_komisi_aju2 ?></td>
                <td></td>
              
            </tr>
          <?php endforeach; ?>

          <tr id="d">

            <th colspan="2">Total</th>   
            <?php $no=1; $tp = array(); foreach ($bulan as $b) : 

                $ab = 'tot_p_bulan'.$no;
              
              ?>

              <?php 
              
              $t = 'tot_p_bulan'.$no;
              
              $$t = array_sum($$ab); 
              
              if (isset($_POST['excel'])) {
                $total_bulan = $$t;
              } else {
                $total_bulan = number_format($$t,'2',',','.');
              }
              
              ?>

              <th style="text-align: right"><?= $total_bulan; ?></th>

            <?php $no++; endforeach;
            
              if (isset($_POST['excel'])) {
                $tot_recov_aju4   = $tot_recov_aju3;
                $tot_komisi_aju4  = $tot_komisi_aju3;
                $tot_peng5        = $tot_peng4;
              } else {
                $tot_recov_aju4   = number_format($tot_recov_aju3,'2',',','.');
                $tot_komisi_aju4  = number_format($tot_komisi_aju3,'2',',','.');
                $tot_peng5        = number_format($tot_peng4,'2',',','.');
              }
            
            ?>

              <th style="text-align: right"><?= $tot_peng5 ?></th>
              <th style="text-align: right"><?= $tot_recov_aju4 ?></th>
              <th style="text-align: right"><?= $tot_komisi_aju4 ?></th>
              <th></th>

          </tr>
          
        </tbody>
      </table>

</body>
</html>