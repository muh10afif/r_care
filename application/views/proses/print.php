<?php
  
$nama_dokumen=date('dmY')."-PROSES";
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
        <title>Print R. Proses</title>

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
          <?php if (!empty($tindakan)): ?>
            <tr>
              <td width="150px">Tindakan Hukum</td>
              <td>:</td>
              <td><?= (!empty($tindakan)) ? $tindakan : '-' ?></td>
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
              <th>Penyebab Klaim</th>
              <th>Nama Debitur</th>
              <th>Nomor Klaim</th>
              <th>Proses Recoveries</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data_r_proses as $p): ?>
              <tr>
                <td><?= $p['cabang_asuransi'] ?></td>
                <td><?= $p['cabang_bank'] ?></td>
                <td><?= $p['bank'] ?></td>
                <td><?= $p['penyebab_klaim'] ?></td>
                <td><?= $p['nama_debitur'] ?></td>
                <td><?= $p['no_klaim'] ?></td>
                <?php $t = $p['tindakan_hukum'] ?>
                <?php if ($t == 'Penagihan Rutin'): ?>
                  <td style="background-color: #70e68c; color: black;"><?= $t ?></td>
                <?php elseif ($t == 'Somasi 1'): ?>
                  <td style="background-color: #f2a28c; color: black;"><?= $t ?></td>
                <?php elseif ($t == 'Somasi 2'): ?>
                  <td style="background-color: #e6f243; color: black;"><?= $t ?></td>
                <?php elseif ($t == 'Litigasi'): ?>
                  <td style="background-color: #2243ad; color: white;"><?= $t ?></td>
                <?php elseif ($t == 'Non Potensial'): ?>
                  <td style="background-color: #e51934; color: black;"><?= $t ?></td>
                <?php elseif ($t == 'Eksekusi Jaminan'): ?>
                  <td style="background-color: #03a335; color: black;"><?= $t ?></td>
                <?php elseif ($t == 'Mediasi'): ?>
                  <td style="background-color: #758de6; color: black;"><?= $t ?></td>
                <?php endif ?>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
        <br>
        <table border="1" align="right">
          <thead>
            <tr>
              <th colspan="3">Proses Recoveries :</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
            <table>
            <?php foreach ($jml_r_proses->result_array() as $j): ?>
              <tr>
                <td><?= $j['jumlah_tindakan'] ?> Debitur</td>
                <td>-</td>
                <td><?= $j['tindakan_hukum'] ?></td>
              </tr>
            <?php endforeach ?>
          </table>
        </td>
      </tr>
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