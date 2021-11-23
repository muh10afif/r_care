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

<?= $contents ?>

<?php
//penulisan output selesai, sekarang menutup mpdf dan generate kedalam format pdf
 
$html = ob_get_contents(); //Proses untuk mengambil hasil dari OB..
ob_end_clean();
//Disini dimulai proses convert UTF-8, kalau ingin ISO-8859-1 cukup dengan mengganti $mpdf->WriteHTML($html);
$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output($nama_dokumen.".pdf" ,'I');
exit;
?>

