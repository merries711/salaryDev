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
      ,[dataFromTempTableDate]
      ,[dataCreateDate]
      ,[dataModifyDate])
 select *,GETDATE(),null from Temp_Import_Tax
 go
 update Import_Tax
	   set sapno=a.sapno,
             idno=a.idno,
	         branch=a.branch,
			 contractType=a.contractType,
			 postType=a.postType,
			 dispatchCompany=a.dispatchCompany,
	         taxMonth=CONVERT(nvarchar(7),taxStartPeriod,120),
			 dataModifyDate=GETDATE()
	 from Base_Employees_Info a , Import_Tax b 
	 where Replace(b.idno,' ','')=a.idno
	 and b.sapno is null
EOT;

?>