<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{webcontrol type='GetAppInf' varName='compName'}{$title|default:'入库单'}</title>
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
<tr width="700"><td colspan="3" align="center" class="caption">{webcontrol type='GetAppInf' varName='compName'}{$title|default:'原料入库单'}</td></tr>
<tr> 
				<td class="tdItem">供应商：{$arr_field_value.Supplier.compName}</td>
				<td class="tdItem">入库单号：{$arr_field_value.rukuNum}</td>			
				<td class="tdItem">入库日期：{$arr_field_value.rukuDate}</td>
			</tr>

<tr><td colspan="3">
	<table class="tableHaveBorder" cellspacing="0" cellpadding="3">
		<tr class="th">
			<td>原料编码</td>
			<td>原料名称</td>
			<td>规格</td>
			
			<td>单位</td>
			<td>数量</td>
			<td>单价</td>
			<td>金额(元)</td>
			<td>备注</td>
		</tr>
		{foreach from=$arr_field_value.Yl item=item} 
			<tr align="center"> 
				<td>{$item.ylCode|default:'&nbsp;'}</td>
				<td>{$item.ylName|default:'&nbsp;'}</td>
				<td>{$item.guige|default:'&nbsp;'}</td>
				
				<td>{$item.unit|default:'&nbsp;'}</td>
				<td>{$item.cnt|default:'&nbsp;'}</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>{$item.memo|default:'&nbsp;'}</td>
			</tr>
		{/foreach}
		<tr class="th">
			<td>合计</td>
			<td colspan="3">&nbsp;</td>
			<td>{$total_cnt|default:'&nbsp;'}</td>
			<td>&nbsp;</td>
			<td colspan="3">&nbsp;</td>
		</tr>
	</table></td></tr>
<tr>
  <td align="left">制单人:{$user_name}</td>
  <td align="center">送货人:</td>
  <td align="right">签收人:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
  <td colspan="3">
	备注: 红色联/客户  黄色联/回单 白色联/存根
  </td>
</tr>
</table>
<div id=prn align="center">
	<input id=prnbutt onClick="return prnbutt_onclick()" type=button value="打 印" style="width:80px; padding-top:3px;"></div>
</body>
</html>