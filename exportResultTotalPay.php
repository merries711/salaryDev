<?php
	header("Content-type:text/html;charset=utf-8");
	date_default_timezone_set('Asia/Chongqing');
	
	$app_start_time = microtime(TRUE);
	$starttime=date('Y-m-d H:i:s');
	echo "Start time is $starttime".PHP_EOL;
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
    include_once "./SqlFiles/doSQL_export_ResultTotalPay.php";

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $dbOper = new DbOpertions();
    $dbOper->dbDoSql($sql_isnull_View_Result_TotalPay);
    $dbOper->dbDoSql($sql_create_View_Result_TotalPay);
    $tabHeader = $dbOper->dbGetHeader($View_Result_TotalPay);

    $whereCondition[] = array("总经理室","WHERE branch = '大连市分公司总经理室' or employee = '桑洪洋'");
    $whereCondition[] = array("劳动（非销）","WHERE contractType='劳动合同' AND postType='非销' AND branch != '大连市分公司总经理室' and employee != '桑洪洋'");
    $whereCondition[] = array("劳动（销售）","WHERE contractType='劳动合同' AND postType='销售'");
    $whereCondition[] = array("光彩（销售）","WHERE contractType='派遣合同' and dispatchCompany='光彩' and postType='销售'");
    $whereCondition[] = array("光彩（非销）","WHERE contractType='派遣合同' and dispatchCompany='光彩' and postType='非销'");
    $whereCondition[] = array("融通（销售）","WHERE contractType='派遣合同' and dispatchCompany like '融%' and postType='销售'");
    $whereCondition[] = array("融通（非销）","WHERE contractType='派遣合同' and dispatchCompany like '融%' and postType='非销'");

    $spreadsheet = new Spreadsheet();

    foreach ( $whereCondition as $v ) {
        $doSQL =  $sql_export_View_Result_TotalPay.PHP_EOL.'  '.$v[1];
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

    $outFileName =   'OUT_'.$pay_month.'_工资表.xlsx';
    $outDir = './ExcelFiles/'.$pay_month.'/';

    $writer = new Xlsx($spreadsheet); 
    $writer->save($outDir.$outFileName);

?>