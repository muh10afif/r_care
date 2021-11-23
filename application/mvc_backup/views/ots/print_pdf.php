<?php
  
$nama_dokumen= date('dmY')."-OTS";
//require(APPPATH.'third_party/fpdf.php'); // file fpdf.php harus diincludekan
// require(APPPATH.'third_party/mpdf-baru/src/Mpdf.php');
base_url('vendor/autoload.php');

//$mpdf=new \Mpdf\Mpdf('utf-8', 'A4-L', 8, 'arial','4','15','5','16','9','9','L'); // Membuat file mpdf baru
$mpdf=new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => [190, 236],
    'orientation' => 'L'
]); // Membuat file mpdf baru

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
        <title>Print R. OTS</title>

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
        <h4 style="font-weight: bold;"><?= $Judul ?></h4>
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
          <?php if (!empty($wilayah)): ?>
            <tr>
              <td width="150px">Cabang</td>
              <td>:</td>
              <td><?= (!empty($wilayah)) ? $wilayah : '-' ?></td>
            </tr>
          <?php endif ?>
          <?php if (!empty($ver)): ?>
            <tr>
              <td width="150px">Petugas</td>
              <td>:</td>
              <td><?= (!empty($ver)) ? $ver : '-' ?></td>
            </tr>
          <?php endif ?>
          <?php if (!empty($bank)): ?>
            <tr>
              <td width="150px">Bank</td>
              <td>:</td>
              <td><?= (!empty($bank)) ? $bank : '-' ?></td>
            </tr>
          <?php endif ?>
          <?php if (!empty($asuransi)): ?>
            <tr>
              <td width="150px">Asuransi</td>
              <td>:</td>
              <td><?= (!empty($asuransi)) ? $asuransi : '-' ?></td>
            </tr>
          <?php endif ?>
        </table><?= br() ?>
        <table border="1" width="100%">
          <thead>
            <tr>
              <th>Asuransi</th>
              <th>Cabang Bank</th>
              <th>Bank</th>
              <th>Deal Reff</th>
              <th>Nama Debitur</th>
              <th>OTS</th>
              <th>Bertemu</th>
              <th>Alamat</th>
              <th>Nomor Telp</th>
              <th>Informasi Debitur</th>
              <th>Agunan</th>
              <th>Potensi</th>
              <th>Proses</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data_r_ots)): ?>
                <?php foreach ($data_r_ots as $d): ?>
                  <tr>
                    <td><?= $d['cabang_asuransi'] ?></td>
                    <td><?= $d['cabang_bank'] ?></td>
                    <td><?= $d['bank'] ?></td>
                    <td><?= $d['no_reff'] ?></td>
                    <td><?= $d['nama_debitur'] ?></td>
                    <td><?= tgl_indo(substr($d['add_time'],0,10)) ?></td>
                    <td><?= $d['narasumber'] ?></td>
                    <td><?= $d['alamat'] ?></td>
                    <td><?= $d['telp'] ?></td>
                    <td><?= $d['keterangan'] ?></td>
                    <td>
                    <?php 
                      if ($d['jenis_asset'] != null) {
                        echo $d['jenis_asset'];
                      } else {
                        echo 'Tidak Ada';
                      }
                      ?></td>
                    <td><?= (!empty($d['potensi'])) ? $d['potensi'] : 'Belum ada tindakan' ?></td>
                    <td><?= (!empty($d['proses'])) ? $d['proses'] : 'Belum ada proses' ?></td>
                  </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                  <td colspan="11" align="center">Data Kosong</td>
                </tr>
            <?php endif ?>
              
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