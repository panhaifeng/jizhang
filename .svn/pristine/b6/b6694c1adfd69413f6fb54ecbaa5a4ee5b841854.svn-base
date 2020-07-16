<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{webcontrol type='GetAppInf' varName='compName'}{$title|default:'出库单'}</title>
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
<tr width="700"><td colspan="3" align="center" class="caption">{webcontrol type='GetAppInf' varName='compName'}{$title|default:'出库单'}</td></tr>
<tr> 
				<td class="tdItem">出库单号：{$arr_field_value.Chuku.chukuCode}</td>
				<td class="tdItem">&nbsp;</td>			
				<td class="tdItem" align="right">出库日期：{$arr_field_value.Chuku.chukuDate}</td>
  </tr>

<tr><td colspan="3">
	<table class="tableHaveBorder" cellspacing="0" cellpadding="3">
		<tr class="th">
			<td>产品编码</td>
			<td>产品名称</td>
			<td>规格</td>
			<td>批号</td>
			<td>数量</td>
			<!-- <td>单价</td> -->
			<!-- <td>次品数</td> -->
			<!--<td>单价</td>
			<td>金额(元)</td>-->
			<td>备注</td>
		</tr>
			<tr align="center"> 
				<td>{$arr_field_value.Products.proCode|default:'&nbsp;'}</td>
				<td>{$arr_field_value.Products.proName|default:'&nbsp;'}</td>
				<td>{$arr_field_value.Products.guige|default:'&nbsp;'}</td>
				<td>{$arr_field_value.pihao|default:'&nbsp;'}</td>
				<td>{$arr_field_value.cnt|default:'&nbsp;'}</td>
				<td>{$arr_field_value.memo|default:'&nbsp;'}</td>
			</tr>
		<tr align="center">
			<td><strong>合计</strong></td>
			<td colspan="3">&nbsp;</td>
			<td>{$arr_field_value.cnt|default:'&nbsp;'}</td>
			<td>&nbsp;</td>
			<!-- <td>{$totalCi|default:'&nbsp;'}</td> -->
			<!-- <td>&nbsp;</td> -->
			<!--<td colspan="3">{$total_money}元</td>-->
		</tr>
       
  </table>
</td></tr>
<tr>
  <td height="18" colspan="3" align="right">制单人：{$smarty.session.REALNAME} </td></tr>
</table>
<div id=prn align="center">
	<input id=prnbutt onClick="return prnbutt_onclick()" type=button value="打 印" style="width:80px; padding-top:3px;"></div>
</body>
</html>