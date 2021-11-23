<?php 

	// Load library phpspreadsheet
	require('./vendor/autoload.php');

	use PhpOffice\PhpSpreadsheet\Helper\Sample;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	// End load library phpspreadsheet

	// Create new Spreadsheet object
    $spreadsheet = new Spreadsheet();

    // Set document properties
    $spreadsheet->getProperties()->setCreator('Solusi Karya Digital')
    ->setLastModifiedBy('SKD')
    ->setTitle('Report OTS')
    ->setSubject('Report OTS')
    ->setDescription('Report OTS')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('OTS');

    // Add some data
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1', $Judul)
    ->setCellValue('A3', 'Tanggal Awal Periode :')
    ->setCellValue('B3', (!empty($tgl_awal)) ? $tgl_awal : '-')
    ->setCellValue('A4', 'Tanggal Akhir Periode :')
    ->setCellValue('B4', (!empty($tgl_akhir)) ? $tgl_akhir : '-')
    ->setCellValue('A5', 'Cabang :')
    ->setCellValue('B5', (!empty($wilayah)) ? $wilayah : '-')
    ->setCellValue('A6', 'Petugas :')
    ->setCellValue('B6', (!empty($ver)) ? $ver : '-')
    ->setCellValue('A7', 'Bank :')
    ->setCellValue('B7', (!empty($bank)) ? $bank : '-')
    ->setCellValue('A8', 'Asuransi :')
    ->setCellValue('B8', (!empty($asuransi)) ? $asuransi : '-')
    ;

    $spreadsheet->getActiveSheet()->getStyle('A1:A8')->getFont()->setBold(true);

    $spreadsheet->getActiveSheet()->mergeCells('A1:C1');

    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A10', 'CABANG ASURANSI')
    ->setCellValue('B10', 'CABANG BANK')
    ;

    $style_a = ['font' => ['bold' => TRUE],
		    	'color'=> ['argb' => '00000000'],
		    	];

    $spreadsheet->getActiveSheet()->getStyle('A10:B10')->applyFromArray($style_a);

	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

    $tot = count($data_r_ots)+10;

    // Miscellaneous glyphs, UTF-8
    $i=11; foreach($data_r_ots as $r) {

	    $spreadsheet->setActiveSheetIndex(0)
	    ->setCellValue('A'.$i, $r['cabang_asuransi'])
	    ->setCellValue('B'.$i, $r['cabang_bank']);

	    $styleArray = array(
	        'borders' => array(
	            'allBorders' => array(
	                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
	                'color' => array('argb' => '00000000'),
	            ),
	        ),
	    );

	    $spreadsheet->getActiveSheet()->getStyle('A'.($i-1).':B'.$tot)->applyFromArray($styleArray);

	    $i++;
    }

    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Report Excel OTS '.date('d-m-Y H'));

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Report Excel OTS.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;

 ?>