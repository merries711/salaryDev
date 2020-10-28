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
    include_once "./doSQL_import_BaseInfo.php";

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

    //---变量初始化---
    $fileMonth = date("Y-m");
    //$fileMonth = '2020-09';
    $inputFile = './_ExcelFiles/'.$fileMonth.'/00模板_新增人员信息.xlsx';
    $reader = new Xlsx();
    $spreadsheet = $reader->load($inputFile);
    
    //---确定导入范围---
    $sheetInfo = $reader->listWorksheetInfo($inputFile);
    $startCol = 'A' ;
    $startRow = '3' ;
    $endCol = $sheetInfo[0]['lastColumnLetter'] ;
    $endRow = $sheetInfo[0]['totalRows'] ;
    $cellRange = $startCol . $startRow . ':' . $endCol . $endRow ;

    //---从EXCEL导入数据---
    $sheetDataArray = $spreadsheet->getActiveSheet()
        ->rangeToArray(
            $cellRange,     // The worksheet range that we want to retrieve
            NULL,        // Value that should be returned for empty cells
            TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
            False,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
            TRUE         // Should the array be indexed by cell row and cell column
    );

    //---整理导入数据---
    $insertTime = date("Y-m-d H:i:s");
    foreach ( $sheetDataArray as $v ) {
        array_unshift($v,'等待导入',$insertTime);
        $importData[] = array_values($v);
    }

    //---导入数据库---
    $dbOper = new DbOpertions();
    $dbOper->dbInsert('Add_New_Person',$importData,$insertTime);

    //---导入后数据库相关操作---
    $dbOper->dbDoSql($import_Base_Info_SQL_01);
    $dbOper->dbDoSql($import_Base_Info_SQL_02);
    $dbOper->dbDoSql($import_Base_Info_SQL_03);
    $dbOper->dbDoSql($import_Base_Info_SQL_05);

?>