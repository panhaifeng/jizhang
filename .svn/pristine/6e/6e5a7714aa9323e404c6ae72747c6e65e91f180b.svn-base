<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{literal}
<style type="text/css">
<!--
.style1 {
	font-size: 14pt;
	font-weight: bold;
}
body {
	margin-left: 0px;
	margin-top: 0px;
}
td {FONT-FAMILY:"宋体,Arial";FONT-SIZE:14px;COLOR:#000;}
.title {FONT-SIZE: 12pt}

#tr {height:30px;}
-->
</style>
<script language="javascript">
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
</script>
{/literal}
<link href="Resource/Css/Print.css" rel="stylesheet" type="text/css" />
</head>
{* 	table_with:表宽
	hr_height:标题行高度
	tr_height:其它行高度
*}
<body style="text-align:center" onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()">

<div style=" margin-bottom:5px;"><font style="font-weight:bold; font-size:24px;  double #000;">常州金马劲飞布业纺织有限公司客户往来余额对账单</font></div>
<div style="text-align:left"><font style="font-weight:bold; font-size:18px;  double #000;">客户：{$title1}</font></div>
<div style="text-align:left">截止{$date}，贵单位尚欠我公司货款合计人民币：{$flag}{$moneyJieyuCap}（{$moneyJieyu}元）,如有异议请于签收之日起3日内提出, 否则视为已确认此对帐单余额, 谢谢！	</div>
<div style="text-align:left"></font></div>
{if $sonTpl}{include file=$sonTpl}{/if}
<div style=" margin-bottom:5px;"><font style="font-weight:bold; font-size:24px; border-bottom:1px double #000;">{$title}</font></div>
{*主表信息,每行显示3个字段*}
{if $arr_main_value}
<table width="98%" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr id="tr">
  	{assign var=i value=0}
    {assign var=index value=0}
    {assign var=countMain value=$arr_main_value|@count}
    {foreach from=$arr_main_value item=item key=key}
    <td align="left">{$key}：{$item}</td>
    {math equation="(x+1)%3" x=$i assign=i}
    {math equation="x+1" x=$index assign=index}
    {if $i==0 && $index>0 && $index<$countMain}
    </tr>
    <tr id="tr">
    {/if}
    {/foreach}
  </tr>
</table>
{/if}

{*明细列表*}
<table id="dataList" width="98%" style="BORDER-COLLAPSE: collapse;" bordercolor="#000000" border="2" align="center" cellpadding="2" cellspacing="0">
  {*字段名称*}
	<tr id="hr" style="height:{$hr_height|default:'30px'};">
		{foreach from=$arr_field_info key=key item=item}
        {if $key!='_edit'}
	 <td class="ptd">{if $item|@is_string==1}{$item}{else}{$item.text}{/if}</td>
        {/if}
		{/foreach}
  </tr>
  {*字段的值*}

 {foreach from=$arr_field_value item=field_value}
  <tr id="tr">
	{foreach from=$arr_field_info key=key item=item}
    	{if $key!='_edit'}
			{assign var=foo value="."|explode:$key}
            {assign var=key1 value=$foo.0}
            {assign var=key2 value=$foo.1}
            {assign var=key3 value=$foo.2}
            <td>
                {if $key2==''}{$field_value.$key|default:'&nbsp;'}
                {elseif $key3==''}{$field_value.$key1.$key2|default:'&nbsp;'}
                {else}{$field_value.$key1.$key2.$key3|default:'&nbsp;'}{/if}
            </td>
         {/if}
	{/foreach}
  </tr>
  {/foreach}
</table>
<div id="prn" align="center">
<input type="button" id="button1" name="button1" value="打印" onclick="window_onbeforeprint();prnbutt_onclick();window_onafterprint();" />
<a href="?controller=caiwu_ys_guozhang&action=duizhang&clientId={$arr_condition.clientId}&export=1&dateFrom={$arr_condition.dateFrom}&dateTo={$arr_condition.dateTo}"><input type="button" value="导出"></a>
</div>
</body>
</html>
