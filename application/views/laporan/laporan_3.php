<!doctype html>
<html>
    <head>
        <title>Laporan Keuangan 3</title>

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

      <h4>REKAP OUTSTANDING POTENSI KOMISI PT. SOLUSI PRIMA SELINDO</h4>
      <h6>SUBROGASI BANK BJB</h6>
      <h6>PERIODE <?= strtoupper(bln_indo($d_bulan_awal)) ?> - <?= strtoupper(bln_indo($d_bulan_akhir)) ?></h6>

      <br>  

      <table border="1" id="ad" width="100%">
        <thead style="background-color: #122E5D; color: white;" class="a">
          <tr>
            <th>No</th>
            <th>Cabang Asuransi</th>
            <th>Periode</th>
            <th>Recoveries Yang Diajukan</th>
            <th>Potensi Pengajuan Komisi</th>
          </tr>
        </thead>
        <tbody>

          <?php $no=0; $total_rec_aju=0; $total_kom_aju=0; foreach ($data_report as $d): $no++; ?>
          
            <tr>
              <td align='center'><?= $no ?></td>
              <td><?= $d['cabang_asuransi'] ?></td>
              <td align="center"><?= nice_date($d['nama_periode'], 'M-Y') ?></td>

              <?php 
              
                $hasil = $this->M_laporan->get_data_recov_aju($d['id_cabang_asuransi'], $d['nama_periode'], 'recov_aju')->result_array(); 
                
                $tot_recov_aju = 0;

                foreach ($hasil as $h) {
                    $tot_recov_aju += $h['recoveries_aju'];
                }
                  
                $total_rec_aju += $tot_recov_aju;

                if (isset($_POST['excel'])) {
                  $tot_recov_aju2 = $tot_recov_aju;
                } else {
                  $tot_recov_aju2 = number_format($tot_recov_aju,'2',',','.');
                }

              ?>

              <td align="right"><?= $tot_recov_aju2 ?></td>

              <?php 
              
                $hasil = $this->M_laporan->get_data_recov_aju($d['id_cabang_asuransi'], $d['nama_periode'], 'komisi_aju')->result_array(); 
                
                $tot_komisi_aju = 0;

                foreach ($hasil as $h) {
                    $tot_komisi_aju += $h['komisi_diajukan'];
                }

                $total_kom_aju += $tot_komisi_aju;

                if (isset($_POST['excel'])) {
                  $tot_komisi_aju2 = $tot_komisi_aju;
                } else {
                  $tot_komisi_aju2 = number_format($tot_komisi_aju,'2',',','.');
                }
                  
              ?>

              <td align="right"><?= $tot_komisi_aju2 ?></td>

            </tr>

          <?php endforeach; 

            if (isset($_POST['excel'])) {
              $total_rec_aju2 = $total_rec_aju;
              $total_kom_aju2 = $total_kom_aju;
            } else {
              $total_rec_aju2 = number_format($total_rec_aju,'2',',','.');
              $total_kom_aju2 = number_format($total_kom_aju,'2',',','.');
            }
          
          ?>

          <tr>
            <th colspan="3">Total</th>
            <th style="text-align: right"><?= $total_rec_aju2 ?></th>
            <th style="text-align: right"><?= $total_kom_aju2 ?></th>
          </tr>

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