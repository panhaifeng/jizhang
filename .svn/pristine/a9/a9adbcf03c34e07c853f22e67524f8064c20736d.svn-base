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
{/literal}
</script>
</head>
<body style="margin-top:0px" onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()">
<table align="center" width="700">
<tr width="700"><td colspan="4" align="center" class="caption">{webcontrol type='GetAppInf' varName='compName'}{$title}</td></tr>
<tr> 
				<td class="tdItem">订单号：{$arr_field_value.orderCode}</td>
				<td class="tdItem"><!--客户：{$arr_field_value.Client.compName}--></td>
				<td class="tdItem">车间：{$arr_field_value.Chejian.name}</td>			
				<td class="tdItem">订单日期：{$arr_field_value.dateOrder}</td>
			</tr>

<tr><td colspan="4">
	<table class="tableHaveBorder" cellspacing="0" cellpadding="3">
		<tr class="th">
			<td>产品编码</td>
			<td>品牌/产品名称/规格</td>
			<td>技术参数</td>
			<td>单位</td>
			<td>数量</td>
			<!--
			<td>单价</td>
			<td>金额(元)</td>-->
			<td>交货日期</td>
			<td>备注</td>
		</tr>
		{foreach from=$arr_field_value.Products item=item} 
			<tr align="center"> 
				<td>{$item.proCode|default:'&nbsp;'}</td>
				<td>"{$item.pingpai|default:'&nbsp;'}" / {$item.proName|default:'&nbsp;'} / {$item.guige|default:'&nbsp;'}</td>
				<td>{$item.tecParam|default:'&nbsp;'}</td>
				<td>{$item.unit|default:'&nbsp;'}</td>
				<td>{$item.cnt|default:'&nbsp;'}</td>
				<!--<td>{$item.danjia|default:'&nbsp;'}</td>
				<td>{$item.money|default:'&nbsp;'}</td>-->
				<td>{$item.jiaohuoDate|default:'&nbsp;'}</td>
				<td>{$item.memo|default:'&nbsp;'}</td>
			</tr>
		{/foreach}
		<tr class="th">
			<td>合计</td>
			<td colspan="3">&nbsp;</td>
			<td>{$total_cnt|default:'&nbsp;'}</td>
			<td colspan="2">&nbsp;</td>
		</tr>
	</table></td></tr>
<tr><td colspan="4">
</td></tr>
</table>
<div id=prn align="center">
	<input id=prnbutt onClick="return prnbutt_onclick()" type=button value="打 印" style="width:80px; padding-top:3px;"></div>
</body>
</html>