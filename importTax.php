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
    include_once "./SqlFiles/doSQL_improt_Tax.php";

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
    use PhpOffice\PhpSpreadsheet\Reader\Xls;

    $inputFile = './ExcelFiles/'.$pay_month.'/202012_税款计算_工资薪金所得.xls';
    $reader = new Xls();
    $spreadsheet = $reader->load($inputFile);
    
    $sheetInfo = $reader->listWorksheetInfo($inputFile);
    $startCol = 'B' ;
    $startRow = '2' ;
    $endCol = $sheetInfo[0]['lastColumnLetter'] ;
    $endRow = $sheetInfo[0]['totalRows'] ;
    $cellRange = $startCol . $startRow . ':' . $endCol . $endRow ;

    $sheetDataArray = $spreadsheet->getActiveSheet()
        ->rangeToArray(
            $cellRange,     // The worksheet range that we want to retrieve
            NULL,        // Value that should be returned for empty cells
            TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
            False,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
            TRUE         // Should the array be indexed by cell row and cell column
    );

    $importData = array();
    $insertTime = date("Y-m-d H:i:s");
    foreach ( $sheetDataArray as $v ) {
        if ( $v['C'] == null || $v['E'] == null || $v['F'] == null || $v['G'] == null  ) {
             $v['C'] = $sheetDataArray[$startRow]['C'];
             $v['E'] = $sheetDataArray[$startRow]['E'];
             $v['F'] = $sheetDataArray[$startRow]['F'];
             $v['G'] = $sheetDataArray[$startRow]['G'];
        }
        $v[] = $insertTime;
        $importData[] = array_values($v);
    }

    //print_r($importData);

    $dbOper = new DbOpertions();

    $dbOper->dbDelete('Temp_Import_Tax');
    $dbOper->dbInsert('Temp_Import_Tax',$importData,$insertTime);
    $dbOper->dbDoSql($SQL_import_Tax_01);

?>