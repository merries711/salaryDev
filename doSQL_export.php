<?php

//$payMonth = date("Y-m");
//$viewName = 'View_RSLT_totalPay_'.date("Y").date("m");
$payMonth = '2020-09';
$viewName = 'View_RSLT_totalPay_'.'202009';

$SQL_isnull_View_RSLT_totalPay = <<<EOT
    IF object_id('$viewName') is not null
       DROP VIEW $viewName
EOT;

$SQL_create_View_RSLT_totalPay = <<<EOT
    CREATE VIEW [dbo].$viewName
    AS
    SELECT x.*
      --实发数
      ,x.[totalPayable]-x.[endowmentPerson]-x.[unemploymentPerson]-x.[medicalPerson]-x.[Criticalillness]-x.[HousingFund]-x.[EnterpriseAnnuity]-x.[unionDues]
	  -x.[totalTaxReal]-x.[afterPayableAdjustment] as totalRealPay
	  --备注
	  ,y.remarks
    FROM
    (
    SELECT 
      --基础信息
      t1.[payMonth] ,t2.[bankCard] ,t1.[sapno] ,t2.[employee] ,t2.[employeeType] ,t1.[idno] ,t2.[branch] ,t2.[contractType] ,t2.[postType] ,t2.[dispatchCompany]
      --应发信息
	  ,t1.[basePay], t1.[postPay] ,t1.[monthPerf] ,t1.[supplementPay] ,t1.[overtimePay] ,t1.[yearPerf] ,t1.[salesPerf] ,t1.[clockDeduction] ,t1.[otherDeduction]
      ,t1.[otherSubsidies] ,t1.[lunchSubsidies] ,t1.[heatingSubsidies] ,t1.[childSubsidies] ,t1.[festivalBonus] ,t1.[other] ,t1.[totalPayable],t1.[afterPayableAdjustment],t1.taxBenefits
      --三险二金
	  ,(case when t1.payMonth between t3.beginMonth and t3.endMonth then isnull(t3.[endowmentPerson]*t1.[socialMonthCount],0) else 0 end) as [endowmentPerson]
	  ,(case when t1.payMonth between t3.beginMonth and t3.endMonth then isnull(t3.[unemploymentPerson]*t1.[socialMonthCount],0) else 0 end) as [unemploymentPerson]
	  ,(case when t1.payMonth between t3.beginMonth and t3.endMonth then isnull(t3.[medicalPerson]*t1.[socialMonthCount],0) else 0 end) as [medicalPerson]
	  ,t1.Criticalillness as [Criticalillness]
	  ,(case when t1.payMonth between t4.beginMonth and t4.endMonth then isnull(t4.[costPerson]*t1.[fundMonthCount],0) else 0 end) as [HousingFund]
	  ,(case when t1.payMonth between t5.beginMonth and t5.endMonth then isnull(t5.[costPerson]*t1.[annuityMonthCount],0) else 0 end) as [EnterpriseAnnuity]
	  --工会会费
	  ,(case when t2.jobStatus = '在职（临时）' then 0 else cast(t1.[basePay]*0.005*unionMonthCount as decimal(18,2)) end)  as [unionDues]
	  --个人所得税
	  ,isnull(t6.totalTaxReal,0) as [totalTaxReal]
    FROM Import_Payable t1 left join Base_Employees_Info t2 on t1.sapno=t2.sapno 
                                              left join Base_Social_Insurance t3 on t1.sapno=t3.sapno 
                                              left join Base_Housing_Fund t4 on t1.sapno=t4.sapno 
									          left join Base_Enterprise_Annuity t5 on t1.sapno=t5.sapno 
									          left join  (select taxMonth,sapno,sum(totalTaxReal) as totalTaxReal from  Import_Tax group by taxMonth,sapno) t6 on t1.sapno=t6.sapno and t1.payMonth=t6.taxMonth
    Where t1.payMonth='$payMonth'
    ) x , Import_Payable y
    WHERE x.payMonth=y.payMonth
    AND x.sapno=y.sapno
    AND x.idno=y.idno 
EOT;

$SQL_RSLT_totalPay_export = <<<EOT
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
  FROM [DLSalary].[dbo].$viewName
EOT;



?>