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
   // include_once "./doSQL.php";

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
    use PhpOffice\PhpSpreadsheet\Reader\Xls;

    $fileMonth = date("Y-m");
    $inputFile = './_ExcelFiles/'.$fileMonth.'/全员导出202010_税款计算_工资薪金所得.xls';
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

    print_r($sheetDataArray);

    $insertTime = date("Y-m-d H:i:s");
    foreach ( $sheetDataArray as $v ) {
        if ( $v['C'] == null ) {
            $v['C'] = $sheetDataArray[$startRow]['C'];
            $v['E'] = $sheetDataArray[$startRow]['E'];
            $v['F'] = $sheetDataArray[$startRow]['F'];
            $v['G'] = $sheetDataArray[$startRow]['G'];
        }
        $v[] = $insertTime;
        $inputData[] = array_values($v);
    }

    $dbOper = new DbOpertions();
    $dbOper->dbDelete('Temp_Import_Tax');
    $dbOper->dbInsert('Temp_Import_Tax',$inputData,$insertTime);



?>