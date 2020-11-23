<?php
	header("Content-type:text/html;charset=utf-8");
	date_default_timezone_set('Asia/Shanghai');
	
	$app_start_time = microtime(TRUE);
	$start_time=date('Y-m-d H:i:s');
	echo "Start time is $start_time".PHP_EOL;
	echo "================================================".PHP_EOL;
	
    $cmd_options = getopt("",array("month:"));
    if (empty($cmd_options)) {   
          echo "请输入参数，格式为：--month YYYYMM".PHP_EOL;
          exit; 
    } elseif (count($cmd_options,COUNT_RECURSIVE) > 1 ) {
          echo "参数数量大于1，请重新输入".PHP_EOL;
          exit; 
    } elseif ( !ctype_digit($cmd_options['month']) || strlen($cmd_options['month'])!=6 || substr($cmd_options['month'],0,2)!='20' ) {
          echo "参数格式错误，格式为：--month YYYYMM".PHP_EOL;
          exit; 
    }
    $pay_month = $cmd_options['month'];

    require '../vendor/autoload.php';
	include_once "./class_DbOpertions.php";
    include_once "./SqlFiles/doSQL_export_FinancialReport.php";

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $dbOper = new DbOpertions();
    $dbOper->dbDoSql($sql_isnull_View_Financial_Report);
    $dbOper->dbDoSql($sql_create_View_Financial_Report);
    $tabHeader = $dbOper->dbGetHeader($View_Financial_Report);

    $spreadsheet = new Spreadsheet();

    $exportData = $dbOper->dbSelectArray("select * from $View_Financial_Report");

   $spreadsheet->getActiveSheet()->getStyle('A:G')
                          ->getNumberFormat()
                           ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

    $spreadsheet->getActiveSheet()->getStyle('H:BI')
                          ->getNumberFormat()
                          ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);

    $spreadsheet->getActiveSheet()->fromArray($tabHeader);
    $spreadsheet->getActiveSheet()->fromArray($exportData,null,'A2');


    $outFileName =   'OUT_'.$pay_month.'_财务系统导入.xlsx';
    $outDir = './ExcelFiles/'.$pay_month.'/';

    $writer = new Xlsx($spreadsheet); 
    $writer->save($outDir.$outFileName);

?>