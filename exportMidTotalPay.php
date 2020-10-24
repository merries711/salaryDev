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
    include_once "./doSQL_export.php";

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $dbOper = new DbOpertions();
    $dbOper->dbDoSql($SQL_isnull_View_RSLT_totalPay);
    $dbOper->dbDoSql($SQL_create_View_RSLT_totalPay);
    $tabHeader = $dbOper->dbGetHeader($viewName);
    
    $whereCondition[] = array("劳动[销售]","WHERE contractType='劳动合同' AND postType='销售'");
    $whereCondition[] = array("劳动[非销]","WHERE contractType='劳动合同' AND postType='非销'");
    
    $doSQL =  $SQL_RSLT_totalPay_export.PHP_EOL.'  '.$whereCondition[0][1];
    $exportData = $dbOper->dbSelectArray($doSQL);
    echo $doSQL.PHP_EOL;

    print_r($exportData);

//    $spreadsheet = new Spreadsheet();
//
//    $spreadsheet->getActiveSheet()->getStyle('A:J')
//                          ->getNumberFormat()
//                          ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
//
//    $spreadsheet->getActiveSheet()->getStyle('K:AJ')
//                          ->getNumberFormat()
//                          ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
//    
//    $spreadsheet->getActiveSheet()->fromArray($tabHeader);
//    $spreadsheet->getActiveSheet()->fromArray($inputData,null,'A2');
//
//   $writer = new Xlsx($spreadsheet);
//   $writer->save('hello_world.xlsx');

?>