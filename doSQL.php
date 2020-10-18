<?php

$Mid_for_RSLT_totalPay = <<<'EOT'
SELECT [payMonth]
      ,' ' + [bankCard] as bankCard
      ,[sapno]
      ,[employee]
      ,[employeeType]
      ,' ' + [idno] as idno
      ,[branch]
      ,[contractType]
      ,[postType]
      ,[dispatchCompany]
      ,[basePay]
      ,[postPay]
      ,[monthPerf]
      ,[supplementPay]
      ,[overtimePay]
      ,[yearPerf]
      ,[salesPerf]
      ,[clockDeduction]
      ,[otherDeduction]
      ,[otherSubsidies]
      ,[heatingSubsidies]
      ,[childSubsidies]
      ,[festivalBonus]
      ,[other]
      ,[totalPayable]
      ,[afterPayableAdjustment]
      ,[taxBenefits]
      ,[endowmentPerson]
      ,[unemploymentPerson]
      ,[medicalPerson]
      ,[Criticalillness]
      ,[HousingFund]
      ,[EnterpriseAnnuity]
      ,[unionDues]
      ,[totalTaxReal]
      ,[totalRealPay]
      ,[remarks]
  FROM [DLSalary].[dbo].[Mid_for_RSLT_totalPay]
EOT;



?>