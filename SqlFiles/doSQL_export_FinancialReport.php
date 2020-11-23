<?php

$db_payMonth = substr($pay_month,0,4).'-'.substr($pay_month,4,2);
$View_Financial_Report = 'View_Financial_Report_'.$pay_month;
$View_Result_TotalPay = 'View_Result_TotalPay_'.$pay_month;

$sql_isnull_View_Financial_Report = <<<EOT
    IF object_id('$View_Financial_Report') is not null
       DROP VIEW $View_Financial_Report
EOT;

$sql_create_View_Financial_Report = <<<EOT
    CREATE VIEW [dbo].$View_Financial_Report
    AS
    ---###领导部分（总部统计）###---
    SELECT
          ------基础信息------
          b.jobStatus,b.[costcode] as '成本中心代码' ,'' as '人员清分类型' ,b.[employeeType] as '人员清分代码' ,cast(b.[claimRatio]*100 as int) as '理赔费用分摊比例'
          ,a.[sapno] as '序号' ,a.[employee] as '姓名' ,cast(0 as decimal(18,2)) as '工资标准'
          ------工资------
          ,cast(0 as decimal(18,2)) as '月基础工资' 
          ,cast(0 as decimal(18,2)) as '补发基础工资' 
          ,cast(0 as decimal(18,2)) as '补扣基础工资'
          ,cast(0 as decimal(18,2)) as '预发绩效工资' 
          ,cast(0 as decimal(18,2)) as '延期绩效工资'
          ,cast(0 as decimal(18,2)) as '加班工资' 
          ,cast(0 as decimal(18,2)) as '补发/补扣加班工资'
          ------费和补贴------
          ,cast(0 as decimal(18,2)) as '交通费' 
          ,cast(0 as decimal(18,2)) as '通讯费' 
          --,cast(0 as decimal(18,2)) as '取暖费'
          ,cast(0 as decimal(18,2)) as '置装费' 
          ,cast(0 as decimal(18,2)) as '劳动保护费'
          ,cast(0 as decimal(18,2)) as '医疗包干费' 
          ,cast(0 as decimal(18,2)) as '子女医疗包干费'
          ,cast(0 as decimal(18,2)) as '洗衣费' 
          ,cast(0 as decimal(18,2)) as '过节费'
          --,cast(0 as decimal(18,2)) as '误餐补助'
          ,cast(0 as decimal(18,2)) as '其他补贴' 
          ,cast(0 as decimal(18,2)) as '奖金或奖励'
          ,cast(0 as decimal(18,2)) as '应发合计'
          ------五险一金（个人）------
          ,cast(0 as decimal(18,2)) as '养老保险个人缴费' 
          ,cast(0 as decimal(18,2)) as '医疗保险个人缴费' 
          ,cast(0 as decimal(18,2)) as '大额医疗互助'
          ,cast(0 as decimal(18,2)) as '失业保险个人缴费' 
          ,cast(0 as decimal(18,2)) as '社会保险个人缴费合计'
          ,cast(0 as decimal(18,2)) as '住房公积金个人缴费'
          ------其他费用（个人）------
          ,cast(0 as decimal(18,2)) as '免税额度1' 
          ,cast(0 as decimal(18,2)) as '免税额度2'
          ,cast(0 as decimal(18,2)) as '企业年金个人缴费额中需缴税部分' 
          ,cast(0 as decimal(18,2)) as '应纳税所得额'
          ,cast(0 as decimal(18,2)) as '个人所得税'
          ,cast(0 as decimal(18,2)) as '扣工会费'
          ,cast(0 as decimal(18,2)) as '扣企业年金个人缴费额' 
          ,cast(0 as decimal(18,2)) as '扣停车费' 
          ,cast(0 as decimal(18,2)) as '税后扣款'
          ,cast(0 as decimal(18,2)) as '扣款小计'
          ,cast(0 as decimal(18,2)) as '实发工资'
          ,cast(0 as decimal(18,2)) as '生日费（现金或卡，计税）'
          ------五险一金（公司）------
          ,cast(0 as decimal(18,2)) as '住房公积金'
          ,cast(0 as decimal(18,2)) as '养老保险' 
          ,cast(0 as decimal(18,2)) as '医疗保险'
          ,cast(0 as decimal(18,2)) as '失业保险' 
          ,cast(0 as decimal(18,2)) as '工伤保险' 
          ,cast(0 as decimal(18,2)) as '生育保险'
          ,cast(0 as decimal(18,2)) as '补充养老保险' 
          ,cast(0 as decimal(18,2)) as '企业年金'
          ,cast(0 as decimal(18,2)) as '员工综合保险' 
          ,cast(0 as decimal(18,2)) as '补充医疗保险'
          ,cast(0 as decimal(18,2)) as '职工福利费'
          ,cast(0 as decimal(18,2)) as '辞退福利费' 
          ,cast(0 as decimal(18,2)) as '股份支付'
          ,cast(0 as decimal(18,2)) as '劳务派遣费'
          ,cast(0 as decimal(18,2)) as '劳动保护费1' 
          ,cast(0 as decimal(18,2)) as '劳动保险费' 
          ,(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.heatingfee*f.socialMonthCount,0) else 0 end) as '非货币性福利费'
          ------其他信息------
          ,'9100001040' as '预算中心编码' ,'大连市分公司人力资源部/教育培训部' as '预算中心名称' ,'' as '销售渠道'
          ,'' as '险类1代码及比例1' ,'' as '险类1代码及比例2' ,'' as '险类1代码及比例3' ,'' as '险类1代码及比例4' ,'' as '险类1代码及比例5'
          ,'' as '险类1代码及比例6' ,'' as '险类1代码及比例7' ,'' as '险类1代码及比例8' ,'' as '险类1代码及比例9' ,'' as '险类1代码及比例10'
      FROM  $View_Result_TotalPay a left join Base_Employees_Info b on a.sapno=b.sapno
                                                            left join Base_Social_Insurance c on a.sapno=c.sapno
                                                            left join Base_Housing_Fund d on a.sapno=d.sapno 
                                                            left join Base_Enterprise_Annuity e on a.sapno=e.sapno
                                                            left join Import_Payable f on a.sapno=f.sapno and a.payMonth=f.payMonth
      where b.contractType='劳动合同'
      and a.payMonth='$db_payMonth'
      and b.positionLevel is not null
    union all
    ---###劳动合同员工部分###---
    SELECT
          ------基础信息------
          b.jobStatus,b.[costcode] as '成本中心代码' ,'' as '人员清分类型' ,b.[employeeType] as '人员清分代码' ,cast(b.[claimRatio]*100 as int) as '理赔费用分摊比例'
          ,a.[sapno] as '序号' ,a.[employee] as '姓名'
          ------工资------
          ,cast(0 as decimal(18,2)) as '工资标准'
          ,a.[basePay]+a.[postPay] as '月基础工资' 
          ,a.supplementPay as '补发基础工资' 
          ,a.clockDeduction+a.otherDeduction as '补扣基础工资'
          ,a.monthPerf as '预发绩效工资' 
          ,a.salesPerf+a.yearPerf as '延期绩效工资'
          ,a.overtimePay as '加班工资' 
          ,cast(0 as decimal(18,2)) as '补发/补扣加班工资'
          ------费和补贴------
          ,a.otherSubsidies as '交通费' 
          ,cast(0 as decimal(18,2)) as '通讯费' 
          --,a.heatingSubsidies as '取暖费'
          ,cast(0 as decimal(18,2)) as '置装费' 
          ,cast(0 as decimal(18,2)) as '劳动保护费'
          ,cast(0 as decimal(18,2)) as '医疗包干费' 
          ,a.childSubsidies as '子女医疗包干费'
          ,cast(0 as decimal(18,2)) as '洗衣费' 
          ,a.festivalBonus as '过节费'
          --,cast(0 as decimal(18,2)) as '误餐补助'
          ,a.other as '其他补贴' 
          ,cast(0 as decimal(18,2)) as '奖金或奖励'
          ,a.totalPayable as '应发合计'
          ------五险一金（个人）------
          ,a.endowmentPerson as '养老保险个人缴费' 
          ,a.medicalPerson as '医疗保险个人缴费' 
          ,a.Criticalillness as '大额医疗互助'
          ,a.unemploymentPerson as '失业保险个人缴费' 
          ,cast(0 as decimal(18,2)) as '社会保险个人缴费合计'
          ,a.HousingFund as '住房公积金个人缴费'
          ------其他费用（个人）------
          ,cast(0 as decimal(18,2)) as '免税额度1' 
          ,cast(0 as decimal(18,2)) as '免税额度2'
          ,cast(0 as decimal(18,2)) as '企业年金个人缴费额中需缴税部分' 
          ,cast(0 as decimal(18,2)) as '应纳税所得额'
          ,a.totalTaxReal as '个人所得税'
          ,a.unionDues as '扣工会费'
          ,a.EnterpriseAnnuity as '扣企业年金个人缴费额' 
          ,cast(0 as decimal(18,2)) as '扣停车费' 
          ,cast(0 as decimal(18,2)) as '税后扣款'
          ,a.totalPayable-a.totalRealPay as '扣款小计'
          ,a.totalRealPay as '实发工资'
          ,cast(0 as decimal(18,2)) as '生日费（现金或卡，计税）'
          ------五险一金（公司）------
          ,(case when a.payMonth between d.beginMonth and d.endMonth then isnull(d.costCompany*f.fundMonthCount,0) else 0 end) as '住房公积金'
          ,(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.endowmentComp*f.socialMonthCount,0) else 0 end) as '养老保险' 
          ,(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.medicalComp*f.socialMonthCount,0) else 0 end) as '医疗保险'
          ,(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.unemploymentComp*f.socialMonthCount,0) else 0 end) as '失业保险' 
          ,(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.injuryComp*f.socialMonthCount,0) else 0 end) as '工伤保险' 
          ,(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.maternityComp*f.socialMonthCount,0) else 0 end) as '生育保险'
          ,cast(0 as decimal(18,2)) as '补充养老保险' 
          ,(case when a.payMonth between e.beginMonth and e.endMonth then isnull(e.costCompany*f.annuityMonthCount,0) else 0 end) as '企业年金'
          ,cast(0 as decimal(18,2)) as '员工综合保险' 
          ,cast(0 as decimal(18,2)) as '补充医疗保险'
          ,(case when a.payMonth between c.beginMonth and c.endMonth then isnull(a.lunchSubsidies+a.heatingSubsidies,0) else 0 end) as '职工福利费'
          ,cast(0 as decimal(18,2)) as '辞退福利费' 
          ,cast(0 as decimal(18,2)) as '股份支付'
          ,cast(0 as decimal(18,2)) as '劳务派遣费'
          ,cast(0 as decimal(18,2)) as '劳动保护费1' 
          ,cast(0 as decimal(18,2)) as '劳动保险费' 
          ,(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.heatingfee*f.socialMonthCount,0) else 0 end) as '非货币性福利费'
          ------其他信息------
          ,'9100001040' as '预算中心编码' ,'大连市分公司人力资源部/教育培训部' as '预算中心名称' ,'' as '销售渠道'
          ,'' as '险类1代码及比例1' ,'' as '险类1代码及比例2' ,'' as '险类1代码及比例3' ,'' as '险类1代码及比例4' ,'' as '险类1代码及比例5'
          ,'' as '险类1代码及比例6' ,'' as '险类1代码及比例7' ,'' as '险类1代码及比例8' ,'' as '险类1代码及比例9' ,'' as '险类1代码及比例10'
      FROM  $View_Result_TotalPay a left join Base_Employees_Info b on a.sapno=b.sapno
                                                            left join Base_Social_Insurance c on a.sapno=c.sapno
                                                            left join Base_Housing_Fund d on a.sapno=d.sapno 
                                                            left join Base_Enterprise_Annuity e on a.sapno=e.sapno
                                                            left join Import_Payable f on a.sapno=f.sapno and a.payMonth=f.payMonth
      where b.contractType='劳动合同'
      and a.payMonth='$db_payMonth'
      and b.positionLevel is null
    union all
    ---###派遣员工部分（非岗位转换）###---
      SELECT
          ------基础信息------
          b.jobStatus ,b.[costcode] as '成本中心代码' ,'' as '人员清分类型' ,b.[employeeType] as '人员清分代码' ,cast(b.[claimRatio]*100 as int) as '理赔费用分摊比例'
          ,a.[sapno] as '序号' ,a.[employee] as '姓名'
          ------工资------
          ,cast(0 as decimal(18,2)) as '工资标准'
          ,cast(0 as decimal(18,2)) as '月基础工资' 
          ,cast(0 as decimal(18,2)) as '补发基础工资' 
          ,cast(0 as decimal(18,2)) as '补扣基础工资'
          ,cast(0 as decimal(18,2)) as '预发绩效工资' 
          ,cast(0 as decimal(18,2)) as '延期绩效工资'
          ,cast(0 as decimal(18,2)) as '加班工资' 
          ,cast(0 as decimal(18,2)) as '补发/补扣加班工资'
          ------费和补贴------
          ,cast(0 as decimal(18,2)) as '交通费' 
          ,cast(0 as decimal(18,2)) as '通讯费' 
          --,cast(0 as decimal(18,2)) as '取暖费'
          ,cast(0 as decimal(18,2)) as '置装费' 
          ,cast(0 as decimal(18,2)) as '劳动保护费'
          ,cast(0 as decimal(18,2)) as '医疗包干费' 
          ,cast(0 as decimal(18,2)) as '子女医疗包干费'
          ,cast(0 as decimal(18,2)) as '洗衣费' 
          ,cast(0 as decimal(18,2)) as '过节费' 
          --,cast(0 as decimal(18,2)) as '误餐补助'
          ,cast(0 as decimal(18,2)) as '其他补贴' 
          ,cast(0 as decimal(18,2)) as '奖金或奖励'
          ,cast(0 as decimal(18,2)) as '应发合计'
          ------五险一金（个人）------
          ,cast(0 as decimal(18,2)) as '养老保险个人缴费' 
          ,cast(0 as decimal(18,2)) as '医疗保险个人缴费' 
          ,cast(0 as decimal(18,2)) as '大额医疗互助'
          ,cast(0 as decimal(18,2)) as '失业保险个人缴费' 
          ,cast(0 as decimal(18,2)) as '社会保险个人缴费合计'
          ,cast(0 as decimal(18,2)) as '住房公积金个人缴费'
          ------其他费用（个人）------
          ,cast(0 as decimal(18,2)) as '免税额度1' 
          ,cast(0 as decimal(18,2)) as '免税额度2'
          ,cast(0 as decimal(18,2)) as '企业年金个人缴费额中需缴税部分' 
          ,cast(0 as decimal(18,2)) as '应纳税所得额'
          ,cast(0 as decimal(18,2)) as '个人所得税'
          ,cast(0 as decimal(18,2)) as '扣工会费'
          ,cast(0 as decimal(18,2)) as '扣企业年金个人缴费额' 
          ,cast(0 as decimal(18,2)) as '扣停车费' 
          ,cast(0 as decimal(18,2)) as '税后扣款'
          ,cast(0 as decimal(18,2)) as '扣款小计'
          ,cast(0 as decimal(18,2)) as '实发工资'
          ,cast(0 as decimal(18,2)) as '生日费（现金或卡，计税）'
          ------五险一金（公司）------
          ,cast(0 as decimal(18,2)) as '住房公积金' 
          ,cast(0 as decimal(18,2)) as '养老保险' 
          ,cast(0 as decimal(18,2)) as '医疗保险'
          ,cast(0 as decimal(18,2)) as '失业保险' 
          ,cast(0 as decimal(18,2)) as '工伤保险' 
          ,cast(0 as decimal(18,2)) as '生育保险'
          ,cast(0 as decimal(18,2)) as '补充养老保险' 
          ,cast(0 as decimal(18,2)) as '企业年金'
          ,cast(0 as decimal(18,2)) as '员工综合保险' 
          ,cast(0 as decimal(18,2)) as '补充医疗保险'
          ,cast(0 as decimal(18,2)) as '职工福利费' 
          ,cast(0 as decimal(18,2)) as '辞退福利费' 
          ,cast(0 as decimal(18,2)) as '股份支付'
          ,isnull(a.totalPayable,0)-isnull(a.unionDues,0)-isnull(a.afterPayableAdjustment,0)+80
          +(case when a.payMonth between d.beginMonth and d.endMonth then isnull(d.costCompany*f.fundMonthCount,0) else 0 end)
          +(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.endowmentComp*f.socialMonthCount,0) else 0 end)
          +(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.medicalComp*f.socialMonthCount,0) else 0 end)
          +(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.unemploymentComp*f.socialMonthCount,0) else 0 end)
          +(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.injuryComp*f.socialMonthCount,0) else 0 end)
          +(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.maternityComp*f.socialMonthCount,0) else 0 end)
          +(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.heatingfee*f.socialMonthCount,0) else 0 end) as '劳务派遣费'
          ,cast(0 as decimal(18,2)) as '劳动保护费1' 
          ,cast(0 as decimal(18,2)) as '劳动保险费' 
          ,cast(0 as decimal(18,2)) as '非货币性福利费'
          ------其他信息------
          ,'9100001040' as '预算中心编码' ,'大连市分公司人力资源部/教育培训部' as '预算中心名称' ,'' as '销售渠道'
          ,'' as '险类1代码及比例1' ,'' as '险类1代码及比例2' ,'' as '险类1代码及比例3' ,'' as '险类1代码及比例4' ,'' as '险类1代码及比例5'
          ,'' as '险类1代码及比例6' ,'' as '险类1代码及比例7' ,'' as '险类1代码及比例8' ,'' as '险类1代码及比例9' ,'' as '险类1代码及比例10'
      FROM  $View_Result_TotalPay a left join Base_Employees_Info b on a.sapno=b.sapno
                                                            left join Base_Social_Insurance c on a.sapno=c.sapno
                                                            left join Base_Housing_Fund d on a.sapno=d.sapno 
                                                            left join Base_Enterprise_Annuity e on a.sapno=e.sapno
                                                            left join Import_Payable f on a.sapno=f.sapno and a.payMonth=f.payMonth
      where b.contractType='派遣合同'
      and a.payMonth='$db_payMonth'
      and b.idno not in (select idno from Base_Employees_Trans_Post)
    union all
    ---###派遣员工部分（岗位转换）###---
      SELECT
          ------基础信息------
          b.jobStatus ,b.[costcode] as '成本中心代码' ,'' as '人员清分类型' ,b.[employeeType] as '人员清分代码' ,cast(b.[claimRatio]*100 as int) as '理赔费用分摊比例'
          ,a.[sapno] as '序号' ,a.[employee] as '姓名'
          ------工资------
          ,cast(0 as decimal(18,2)) as '工资标准'
          ,cast(0 as decimal(18,2)) as '月基础工资' 
          ,cast(0 as decimal(18,2)) as '补发基础工资' 
          ,cast(0 as decimal(18,2)) as '补扣基础工资'
          ,cast(0 as decimal(18,2)) as '预发绩效工资' 
          ,cast(0 as decimal(18,2)) as '延期绩效工资'
          ,cast(0 as decimal(18,2)) as '加班工资' 
          ,cast(0 as decimal(18,2)) as '补发/补扣加班工资'
          ------费和补贴------
          ,cast(0 as decimal(18,2)) as '交通费' 
          ,cast(0 as decimal(18,2)) as '通讯费' 
         --,cast(0 as decimal(18,2)) as '取暖费'
          ,cast(0 as decimal(18,2)) as '置装费' 
          ,cast(0 as decimal(18,2)) as '劳动保护费'
          ,cast(0 as decimal(18,2)) as '医疗包干费' 
          ,cast(0 as decimal(18,2)) as '子女医疗包干费'
          ,cast(0 as decimal(18,2)) as '洗衣费' 
          ,cast(0 as decimal(18,2)) as '过节费' 
          --,cast(0 as decimal(18,2)) as '误餐补助'
          ,cast(0 as decimal(18,2)) as '其他补贴' 
          ,cast(0 as decimal(18,2)) as '奖金或奖励'
          ,cast(0 as decimal(18,2)) as '应发合计'
          ------五险一金（个人）------
          ,cast(0 as decimal(18,2)) as '养老保险个人缴费' 
          ,cast(0 as decimal(18,2)) as '医疗保险个人缴费' 
          ,cast(0 as decimal(18,2)) as '大额医疗互助'
          ,cast(0 as decimal(18,2)) as '失业保险个人缴费' 
          ,cast(0 as decimal(18,2)) as '社会保险个人缴费合计'
          ,cast(0 as decimal(18,2)) as '住房公积金个人缴费'
          ------其他费用（个人）------
          ,cast(0 as decimal(18,2)) as '免税额度1' 
          ,cast(0 as decimal(18,2)) as '免税额度2'
          ,cast(0 as decimal(18,2)) as '企业年金个人缴费额中需缴税部分' 
          ,cast(0 as decimal(18,2)) as '应纳税所得额'
          ,cast(0 as decimal(18,2)) as '个人所得税'
          ,cast(0 as decimal(18,2)) as '扣工会费'
          ,a.EnterpriseAnnuity as '扣企业年金个人缴费额' 
          ,cast(0 as decimal(18,2)) as '扣停车费' 
          ,cast(0 as decimal(18,2)) as '税后扣款'
          ,cast(0 as decimal(18,2)) as '扣款小计'
          ,cast(0 as decimal(18,2)) as '实发工资'
          ,cast(0 as decimal(18,2)) as '生日费（现金或卡，计税）'
          ------五险一金（公司）------
          ,cast(0 as decimal(18,2)) as '住房公积金' 
          ,cast(0 as decimal(18,2)) as '养老保险' 
          ,cast(0 as decimal(18,2)) as '医疗保险'
          ,cast(0 as decimal(18,2)) as '失业保险' 
          ,cast(0 as decimal(18,2)) as '工伤保险' 
          ,cast(0 as decimal(18,2)) as '生育保险'
          ,cast(0 as decimal(18,2)) as '补充养老保险' 
          ,(case when a.payMonth between e.beginMonth and e.endMonth then isnull(e.costCompany*f.annuityMonthCount,0) else 0 end) as '企业年金'
          ,cast(0 as decimal(18,2)) as '员工综合保险' 
          ,cast(0 as decimal(18,2)) as '补充医疗保险'
          ,cast(0 as decimal(18,2)) as '职工福利费' 
          ,cast(0 as decimal(18,2)) as '辞退福利费' 
          ,cast(0 as decimal(18,2)) as '股份支付'
          ,isnull(a.totalPayable,0)-isnull(a.unionDues,0)-isnull(a.EnterpriseAnnuity,0)-isnull(a.afterPayableAdjustment,0)+80
          +(case when a.payMonth between d.beginMonth and d.endMonth then isnull(d.costCompany*f.fundMonthCount,0) else 0 end)
          +(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.endowmentComp*f.socialMonthCount,0) else 0 end)
          +(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.medicalComp*f.socialMonthCount,0) else 0 end)
          +(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.unemploymentComp*f.socialMonthCount,0) else 0 end)
          +(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.injuryComp*f.socialMonthCount,0) else 0 end)
          +(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.maternityComp*f.socialMonthCount,0) else 0 end)
          +(case when a.payMonth between c.beginMonth and c.endMonth then isnull(c.heatingfee*f.socialMonthCount,0) else 0 end) as '劳务派遣费'
          ,cast(0 as decimal(18,2)) as '劳动保护费1' 
          ,cast(0 as decimal(18,2)) as '劳动保险费' 
          ,cast(0 as decimal(18,2)) as '非货币性福利费'
          ------其他信息------
          ,'9100001040' as '预算中心编码' ,'大连市分公司人力资源部/教育培训部' as '预算中心名称' ,'' as '销售渠道'
          ,'' as '险类1代码及比例1' ,'' as '险类1代码及比例2' ,'' as '险类1代码及比例3' ,'' as '险类1代码及比例4' ,'' as '险类1代码及比例5'
          ,'' as '险类1代码及比例6' ,'' as '险类1代码及比例7' ,'' as '险类1代码及比例8' ,'' as '险类1代码及比例9' ,'' as '险类1代码及比例10'
      FROM  $View_Result_TotalPay a left join Base_Employees_Info b on a.sapno=b.sapno
                                                            left join Base_Social_Insurance c on a.sapno=c.sapno
                                                            left join Base_Housing_Fund d on a.sapno=d.sapno 
                                                            left join Base_Enterprise_Annuity e on a.sapno=e.sapno
                                                            left join Import_Payable f on a.sapno=f.sapno and a.payMonth=f.payMonth
      where b.contractType='派遣合同'
      and a.payMonth='$db_payMonth'
      and b.idno in (select idno from Base_Employees_Trans_Post)
EOT;




?>