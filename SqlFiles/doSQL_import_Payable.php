<?php

$payMonth = date("Y-m");
//$payMonth = '2020-09';

$SQL_import_Payable_01 = <<<EOT
    INSERT INTO Import_Payable 
    SELECT *
    FROM Temp_Import_Payable
EOT;

$SQL_import_Payable_02 = <<<EOT
    UPDATE Import_Payable
	   SET employee = a.employee
	         ,sapno = a.sapno
			 ,employeeType = a.employeeType
             ,[branch] = a.branch
             ,[positionTitle] = a.positionTitle
             ,[contractType] = a.contractType
             ,[postType] =a.postType
             ,[dispatchCompany] = a.dispatchCompany
			 ,dataModifyDate=GETDATE()
    FROM Base_Employees_Info a , Import_Payable b
    WHERE 1=1
    and a.idno=b.idno
    and a.sapno=b.sapno
    and b.payMonth='$payMonth'
EOT;


?>