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

    $whereCondition[] = array("总经理室","WHERE branch = '大连市分公司总经理室'");
    $whereCondition[] = array("劳动（非销）","WHERE contractType='劳动合同' AND postType='非销' AND branch != '大连市分公司总经理室'");
    $whereCondition[] = array("劳动（销售）","WHERE contractType='劳动合同' AND postType='销售'");
    $whereCondition[] = array("光彩（销售）","WHERE contractType='派遣合同' and dispatchCompany='光彩' and postType='销售'");
    $whereCondition[] = array("光彩（非销）","WHERE contractType='派遣合同' and dispatchCompany='光彩' and postType='非销'");
    $whereCondition[] = array("融通（销售）","WHERE contractType='派遣合同' and dispatchCompany like '融%' and postType='销售'");
    $whereCondition[] = array("融通（非销）","WHERE contractType='派遣合同' and dispatchCompany like '融%' and postType='非销'");

    $spreadsheet = new Spreadsheet();

    foreach ( $whereCondition as $v ) {
        $doSQL =  $SQL_RSLT_totalPay_export.PHP_EOL.'  '.$v[1];
        $exportData = $dbOper->dbSelectArray($doSQL);
        //echo $doSQL.PHP_EOL;
        //print_r($exportData); 

       $infoIndex = 0;

       $spreadsheet->createSheet($infoIndex)->setTitle($v[0]);
       $spreadsheet->setActiveSheetIndex($infoIndex);

       $spreadsheet->getActiveSheet($infoIndex)->getStyle('A:J')
                              ->getNumberFormat()
                               ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

        $spreadsheet->getActiveSheet($infoIndex)->getStyle('K:AK')
                              ->getNumberFormat()
                              ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
    
        $spreadsheet->getActiveSheet($infoIndex)->fromArray($tabHeader);
        $spreadsheet->getActiveSheet($infoIndex)->fromArray($exportData,null,'A2');

        ++$infoIndex;

    }

    $outFileName =   'OUT_'.date("Y").date("m").'_工资表.xlsx';
    $outDir = './_ExcelFiles/'.date("Y-m").'/';

    $writer = new Xlsx($spreadsheet); 
    $writer->save($outDir.$outFileName);

?>