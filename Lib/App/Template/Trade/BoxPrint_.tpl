<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{webcontrol type='GetAppInf' varName='compName'}{$title}</title>
{literal}
<style type="text/css">
body {
Crollbar-Face-color:#6699CC;
Scrollbar-Highlight-Color:#6699CC;
Scrollbar-Shadow-Color:#6699CC;
Scrollbar-3Dlight-Color:#6699CC;
Scrollbar-Arrow-Color:#6699CC;
Scrollbar-Track-Color:#6699CC;
Scrollbar-Darkshadow-Color:#6699CC;
margin:0px; padding:0px;
}

.haveBorder{border-top:1px solid #000000; border-left:1px solid #000}
.haveBorder td { border-bottom:1px solid #000; border-right:1px solid #000}
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
<style type="text/css">
{literal}
.boxTable{height:270px;}
.boxTable td {font-size:20px; font-weight:bold;}
{/literal}
</style>
</head>
<body style="margin-top:0px; margin-left:0px;" onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()">
{foreach from=$arr_field_value.Products item=item} 
{section name=row loop=$item.boxCnt}
<table width='350px' class="haveBorder boxTable" cellpadding="0" cellspacing="0" style="margin-top:0px;padding-top:0px;">
	<tr><td colspan="4" align="center">多顺电器装箱单</td></tr>
	<tr><td>购货方</td><td colspan="3">{$arr_field_value.Client.compName}</td></tr>
	<tr><td>名称/型号</td><td colspan="3">{$item.proName|default:'&nbsp;'}</td></tr>
	<tr><td>订单总量</td><td>{$item.cnt|default:'&nbsp;'}</td><td>总箱数</td><td style="width:50px;">{$item.boxCnt|default:'&nbsp;'}</td></tr>
	<tr><td>每箱数量</td><td>{$item.perBoxCnt|default:'&nbsp;'}</td><td>箱编码</td><td>&nbsp;</td></tr>
	<tr><td>插件型号</td><td>{$item.guige|default:'&nbsp;'}</td><td>插件排列</td><td>&nbsp;</td></tr>
	<tr><td>输出电压</td><td>&nbsp;</td><td>线长</td><td>&nbsp;</td></tr>
	<tr><td>生产日期</td><td>{$arr_field_value.dateOrder}</td><td>检验员</td><td>&nbsp;</td></tr>
</table>
	<br><br>
	{/section}
{/foreach}
<div id=prn align="center">
	<input id=prnbutt onClick="return prnbutt_onclick()" type=button value="打 印" style="width:80px; padding-top:3px;"></div>
</body>
</html>