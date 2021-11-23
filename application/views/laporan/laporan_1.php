<!doctype html>
<html>
    <head>
        <title>Laporan Keuangan 1</title>

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

    tr th {
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
        <form method="POST" target="_self" id="tab" action="<?= base_url('r_laporan/unduh_data') ?>">

          <input type="hidden" name="jenis_laporan" value="<?= $jenis_laporan ?>">
          <input type="hidden" name="start" value="<?= $d_bulan_awal ?>">
          <input type="hidden" name="end" value="<?= $d_bulan_akhir ?>">

          <button name="pdf" onclick="b()" class="btn btn-primary">UNDUH - PDF</button><?= nbs(5) ?>
          <button name="excel" onclick="b()" class="btn btn-success">UNDUH - EXCEL</button>

        </form><br>
      <?php endif ?>

      <h4>REKAP BIAYA DAN PENDAPATAN DIVISI SUBROGASI</h4>
      <h6>BERDASARKAN INVOICE YANG DITAGIHKAN</h6>
      <h6>PERIODE <?= strtoupper(bln_indo($d_bulan_awal)) ?> - <?= strtoupper(bln_indo($d_bulan_akhir)) ?></h6>
      <br>  

      <table border="1" id="ad" width="100%">
        <thead style="background-color: #122E5D; color: white;" class="a">
          <tr>
            <th>KETERANGAN BIAYA</th>
            <?php foreach ($bulan as $c): ?>
            <th><?= nice_date($c, 'F-Y') ?></th>
            <?php endforeach ?>
            <th>TOTAL</th>
          </tr>
        </thead>
        <tbody>

        <?php if (count($data_report) == 0) : $bln = count($bulan); ?>

            <tr>
                <td align="center" colspan="<?= 2+$bln ?>"><b>Data Kosong</b></td>
            </tr>

        <?php else : ?>
        
        <?php foreach ($data_report as $c) : ?>

            <tr>
                <td><?= $c['deskripsi_coa'] ?></td>

                <?php $tot_coa=0; foreach ($bulan as $d): ?>

                    <?php $hasil = $this->M_laporan->get_cetak_coa($d, $c['no_coa_des'])->row_array(); 
                    
                        if (isset($_POST['excel'])) {
                            $ha_tot = ($hasil['total'] == null) ? 0 : $hasil['total'];
                        } else {
                            $ha_tot = number_format($hasil['total'],'2',',','.');
                        }
                    
                    ?>

                    <td align="right"><?= $ha_tot; ?></td>

                    <?php $tot_coa += $hasil['total'];  ?>

                    <?php endforeach;
                    
                        if (isset($_POST['excel'])) {
                            $tot_c = ($tot_coa == null) ? 0 : $tot_coa;
                        } else {
                            $tot_c = number_format($tot_coa,'2',',','.');
                        }

                    ?>

                <td align="right"><?= $tot_c ?></td>

            </tr>

        <?php endforeach ?>

            <tr>
                <th style="text-align: right">Jumlah Pengeluaran</th>

                <?php $tot_coa=0; foreach ($bulan as $d): ?>

                    <?php $hasil = $this->M_laporan->get_cetak_coa($d, '')->row_array();

                        if (isset($_POST['excel'])) {
                            $ha_tot = ($hasil['total'] == null) ? 0 : $hasil['total'];
                        } else {
                            $ha_tot = number_format($hasil['total'],'2',',','.');
                        }
                    
                    ?>

                    <th style="text-align: right"><?= $ha_tot; ?></th>

                    <?php $tot_coa += $hasil['total'];  ?>

                    <?php endforeach;

                        if (isset($_POST['excel'])) {
                            $tot_c = ($tot_coa == null) ? 0 : $tot_coa;
                        } else {
                            $tot_c = number_format($tot_coa,'2',',','.');
                        }
                    
                    ?>

                <th style="text-align: right"><?= $tot_c ?></th>

            </tr>

            <tr>
                <th style="text-align: right">Jumlah Pendapatan (Invoicing)</th>
                
                <?php $tot_inv=0; foreach ($bulan as $d): ?>

                    <?php $hasil = $this->M_laporan->get_pendapatan_invoice($d)->row_array();
                    
                        if (isset($_POST['excel'])) {
                            $ha_tot = ($hasil['total'] == null) ? 0 : $hasil['total'];
                        } else {
                            $ha_tot = number_format($hasil['total'],'2',',','.');
                        }

                    ?>

                    <th style="text-align: right"><?= $ha_tot; ?></th>

                    <?php $tot_inv += $hasil['total'];  ?>

                <?php endforeach;

                    if (isset($_POST['excel'])) {
                        $tot_ci = ($tot_inv == null) ? 0 : $tot_inv;
                    } else {
                        $tot_ci = number_format($tot_inv,'2',',','.');
                    }
                
                ?>       
                
                <th style="text-align: right"><?= $tot_ci ?></th>
            </tr>

            <tr>
                <th style="text-align: right">Rugi - Laba</th>
                
                <?php $tot_rl=0; foreach ($bulan as $d): ?>

                    <?php $hasil = $this->M_laporan->get_cetak_coa($d, '')->row_array(); ?>

                    <?php $hasil2 = $this->M_laporan->get_pendapatan_invoice($d)->row_array(); ?>

                    <?php $tot_rl = $hasil2['total'] - $hasil['total'];
                    
                        if (isset($_POST['excel'])) {
                            $tot_rl_t = ($tot_rl == null) ? 0 : $tot_rl;
                        } else {
                            $tot_rl_t = number_format($tot_rl,'2',',','.');
                        }
                    
                    ?>

                    <th style="text-align: right"><?= $tot_rl_t; ?></th>

                    <?php endforeach;

                        $rl = $tot_inv - $tot_coa;

                        if (isset($_POST['excel'])) {
                            $tot_rl_t2 = ($rl == null) ? 0 : $rl;
                        } else {
                            $tot_rl_t2 = number_format($rl,'2',',','.');
                        }
                    
                    ?>       
                
                <th style="text-align: right"><?= $tot_rl_t2; ?></th>
            </tr>

            <tr>
                <th style="text-align: right">Prosentase Biaya</th>
                
                <?php $tot_rl=0; foreach ($bulan as $d): ?>

                    <?php $hasil = $this->M_laporan->get_cetak_coa($d, '')->row_array(); ?>

                    <?php $hasil2 = $this->M_laporan->get_pendapatan_invoice($d)->row_array(); ?>

                    <?php

                    if ($hasil2['total'] == 0) {
                        $tot_rl = 0;
                    } else {
                        $tot_rl = ($hasil['total'] / $hasil2['total']) * 100;
                    }
                    
                     ?>

                    <th style="text-align: right"><?= number_format($tot_rl,'2',',','.'); ?> %</th>

                <?php endforeach ?>       

                <?php 

                    if ($tot_inv == 0) {
                        $pr_biaya = 0;
                    } else {
                        $pr_biaya = ($tot_coa / $tot_inv) * 100;
                    }
                
                ?>
                
                <th style="text-align: right"><?= number_format($pr_biaya,'2',',','.'); ?> %</th>
            </tr>

            <tr>
                <th style="text-align: right">Recoveries</th>
                
                <?php $tot_rec=0; foreach ($bulan as $d): ?>

                    <?php $hasil = $this->M_laporan->get_recoveries($d)->row_array(); 

                        if (isset($_POST['excel'])) {
                            $ha_tot = ($hasil['total'] == null) ? 0 : $hasil['total'];
                        } else {
                            $ha_tot = number_format($hasil['total'],'2',',','.');
                        }
                    ?>

                    <th style="text-align: right"><?= $ha_tot; ?></th>

                    <?php $tot_rec += $hasil['total'];  ?>

                <?php endforeach;

                    if (isset($_POST['excel'])) {
                        $tot_rec2 = ($tot_rec == null) ? 0 : $tot_rec;
                    } else {
                        $tot_rec2 = number_format($tot_rec,'2',',','.');
                    }
                
                ?>       
                
                <th style="text-align: right"><?= $tot_rec2 ?></th>
            </tr>

            <?php endif; ?>

        </tbody>
      </table>

    <script>
        
        $('button[name="export"]').on('click', function () {
			var jns = $(this).attr('data');

			$('#aksi').val(jns);

			console.log(jns);

			if (jns != 'print') {
				$("#aksi_export").attr('target', '_self');
			} else {
				$("#aksi_export").attr('target', '_blank');
			}
		
		})
    
    </script>

</body>
</html>