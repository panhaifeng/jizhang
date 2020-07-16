<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{webcontrol type='GetAppInf' varName='compName'}{$title}</title>
<link href="Resource/Css/print.css" rel="stylesheet" type="text/css" />
{literal}
<style type="text/css">
td {FONT-SIZE:14px;}
.haveBorder{border-top:1px solid #000000; border-left:1px solid #000}
.haveBorder td { border-bottom:1px solid #000; border-right:1px solid #000}
.caption {font-size:22px; font-weight:bold;}
.caption span{font-size:12px; font-weight:normal}
.th td{font-weight:bold; text-align:center}
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
</script>
{/literal}

</head>
<body style="margin-top:0px" onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()">
<table align="center" width="700">
<tr width="700"><td colspan="3" align="center" class="caption">{webcontrol type='GetAppInf' varName='compName'}{$title|default:'销售订单'}</td></tr>
<tr> 
				<td class="tdItem">客户：{$arr_field_value.Client.compName}</td>
				<td class="tdItem" align="center">客户订单号：{$arr_field_value.clientOrderCode}</td>
				<td align="right" class="tdItem">签单日期：{$arr_field_value.dateOrder}</td>
			</tr>

<tr><td colspan="3">
  <table class="tableHaveBorder" cellspacing="0" cellpadding="3">
    <tr class="th">
      <td>产品名称</td>
      <td>规格</td>
      <td>单位</td>
      <td align="right">数量</td>
      <td align="right">单价</td>
      <td align="right">金额</td>
      <td>备注</td>
      <!--
			<td>单价</td>
			<td>金额(元)</td>-->
      </tr>
    {foreach from=$arr_field_value.Products item=item} 
    <tr align="center"> 
      <td>{$item.proName|default:'&nbsp;'}</td>
      <td>{$item.guige|default:'&nbsp;'}</td>
      <td>{$item.unit|default:'&nbsp;'}</td>
      <td >{$item.cnt|default:'&nbsp;'}</td>
      <td >{$item.danjia|default:'&nbsp;'}</td>
      <td >{$item.money}</td>
      <td>{$item.memo|default:'&nbsp;'}</td>
      <!--<td>{$item.danjia|default:'&nbsp;'}</td>
				<td>{$item.money|default:'&nbsp;'}</td>-->
      </tr>
    {/foreach}
    <tr>
      <td colspan="3"><strong>合计</strong></td>
      <td align="center">{$total_cnt|default:'&nbsp;'}</td>
      <td align="right">&nbsp;</td>
      <td align="center" >{$total_money|default:'&nbsp;'}</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td colspan="7">合计大写金额：{$money}</td>
      </tr>
  </table></td></tr>
</table>
<div id=prn align="center">
<input id=prnbutt onClick="return prnbutt_onclick()" type=button value="打 印" style="width:80px; padding-top:3px;"></div>

</body>
</html>