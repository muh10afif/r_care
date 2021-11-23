<?php
  
$nama_dokumen=date('dmY')."-EKS-ASSET";
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
        <title>Print R. EKS ASSET</title>

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
            <?php if (!empty($jenis_asset)): ?>
              <tr>
              <td width="150px">Jenis Asset</td>
              <td>:</td>
              <td><?= (!empty($jenis_asset)) ? $jenis_asset : '-' ?></td>
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
              <th>Nama Klaim</th>
              <th>Asset / Jaminan</th>
              <th>Marketable</th>
              <th>Harga Taksiran</th>
              <th>Tindakan</th>
              <th>Proses</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data_r_eks_asset->result_array() as $a): ?>
              <tr>
                <td><?= $a['cabang_asuransi'] ?></td>
                <td><?= $a['cabang_bank'] ?></td>
                <td><?= $a['bank'] ?></td>
                <td><?= $a['nama_debitur'] ?></td>
                <td><?= $a['no_klaim'] ?></td>
                <td><?= $a['jenis_asset'] ?></td>
                <td><?= $a['sifat_asset'] ?></td>
                <td align="right"><?= number_format($a['harga'],0,'.','.') ?></td>
                <td><?= $a['status_asset'] ?></td>
                <td></td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
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