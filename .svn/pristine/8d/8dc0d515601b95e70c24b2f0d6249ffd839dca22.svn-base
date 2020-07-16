<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{webcontrol type='GetAppInf' varName='compName'}{$title|default:'销售清单'}</title>
<link href="Resource/Css/print.css" rel="stylesheet" type="text/css" />
{literal}
<style type="text/css">
td {FONT-SIZE:14px;}
.haveBorder{border-top:1px solid #000000; border-left:1px solid #000}
.haveBorder td { border-bottom:1px solid #000; border-right:1px solid #000}
.caption {font-size:22px; font-weight:bold;}
.caption span{font-size:12px; font-weight:normal}
.th td{font-weight:bold; text-align:center}
.pageBreak{ page-break-after:always;}
.tableHaveBorder td{
  font-size: 18;
}
.tdItem{
  font-size: 17;
}

</style>
{/literal}

<script language=javascript id=clientEventHandlersJS> 
{literal}
<!-- 

function prnbutt_onclick() { 
	window.print(); 
	return true; 
} 

function window_onbeforeprint() { 
	prn.style.visibility ="hidden"; 
	return true; 
} 

function window_onafterprint() { 
	prn.style.visibility = "visible"; 
	return true; 
} 
//--> 
{/literal}
</script>
</head>
<body style="margin-top:0px" onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()">
<table align="center" width="700">
<tr width="700">
	<td colspan="3" align="center" class="caption">{webcontrol type='GetAppInf' varName='compName'}{$title}</td>
    
</tr>
<tr>
	 <td colspan="3"  align="right">&nbsp;</td>
</tr>
<tr>
  <td colspan="3" class="tdItem" align="left">订单号：{$arr_field_value.chukuOrder}</td>
</tr>
<tr > 
	<td class="tdItem" align="left">收货单位：{$arr_field_value.Client.compName}</td>	
    <td class="tdItem" align="center">出库单号：{$arr_field_value.chukuNum}</td>			
	<td class="tdItem" align="right">日期：{$smarty.now|date_format:'%Y-%m-%d'}</td>
</tr>

<tr><td colspan="3">
  <table class="tableHaveBorder" cellspacing="0" cellpadding="3">
    <tr class="th">
      <td>产品编码</td>
      <td>产品名称</td>
    
      <td>规格</td>
      <!--<td>颜色</td>-->
      <td>单位</td>
      <td>数量</td>
      
      <!--
			<td>单价(元)</td>
			<td>金额(元)</td>
			-->
      <td>备注</td>
      </tr>
    {foreach from=$arr_field_value.Pro item=item} 
    {$item.pageBreak}
    <tr align="center"> 
      <td>{$item.proCode|default:'&nbsp;'}</td>
      <td>{$item.proName|default:'&nbsp;'}</td>
     
      <td style="font-size:{if strlen($item.guige)>25}13px{else}18{/if}">{$item.guige|default:'&nbsp;'}</td>
      <!--<td>{$item.color|default:'&nbsp;'}</td>-->
      <td>{$item.unit|default:'&nbsp;'}</td>
      <td>{$item.cnt1|default:'&nbsp;'}</td>
      
      <!--
				<td>{$item.danjia|default:'&nbsp;'}</td>
				<td>{$item.money|default:'&nbsp;'}</td>
				-->
      <td>{$item.memo|default:'&nbsp;'}</td>
      </tr>
    {/foreach}
    <tr align="center">
      <td><strong>合计</strong></td>
      <td>&nbsp;</td>
      
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>{$total_cnt}</td>
     
      <td>&nbsp;</td>
    </tr>
    </table>
</td></tr>
<tr>
  <td style='font-size:17' >收货单位：</td>
  <td style='font-size:17' >经手人：</td>
  <td style='font-size:17' colspan="2" align="right" nowrap>制单人：{$smarty.session.REALNAME}</td>
  </tr>
</table>
<div id=prn align="center">
<input id=prnbutt onClick="return prnbutt_onclick()" type=button value="打 印" style="width:80px; padding-top:3px;"></div>
</body>
</html>