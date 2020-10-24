<?php

/****** 员工基本信息导入 ******/
$import_Base_Info_SQL_01 = <<<'EOT'
INSERT INTO Base_Employees_Info 
    ([sapno],[employee],[employeeType],[branch],[positionTitle]
     ,[postType],[contractType],[dispatchCompany],[idno],[jobStatus],[entryDate]
	 ,[claimRatio],[heatingfeeFlag],[bankCard],[dataCreateDate] )
SELECT 
    [sapno],[employee],[employeeType],[branch],[positionTitle]
    ,[postType],[contractType],[dispatchCompany],[idno],'在职',[entryDate]
	,[claimRatio],[heatingfeeFlag],[bankCard],GETDATE()
FROM [DLSalary].[dbo].[Add_New_Person]
WHERE inStatus='等待导入'
EOT;

  /****** 社保信息导入 ******/
$import_Base_Info_SQL_02 = <<<'EOT'
INSERT INTO Base_Social_Insurance      
    ([sapno],[employee],[employeeType],[idno],[baseYear],[beginMonth],[endMonth],[baseNumber],[dataCreateDate])
SELECT 
    [sapno],[employee],[employeeType],[idno],'2020v1',Social_Insurance_beginMonth,'2021-06',[Social_Insurance_baseNunber],GETDATE()
FROM [DLSalary].[dbo].[Add_New_Person]      
WHERE inStatus='等待导入'
EOT;

/****** 公积金信息导入 ******/
$import_Base_Info_SQL_03 = <<<'EOT'
INSERT INTO Base_Housing_Fund 
    ([sapno],[employee],[employeeType],[idno],[baseYear],[beginMonth],[endMonth],[baseNumber]
    ,[ratioComp],[discountCompany],[ratioPerson],[discountPerson],[dataCreateDate])
SELECT   
    [sapno],[employee],[employeeType],[idno],'2020v1',[Housing_Fund_beginMonth],'2021-06'
    ,[Housing_Fund_baseNunber],[Housing_Fund_ratioComp],1,[Housing_Fund_ratioPerson],1,GETDATE()
FROM [DLSalary].[dbo].[Add_New_Person]      
WHERE inStatus='等待导入'
EOT;

$import_Base_Info_SQL_05 = <<<'EOT'
UPDATE Add_New_Person 
SET inStatus='已完成' 
WHERE inStatus='等待导入'
EOT;

?>