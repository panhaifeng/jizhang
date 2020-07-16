{*
	数据选择器,弹出对话框形式,
    如果父界面是用thickbox,必须在父窗体中定义
    function thickboxCallBack(ret,pos)
    其他ret表示缓存中的结果数组,
    pos是在get参数中传入的代表父窗体点击元素的位置的信息。
*}<html>
<head>

<title>{$title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/common.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
<link href="Resource/Css/Main.css" rel="stylesheet" type="text/css">
<link href="Resource/Css/Page.css" rel="stylesheet" type="text/css" >
{webcontrol type='LoadJsCss' src="Resource/Css/SearchItemTpl.css"}
{literal}
<base target="_self" />
<script language="javascript">
var _cache = new Array();
$(function(){
	var he = $('#searchGuide').height() || 0;
	var he1 = $('#divPage').height() || 0;
	//alert(he);
	//alert($(window).height());
	$('#TableContainer').height($(window).height()-he-he1-17);
    $('#checkAll').click(function(){
        $("input[name='sel[]']").each(function(i){
            $(this).click();
        });
    });

});
function changeDateNew(obj){
	var df = document.getElementById('dateFrom');
	var dt = document.getElementById('dateTo');
	var newDate = new Date();
	var y = newDate.getFullYear();
	if(obj.value=='1' || obj.value=='-1'){ //本月
    	var m = newDate.getMonth()+1;
	}
	if(obj.value=='2'){ //上月
    	var m = newDate.getMonth();
    	if(m=='00') {y = y-1; // 若为一月，则年份也要相应减1
    	m = 12;}
	}
    if(m<10) m="0"+m;
	df.value=y+'-'+m+'-'+'01';
	//如果为1、3、5、7、8、10、12一个月为31天
	if(m=='01'||m=='03'||m=='05'||m=='07'||m=='08'||m=='10'||m=='12')
	{
		dt.value=y+'-'+m+'-'+'31';
	}else if(m=='02') { //如果是2月份判断是否闰年
		if((y%4==0 && y%100!=0) || y%400==0){
			dt.value=y+'-'+m+'-'+'29';
		}else{
			dt.value=y+'-'+m+'-'+'28';;
		}
	}else{ //如果为4、6、9、11一个月为30天
		dt.value=y+'-'+m+'-'+'30';
	}
	if(obj.value=='3'){ //本年
		df.value = y+'-01-01';
		dt.value = y+'-12-31';
	}
	if(obj.value=='13') { //全部
		df.value='2010-01-01';
		dt.value=y+'-12-31';
	}

}
function changeCache(obj,json) {
	if(obj.checked) {
		_cache[obj.value] = json;
	} else delete _cache[obj.value];
	//alert(_cache.length);
}
function ret(pos) {
	//window.parent.callBack('adf');return false;
	var arr = new Array();
	for (key in _cache) {
		arr.push(_cache[key])
	}
	if(window.parent.ymPrompt){
        window.parent.ymPrompt.doHandler(arr,true);//return false;
    }else if(window.opener.onSelect!=null) {
			window.opener.onSelect(arr);//return false;
			window.close();
			return;
	}else if(window.parent.thickboxCallBack) {
			window.parent.tb_remove();
			window.parent.thickboxCallBack(arr,pos);
	}else{
		window.returnValue = arr;
		window.close();
	}

	//如果是iframe,改变opener中的变量,并执行callback(arr);
}
</script>

<style type="text/css">
.scrollTable{
	border-top:1px solid #999;
	border-left:1px solid #999;
	border-collapse:collapse;
	text-align:center
}
.scrollTable td {border-bottom:1px solid #999; border-right:1px solid #999; }
.scrollTable .th {background-color:#CCCCCC}
</style>
{/literal}
</head>
<body style="margin-left:2px; margin-right:8px; height:100%">
{if $smarty.get.no_edit!=1}
<table width="100%" cellpadding="0" cellspacing="0">
<tr><td colspan="2">{include file="_Search.tpl"}</tr>
<tr><td style="height:5px;" colspan="2"></td></tr>
</table>
{/if}
<div id="div_content" class="c">
<table width='100%' class="scrollTable" id="tb">
      {*字段名称*}
    <thead class="fixedHeader headerFormat">
      <tr id="fieldInfo" class="th">
        <td align="center"><input type="button" name="button" id="button" value="选择" onClick="ret({$smarty.get.pos})"></td>
	    {foreach from=$arr_field_info item=item}
        	<td align="center"  class="point" nowrap>{$item}</td>
        {/foreach}
		{if $arr_edit_info != ""}		{/if}
      </tr>
	  </thead>
      {*字段的值*}
      <tbody class="scrollContent bodyFormat" style="height:auto;">
      {foreach from=$arr_field_value item=field_value}
	  {if $field_value.display != 'false'}	{*显示条件行*}
  	  <tr class="fieldValue">
      	<td align="center">{if $field_value.isHave!='no'}<INPUT TYPE="checkbox" NAME="sel[]" onclick='changeCache(this,{$field_value|@json_encode|escape:'html'})' value='{$field_value[$unique_field]|default:$field_value.id}' style="margin:0px; border:0px;">{/if}</td>
		{foreach from=$arr_field_info key=key item=item}
        	{assign var=foo value="."|explode:$key}
		    {assign var=key1 value=$foo[0]}
		    {assign var=key2 value=$foo[1]}
			{assign var=key3 value=$foo[2]}
    	<td align="center" nowrap {if $field_value._bgColor!=''}bgcolor="{$field_value._bgColor}"{/if}>
            {if $key2==''}{$field_value.$key|default:'&nbsp;'}
            {elseif $key3==''}{$field_value.$key1.$key2|default:'&nbsp;'}
            {else}{$field_value.$key1.$key2.$key3|default:'&nbsp;'}
            {/if}</td>
    	{/foreach}
  	  </tr>
	  {/if}
      {/foreach}
      </tbody>
    </table>
</div>
<div id='divPage' style="float:left">{$page_info}</div>
</body>
</html>