<?php
  
$nama_dokumen=date('dmY')."-RECOVERIES";
//require(APPPATH.'third_party/fpdf.php'); // file fpdf.php harus diincludekan
require(APPPATH.'third_party/mpdf/mpdf.php');

$mpdf=new mPDF('utf-8', 'A4-L', 5, 'arial','4','15','10','16','9','9','L'); // Membuat file mpdf baru

/*$mpdf = new mPDF('',    // mode - default ''
 '',    // format - A4, for example, default ''
 0,     // font size - default 0
 '',    // default font family
 15,    // margin_left
 15,    // margin right
 16,     // margin top
 16,    // margin bottom
 9,     // margin header
 9,     // margin footer
 'L');  // L - landscape, P - portrait*/
 
//Memulai proses untuk menyimpan variabel php dan html
ob_start();
 
?>
<!doctype html>
<html>
    <head>
        <title>Print R. Recov</title>

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">

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
      color: black;
    }
    </style>
    </head>
    <body>
        <h5 style="font-weight: bold;"><?= $judul ?></h5>
        <table class="a">
            <?php if (!empty($tgl_awal)): ?>
            <tr>
              <td width="150px">Tanggal Awal Periode</td>
              <td>:</td>
              <td><?= (!empty($tgl_awal)) ? tgl_indo($tgl_awal) : '-' ?></td>
            </tr>
          <?php endif ?>
          <?php if (!empty($tgl_akhir)): ?>
            <tr>
              <td width="150px">Tanggal Akhir Periode</td>
              <td>:</td>
              <td><?= (!empty($tgl_akhir)) ? tgl_indo($tgl_akhir) : '-' ?></td>
            </tr>
          <?php endif ?>
          <?php if (!empty($potensial)): ?>
            <tr>
              <td width="150px">Status Debitur</td>
              <td>:</td>
              <td><?= (!empty($potensial)) ? $potensial : '-' ?></td>
            </tr>
          <?php endif ?>
          <?php if (!empty($bank)): ?>
            <tr>
              <td width="150px">Bank</td>
              <td>:</td>
              <td><?= (!empty($bank)) ? $bank : '-' ?></td>
            </tr>

          <?php endif ?>
        </table><?= br() ?>
        <table border="1" width="100%">
          <thead>
            <tr>
              <th>Asuransi</th>
              <th>Cabang Bank</th>
              <th>Bank</th>
              <th>Nama Debitur</th>
              <th>Nomor Klaim</th>
              <th>Nilai Subrogasi</th>
              <th>Nilai Recoveries</th>
              <th>SHS</th>
              <th>Nilai Tagihan</th>
              <th>Saldo Tagihan</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data_r_noa as $r): ?>
              <tr>
                <td><?= $r['cabang_asuransi'] ?></td>
                <td width="150"><?= $r['cabang_bank'] ?></td>
                <td width="150"><?= $r['bank'] ?></td>
                <td width="250"><?= $r['nama_debitur'] ?></td>
                <td><?= $r['no_klaim'] ?></td>
                <td align="right"><?= number_format($r['subrogasi'],0,'.','.') ?></td>
                <td align="right"><?= ($r['recoveries'] != null) ? number_format($r['recoveries'],0,'.','.') : '-' ?></td>
                <td align="right"><?= number_format($r['shs'],0,'.','.') ?></td>
                <td align="right"><?= number_format($r['nilai_tagihan'],0,'.','.') ?></td>
                <td align="right"><?= number_format($r['saldo_tagihan'],0,'.','.') ?></td>
                <td></td>
              </tr><?php $tot_shs += $r['shs']; $tot_saldo_tagihan += $r['saldo_tagihan'] ?>
              <?php $tot_subro += $r['subrogasi']; $tot_recov += $r['recoveries']; $tot_tagihan += $r['nilai_tagihan'] ?>
            <?php endforeach ?>
            <tr>
              <td colspan="5" align="right" style="font-weight: bold;">Jumlah Total :</td>
              <td style="font-weight: bold; text-align: right;"><?= number_format($tot_subro,0,'.','.') ?></td>
              <td style="font-weight: bold; text-align: right;"><?= number_format($tot_recov,0,'.','.') ?></td>
              <td style="font-weight: bold; text-align: right;"><?= number_format($tot_shs,0,'.','.') ?></td>
              <td style="font-weight: bold; text-align: right;"><?= number_format($tot_tagihan,0,'.','.') ?></td>
              <td style="font-weight: bold; text-align: right;"><?= number_format($tot_saldo_tagihan,0,'.','.') ?></td>
              <td></td>
            </tr>
          </tbody>
        </table><?= br() ?>
        <?php if (!empty($bank )): ?>
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
              <td align="right"><?= number_format($tot_subro,0,'.','.') ?></td>
            </tr>
            <tr>
              <td>Recoveries</td>
              <td align="right"><?= number_format($tot_recov,0,'.','.') ?></td>
            </tr>
            <tr>
              <td>Saldo Tagihan Akhir</td>
              <td align="right"><?= number_format($tot_shs,0,'.','.') ?></td>
            </tr>
            <tr>
              <td>CRP (%)</td>
              <td align="right"><?= number_format(($tot_recov/$tot_subro) * 100,2,',','.') ?> %</td>
            </tr>
          </tbody>
        </table>
        <?php endif ?>
</body>
</html>
 
<?php
//penulisan output selesai, sekarang menutup mpdf dan generate kedalam format pdf
 
$html = ob_get_contents(); //Proses untuk mengambil hasil dari OB..
ob_end_clean();
//Disini dimulai proses convert UTF-8, kalau ingin ISO-8859-1 cukup dengan mengganti $mpdf->WriteHTML($html);
$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output($nama_dokumen.".pdf" ,'I');
exit;
?>