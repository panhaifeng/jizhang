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
	<td colspan="4" align="center" class="caption">{webcontrol type='GetAppInf' varName='compName'}{$title}</td>
</tr>
<tr> 
	<td class="tdItem" align="left">领料部门：{$arr_field_value.Dep.depName}</td>
	<td class="tdItem" align="left"></td>			
	<td class="tdItem" align="center">出库单号：{$arr_field_value.chukuNum}</td>
	<td class="tdItem" align="right">出库日期：{$arr_field_value.chukuDate}</td>
</tr>

<tr><td colspan="4">
	<table class="tableHaveBorder" cellspacing="0" cellpadding="3">
		<tr class="th">
			<td>原料编码</td>
			<td>原料名称</td>
			<td>规格</td>

			<td>单位</td>
			<td>长度</td>
			<td>重量</td>
			<td>数量</td>
			<!--
			<td>单价(元)</td>
			<td>金额(元)</td>
			-->
			<td>备注</td>
		</tr>
		{foreach from=$arr_field_value.Yl item=item} 
		{$item.pageBreak}
			<tr align="center"> 
				<td>{$item.ylCode|default:'&nbsp;'}</td>
				<td>{$item.ylName|default:'&nbsp;'}</td>
				<td>{$item.guige|default:'&nbsp;'}</td>
				<td>{$item.unit|default:'&nbsp;'}</td>
				<td>{$item.len|default:'&nbsp;'}</td>
				<td>{$item.cnt|default:'&nbsp;'}</td>
				<td>{$item.zhishu|default:'&nbsp;'}</td>
				<!--
				<td>{$item.danjia|default:'&nbsp;'}</td>
				<td>{$item.money|default:'&nbsp;'}</td>
				-->
				<td>{$item.memo|default:'&nbsp;'}</td>
			</tr>
		{/foreach}

	</table>
</td></tr>

<tr>
  <td align="left">制单人:{$user_name}</td>
  <td></td>
  <td colspan="2" align="right">签收人:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table>
<div id=prn align="center">
<input id=prnbutt onClick="return prnbutt_onclick()" type=button value="打 印" style="width:80px; padding-top:3px;"></div>
</body>
</html>