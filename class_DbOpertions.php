<?php
    class DbOpertions {  
	   private $server_ip = '9.240.0.199';
	   private $database = 'DLSalary';
	   private $uid = 'sa';
	   private $pwd = 'GpicIn1220';
	   private $conn;
	   private $rows;

	   public function __construct() {
		  try
		  {
			$this->conn = new PDO("sqlsrv:server=$this->server_ip;Database=$this->database",$this->uid,$this->pwd); 
			$this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); 
		  }  
		  catch(Exception $e)  
		  {   
		     die(print_r($e->getMessage()));   
		  }  
	   }

	   function dbSelectArray($tsql) {
		  $stmt = $this->conn->prepare($tsql,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));  
          $stmt->execute();  
		  return $stmt->fetchall(PDO::FETCH_ASSOC);  
       }

	   function dbDoSql($tsql) {
		  $stmt = $this->conn->prepare($tsql,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));  
          //echo $tsql.PHP_EOL;
          $stmt->execute();  
		  printf("本次操作影响 %1d 条数据\n", $stmt->rowCount());
       }

      function dbGetHeader($tab_name) {
		  $tsql = "select name from syscolumns where id=OBJECT_ID('$tab_name') order by colorder";
		  $stmt = $this->conn->prepare($tsql,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));  
          $stmt->execute();  
          foreach (  $stmt->fetchall(PDO::FETCH_ASSOC) as $v ) {
              $tabColumn[] = $v['name'];
          }
		  return $tabColumn;  
       }

	   function dbInsert($tab_name,$data_input,$time_insert) {
		   //---计算插入表的列数---
		  $tsql = "select name from syscolumns where id=OBJECT_ID('$tab_name') order by colorder";
		  $stmt = $this->conn->prepare($tsql,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));  
		  $stmt->execute();  
		  $row_count = $stmt->rowCount();  
		  //---生成占位符---
		  $bit="?";
		  for ($i = 1; $i <= $row_count-1; $i++) {
			 $bit .= ", ?";
		  }
		  //---执行插入操作--- 
		  $tsql = "insert into $tab_name values ($bit)";
		  $stmt = $this->conn->prepare($tsql,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL)); 
		  foreach ( $data_input as $v ) {
			 $stmt->execute($v);
          }
		  //---统计本次插入的行数---
          $tsql = "select count(*) from $tab_name where dataCreateDate = '$time_insert'";
          $stmt = $this->conn->query($tsql);  
          printf("本次 %1s 表操作共插入 %2d 条数据\n", $tab_name , $stmt->fetchColumn(0));
       }

	   function dbDelete($tab_name) {
		  //---执行删除操作--- 
		  $tsql="delete from ".$tab_name;
		  $stmt = $this->conn->prepare($tsql,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL)); 
		  $stmt->execute();
          printf("本次 %1s 表操作共删除 %2d 条数据\n", $tab_name , $stmt->rowCount());
	   }

	   function printInfoInserted() {
		  foreach($this->rows as $v) 
			 {
				print_r($v).PHP_EOL;
			 }
		  }

	   function __destruct() {
		  try
		  {
			unset($this->stmt);
			unset($this->conn);
		  }
		  catch(Exception $e)  
		  {   
		     die(print_r($e->getMessage()));   
		  }  
	   }
    }

?>