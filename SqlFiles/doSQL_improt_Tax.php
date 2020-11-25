<?php

$db_payMonth = substr($pay_month,0,4).'-'.substr($pay_month,4,2);

$SQL_import_Tax_01 = <<<EOT
insert into Import_Tax ([employee]
      ,[idtype]
      ,[idno]
      ,[taxStartPeriod]
      ,[taxEndPeriod]
      ,[incomeType]
      ,[curIncome]
      ,[curExpenses]
      ,[curFreeTaxIncome]
      ,[curEndowment]
      ,[curMedical]
      ,[curUnemployment]
      ,[curHousingFund]
      ,[curEnterAnnuity]
      ,[curHealthInsurance]
      ,[curTaxEndowmentInsurace]
      ,[curOtherDeduction]
      ,[totalIncome]
	  ,totalFreeTaxIncome
      ,[totalBaseDeduction]
      ,[totalSpecialDeduction]
      ,[totalChildDeduction]
      ,[totalParentsDeduction]
      ,[totalEduDeduction]
      ,[totalHousingDeduction]
      ,[totalRentDeduction]
      ,[totalOtherDeduction]
      ,[totalDonationDeduction]
      ,[totalTaxableIncome]
      ,[taxRate]
      ,[rapidCaclNumber]
      ,[totalTaxPayable]
      ,[totalTaxSaving]
      ,[totalTax]
      ,[totalTaxPrepay]
      ,[totalTaxReal]
      ,[remarks]
      ,[dataCreateDate]
      ,[dataModifyDate])
 select *,GETDATE(),null from Temp_Import_Tax
 go
 update Import_Tax
	   set sapno=a.sapno,
	         branch=a.branch,
			 contractType=a.contractType,
			 postType=a.postType,
			 dispatchCompany=a.dispatchCompany,
	         taxMonth=CONVERT(nvarchar(7),taxStartPeriod,120),
			 dataModifyDate=GETDATE()
	 from Base_Employees_Info a , Import_Tax b 
	 where b.idno=a.idno
	 and b.sapno is null
EOT;

$SQL_import_Tax_02 = <<<EOT
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
    and b.payMonth='$db_payMonth'
EOT;


?>