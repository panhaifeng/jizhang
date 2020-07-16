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
</script>
{/literal}
</head>
<body style="margin-top:0px" onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()">
<table align="center" width="700">
<tr width="700">
  <td colspan="3" align="center" class="caption">{webcontrol type='GetAppInf' varName='compName'}成品库存报表</td></tr>


<tr><td colspan="3">
	<table class="tableHaveBorder" cellspacing="0" cellpadding="3">
		<tr class="th">
		  <td >产品编码</td>
		  <td>名称</td>
		  <td>规格</td>
		  <td>单位</td>
          <td>库存数</td>
         
          <td>次品数</td>
          <td>最低库存</td>
          <td>最高库存</td>
	    </tr>
		
		{foreach from=$aRow item=item} 
			<tr align="center"> 
				<td>{$item.proCode|default:'&nbsp;'}</td>
				<td>{$item.proName|default:'&nbsp;'}</td>
				<td>{$item.guige|default:'&nbsp;'}</td>
				<td>{$item.unit|default:'&nbsp;'}</td>
				<td>{$item.kucunCnt|default:'&nbsp;'}</td>
                
                <td>{$item.cntCi|default:'&nbsp;'}</td>
				<td>{$item.cntMin|default:'&nbsp;'}</td>
                <td>{$item.cntMax|default:'&nbsp;'}</td>
			</tr>
		{/foreach}
  </table></td></tr>
<tr>
  <td height="21" align="left">制表人：{$smarty.session.REALNAME}</td>
  <td>&nbsp;</td>
  <td align="right" nowrap>打印日期：{$smarty.now|date_format:'%Y-%m-%d'}</td>
  
  </tr>
</table>
<div id=prn align="center">
<input id=prnbutt onClick="return prnbutt_onclick()" type=button value="打 印" style="width:80px; padding-top:3px;"></div>
</body>
</html>