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
    $inputData = $dbOper->dbSelectArray('');
    print_r($inputData);

    PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new CustomValueBinder()); //在创建spreadsheet对象前设置

    $spreadsheet = new Spreadsheet();
    $spreadsheet->getActiveSheet()->fromArray($inputData);

   $writer = new Xlsx($spreadsheet);
   $writer->save('hello_world.xlsx');

?>