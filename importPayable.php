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
    include_once "./doSQL_import_Payable.php";

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
    use PhpOffice\PhpSpreadsheet\Reader\Xls;

    //$fileMonth = date("Y-m");
    $fileMonth = '2020-09';
    $inputFiles[] = './_ExcelFiles/'.$fileMonth.'/模板_IN_应发明细_v9_非销.xlsx';
    $inputFiles[] = './_ExcelFiles/'.$fileMonth.'/模板_IN_应发明细_v9_销售_互动.xlsx';
    $inputFiles[] = './_ExcelFiles/'.$fileMonth.'/模板_IN_应发明细_v9_销售_自主.xlsx';

    $importData = array();
    $insertTime = date("Y-m-d H:i:s");
    $reader = new Xlsx();
    foreach ( $inputFiles as $i ) {
       importPayable($i);
    }

    $dbOper = new DbOpertions();
    $dbOper->dbDelete('Temp_Import_Payable');
    $dbOper->dbInsert('Temp_Import_Payable',$importData,$insertTime);

    $dbOper->dbDoSql($SQL_import_Payable_01);
    $dbOper->dbDoSql($SQL_import_Payable_02);

    function importPayable ($inputFile) 
    {
        global $reader;
        global $importData;
        global $insertTime;

        $spreadsheet = $reader->load($inputFile);     
        $sheetInfo = $reader->listWorksheetInfo($inputFile);
        
        $startCol = 'A' ;
        $startRow = '3' ;
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

        foreach ( $sheetDataArray as $v ) {
            $v[] = $insertTime;
            $v[] = '';
            $importData[] = array_values($v);
        }
    }

?>