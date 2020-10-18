<?php
	header("Content-type:text/html;charset=utf-8");
	date_default_timezone_set('Asia/Chongqing');
	
	$app_start_time = microtime(TRUE);
	$starttime=date('Y-m-d H:i:s');
	echo "Start time is $starttime".PHP_EOL;
	echo "================================================".PHP_EOL;
	
    require '../vendor/autoload.php';
	include_once "./class_DbOpertions.php";
    include_once "./class_MyExcel.php";
    include_once "./doSQL.php";

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $dbOper = new DbOpertions();
    $tabHeader = $dbOper->dbGetHeader('Mid_for_RSLT_totalPay');
    $inputData = $dbOper->dbSelectArray($Mid_for_RSLT_totalPay);
    print_r($tabHeader);
    //print_r($inputData);

    $spreadsheet = new Spreadsheet();

    $spreadsheet->getActiveSheet()->getStyle('A:J')
                          ->getNumberFormat()
                          ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

    $spreadsheet->getActiveSheet()->getStyle('K:AJ')
                          ->getNumberFormat()
                          ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
    
    $spreadsheet->getActiveSheet()->fromArray($tabHeader);
    $spreadsheet->getActiveSheet()->fromArray($inputData,null,'A2');

   $writer = new Xlsx($spreadsheet);
   $writer->save('hello_world.xlsx');

?>