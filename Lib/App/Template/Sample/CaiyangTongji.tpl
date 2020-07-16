<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/common.js"}
<link href="Resource/Css/Print.css" type="text/css" rel="stylesheet" />
{literal}
<script language="javascript">
function prnbutt_onclick() {
	window.print();
	return true;
}
function expbutt_onclick(){
	var pos=$('[name="fn"]').val();
	var dateFrom=$('#dateFrom').val();
	var dateTo=$('#dateTo').val();
	url=pos+"&dateFrom="+dateFrom+"&dateTo="+dateTo;
	window.location.href =url;
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
</script>
<script language="javascript">
function changeDate(obj){
	//alert(obj.value);
	var df = document.getElementById('dateFrom');
	var dt = document.getElementById('dateTo');
	 

	var d=new Date();
	var year=d.getFullYear();
	var m=parseInt(obj.value)+1;
	df.value=year+'-'+m+'-'+'01';
	//如果为1、3、5、7、8、10、12一个月为31天
	if(obj.value=='0'||obj.value=='2'||obj.value=='4'||obj.value=='6'||obj.value=='7'||obj.value=='9'||obj.value=='11')
	{
		
		dt.value=year+'-'+m+'-'+'31';
	}
	//如果是2月份判断是否闰年
	if(obj.value=='1') {
		if((year%4==0 && year%100!=0) || year%400==0){
			dt.value=year+'-'+m+'-'+'29';
		}else{
			dt.value=year+'-'+m+'-'+'28';;
		}
	}
	if(obj.value=='13') {
		df.value='{/literal}{php}echo date("2010-01-01");{/php}{literal}';
		dt.value='{/literal}{php}echo date("Y-m-d",mktime(0,0,0,1,0,date("Y")+1));{/php}{literal}';
	}
	if(obj.value=='3'||obj.value=='5'||obj.value=='8'||obj.value=='10')
	{
		dt.value=year+'-'+m+'-'+'30';
	}
	
}
</script>
<style type="text/css">
input {
	/*for Mozilla*/
 	outline: 1px solid #1590BE ;
	border: 1px solid #FFFFFF !important;
	height: 22px !important;
	line-height: 22px !important;

	/*for IE7*/
	> border: 1px solid #ccc !important;
	> height: 22px !important;
	> line-height: 20px !important;

	/*for IE*/
	border: 1px solid #999 ;
	height: 22px;
	line-height: 20px;
	padding-left:2px;
}
input[type="button"],input[type="submit"]{
	background-color:#eee !important;	
}
.td{ border:0px; font-size:14px; font-family:"黑体";}
</style>
{/literal}
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<form action="{url controller=$smarty.get.controller action='tongji'}" method="post" name="form1">
<tr>
<td bgcolor="#efefef" valign="middle">
<select name="dateSelect" id="dateSelect" onChange="changeDate(this)">
				<option value= -1 style="color:#CCC">选择日期</option>
					{section loop=12 name=loop} 
                    	<option value={$smarty.section.loop.index}>{$smarty.section.loop.index+1}月份</option>
                    {/section}
				<option value=13>全部</option>
            </select>
        <input name="dateFrom" type="text" id="dateFrom" value="{$arr_condition.dateFrom}" size="10" onClick="calendar()">到<input name="dateTo" type="text" id="dateTo" value="{$arr_condition.dateTo}" size="10" onClick="calendar()"/>  <input type="submit" name="submit" value="搜索">
</td>
</tr>
</form>
{foreach from=$rowset item=item}
<tr >
<td >
{if $item.caiyangInfo!=''}
<font color="#0000FF"><b>{$item.employName}</b></font>采样信息
<table width="90%" border='1' style="border-width:1; border-color:#333">
<tr>
<td class="td"><b>日期</b></td>
<td class="td"><b>产品编号</b></td>
<td class="td"><b>规格</b></td>
<td class="td"><b>颜色</b></td><!-- 
<td class="td"><b>经纬密</b></td>
<td class="td"><b>成分</b></td> -->
<td class="td"><b>门幅</b></td>
<td class="td"><b>克重</b></td>
<td class="td"><b>客户</b></td>
<td class="td"><b>采样数量</b></td>
<td class="td"><b>金额</b></td>
<td class="td"><b>运费</b></td>
<td class="td"><b>备注</b></td>
</tr>
{foreach from=$item.caiyangInfo item=item2}
<tr>
<td class="td">{$item2.chukuDate|default:''}</td>
<td class="td">{$item2.proCode|default:''}</td>
<td class="td">{$item2.guige|default:''}</td>
<td class="td">{$item2.color|default:''}</td><!-- 
<td class="td">{$item2.jingwei|default:''}</td>
<td class="td">{$item2.chengfen|default:''}</td> -->
<td class="td">{$item2.menfu|default:''}</td>
<td class="td">{$item2.kezhong|default:''}</td>
<td class="td">{$item2.clientName|default:''}</td>
<td class="td">{$item2.chukuCnt|default:''}</td>
<td class="td">{$item2.money|default:''}</td>
<td class="td">{$item2.yunfei|default:''}</td>
<td class="td">{$item2.memo|default:''}</td>
</tr>
{/foreach}
</table>
<br>
{/if}
</td>
</tr>
{/foreach}
</table>
<div id="prn" align="center">
<input type="button" id="buttonExport" name="buttonExport" value="导出" onClick="expbutt_onclick()"/>
<input type="button" id="buttonPrint" name="buttonPrint" value="打印" onClick="prnbutt_onclick()"/>
<input type="hidden" id="fn" name="fn" value="{$fn_export}" />
</div>
</body>
</html>
