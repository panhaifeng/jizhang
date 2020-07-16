{*2012-10-13 by jeff,1对多模式的列表
*}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/ext/adapter/ext/ext-base.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/ext-all.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/common.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/thickbox/thickbox.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/thickbox/thickbox.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/page.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/SearchItemTpl.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/resources/css/ext-all.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/TblList.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/tblList.js"}
    
{webcontrol type='LoadJsCss' src="Resource/Css/clientAuto.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/autocomplete/autocomplete.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/autocomplete/autocomplete.js"}
<script language="javascript">
var _head = {$arr_field_info|@json_encode};
var _head1 = {$arr_field_info2|@json_encode};
var _hasSearch = {if $arr_condition}true{else}false{/if};
var _printUrl = '{$print_href|default:null}';//打印的url
var _showExport = '{$fn_export|default:0}';//是否显示导出
var _sonWidth = parseInt({$sonWidth|default:400});//左边主表的宽度
var _subField = '{$sub_field}';//主表数据中的从表数据字段
var _debug = false;//打开调试，不进行viewport，不显示蒙版
var _arrDebug = [];
{literal}
if(_subField == '') {
	alert('必须定义sub_field');
}
try { //解决ie6下背景图片延迟的问题
	document.execCommand("BackgroundImageCache", false, true);
} catch(e) {}
Ext.onReady(function() {
	//alert($('#TableContainer').height());
	// $('#tblScorll').height(475);
	var divGrid = document.getElementById('divGrid');
	var divGrid1 = document.getElementById('divGridSon');

	$('#divList', divGrid)[0].onscroll = function() {
		duiqiHead(this.parentNode);
	}

	$('#divList', divGrid1)[0].onscroll = function() {
		duiqiHead(this.parentNode);
	}


	//开始布局
	var items = [];
	if(_hasSearch && document.getElementById('searchGuide')) items.push({
		xtype: 'box',
		region: 'north',
		height: 28,
		//frame:true,
		contentEl: 'searchGuide'
	});

	var bbar = [{
		xtype: 'tbitem',
		contentEl: 'p_bar'
	} //不能使用tbtext,会导致上下偏移失常。
	,
	'->',
	{
		text: ' 刷 新 ',
		iconCls: 'x-tbar-loading',
		cls: 'x-btn-mc',
		handler: function() {
			window.location.href = window.location.href
		}
	}];
	if(_printUrl || typeof(fnPrint) == 'function') {
		bbar.push('-');
		bbar.push({
			text: '打 印',
			iconCls: 'btnPrint',
			handler: function() {
				if(typeof(fnPrint) == 'function') {
					fnPrint();
					return;
				}
				window.location.href = _printUrl;
			}
		});
	}
	if(_showExport) {
		bbar.push('-');
		bbar.push({
			text: '导 出',
			iconCls: 'btnExport',
			handler: function() {
				window.location.href = _showExport;
				//alert('导出');
			}
		});
	}

	/*if(document.getElementById('p_bar')) items.push({
		  xtype: 'box',
		  region: 'south',
		  height: 22,
		  contentEl: 'p_bar'
		  //split:true
	  });*/
	if(divGrid) items.push({
		//id : 'gridView',
		//collapsible: false,
		id: 'viewMain',
		region: 'center',
		layout: 'border',
		bbar: bbar,
		items: [{
			title: '明细(选中上边某行显示)',
			id: 'gridView1',
			region: 'south',
			layout: 'fit',
			contentEl: 'divGridSon',
			split: true // enable resizing
			,
			onLayout: function() {
				layoutGrid(divGrid1)
			},
			//width: _sonWidth
			height:200
		}, {
			id: 'gridView',
			region: 'center',
			layout: 'fit',
			contentEl: 'divGrid',
			onLayout: function() {
				layoutGrid(divGrid)
			}
		}]
		//contentEl: 'divGrid',
		//autoScroll: false
	});
	if(!_debug) var viewport = new Ext.Viewport({
		layout: 'border',
		items: items
		//,afterRender:function(){}
		,
		onRender: function() {
			//根据字段定义设置表格的列宽
			setCellsWidth(divGrid, _head);
			setCellsWidth(divGrid1, _head1);
			//setCellWidth();
			//setCellWidth1();
			//鼠标移上变边框,选中显示子表
			$('#divRow', divGrid).hover(fnOver, fnOut).click(function() {
				var divList1 = $('#divList', divGrid1)[0];
				$('.rowSelected').removeClass('rowSelected');
				$(this).addClass('rowSelected');

				//子表中插入数据
				$(divList1).html('');
				var json = eval("(" + $(this).attr('subJson') + ")");
				var s = json[_subField];

				//构造html
				var html = [];

				for(var i = 0; s[i]; i++) {
					html.push("<div id='divRow' name='divRow'>");
					html.push('<table border="0" cellpadding="0" cellspacing="0" id="tblRow" name="tblRow">');
					html.push("<tr class='trRow'>");
					for(var k in _head1) {
						html.push("<td><div class='valueTdDiv'>");
						html.push(s[i][k]);
						html.push("</div></td>");
					}
					html.push("</tr>");
					html.push("</table>");
					html.push("</div>");
				}
				$(divList1).html(html.join(''));
				setCellsWidth(divGrid1, _head1);
				duiqiHead(divGrid1);
			});

			//表头以上改变cursor
			$('.headTd', divGrid).mousemove(changeCursor);
			//使列宽可调整
			var splitZone = new SplitDragZone(divGrid);

			//两个grid中tblHead命名相同导致无法拖动，需要大改
			//$('.headTd',divGrid1).mousemove(changeCursor);
			//var splitZone1 = new SplitDragZone(divGrid1);
			//_arrDebug.push(splitZone1);
			//splitZone1 = new SplitDragZone($('#divGrid')[0]);
			if(typeof(afterRender) != 'undefined') afterRender();
			Ext.QuickTips.init(); //使得所有标记了ext:qtip='点击显示订单跟踪明细'的元素显示出tip,比如
			Ext.apply(Ext.QuickTips.getQuickTip(), {
				dismissDelay: 0
			});
			//<input value='' ext:qtip='点击显示订单跟踪明细' />
			renderForm(document.getElementById('FormSearch'));
		}
	});
	
	//自动加载鼠标提示信息
	qtipToCellContents();
	//处理搜索
	autoSearchDiv();
	Ext.get('loading').remove();
	Ext.get('loading-mask').remove();
	
});

//选择月份后改变dateFrom和dateTo


function changeDate(obj) {
	//alert(obj.value);
	var df = document.getElementById('dateFrom');
	var dt = document.getElementById('dateTo');
	var d = new Date();
	var year = d.getFullYear();
	var m = parseInt(obj.value) + 1;
	if(m < 10) m = "0" + m;
	df.value = year + '-' + m + '-' + '01';
	//如果为1、3、5、7、8、10、12一个月为31天
	if(obj.value == '0' || obj.value == '2' || obj.value == '4' || obj.value == '6' || obj.value == '7' || obj.value == '9' || obj.value == '11') {

		dt.value = year + '-' + m + '-' + '31';
	}
	//如果是2月份判断是否闰年
	if(obj.value == '1') {
		if((year % 4 == 0 && year % 100 != 0) || year % 400 == 0) {
			dt.value = year + '-' + m + '-' + '29';
		} else {
			dt.value = year + '-' + m + '-' + '28';;
		}
	}
	if(obj.value == '13') {
		df.value = '{/literal}{php}echo date("2010-01-01");{/php}{literal}';
		dt.value = '{/literal}{php}echo date("Y-m-d",mktime(0,0,0,1,0,date("Y")+1));{/php}{literal}';
	}
	if(obj.value == '3' || obj.value == '5' || obj.value == '8' || obj.value == '10') {
		dt.value = year + '-' + m + '-' + '30';
	}

}

function form_submit() {
	/*$('#'+name+' input[type=text]').each(function(i){
			if(this.value==this.emptyText) this.value='';
			//alert(this.value);
		});*/
	document.getElementById("FormSearch").submit();
}
$(function(){
	//供应商自动完成
	$('#clientText').autocomplete('?controller=jichu_client&action=GetJsonByKey2', {
			minChars:1,
			remoteDataType:'json',
			useCache:false,
			sortResults:false,
			onItemSelect:function(v){
				$('#clientId').val(v.data[0].id);
        		$('#divModel').hide();
        		$('#btnMore').removeClass('active');
			}
	});
	//切换divModel的可见
	$('#btnMore').click(function(){
        $('#clientText').val('');
		if($('#divModel').is(':hidden')){
			var showTarget = $(this), target= $('#clientId');
			var showL = target.offset().left
			   ,showT = target.offset().top
			   ,showW = target.width()
			   ,showH = target.height();
		    $('#clientText').css("left", showL).css("top", showT).css("width",showW).css("height", showH+1);
			$('#divModel').css({'left':showL,'top':showT, 'width':(showW+2), 'height':(showH+2)}).show();
		    $(this).addClass('active');
			$('#clientText').focus();
		} else {
			$('#divModel').hide();
			$(this).removeClass('active');
		}
	});
});
</script>
{/literal}
{*需要在action中对sonTpl进行赋值,
$smarty->assign('sonTpl',sonTpl.tpl');
并新建一个sonTpl.tpl模板，如下：
literal
<script language='javascript'>
function fnPrint() {
	alert(1);
}
</script>
/literal
模板中可定义fnPrint(打印触发函数),或者print_href变量
*}
{if $sonTpl}{include file=$sonTpl}{/if}
</head>
<body style='position:static'>
<div id="loading-mask"></div>
  <div id="loading">
    <div class="loading-indicator"><img src="Resource/Script/ext/resources/images/default/grid/loading.gif" width="16" height="16" style="margin-right:8px;" align="absmiddle"/>正在载入...</div>
  </div>
{if $smarty.get.no_edit!=1}{include file="_Search.tpl"}{/if}
{*传递no_edit=1,可使_edit字段不显示*}
{*grid*}
<div id="divGrid" class="divGrid x-hide-display">
	<div id="divHead">
		<!--增加一个headOffset层，宽度为list的宽度+滚动条的宽度，为了在拖动时始终能看到表头背景-->
        <div id='divHeadOffset'>
        	<!--必须定义border="0" cellpadding="0" cellspacing="0",否则offsetWidth会超出-->
			<table border="0" cellpadding="0" cellspacing="0" id='tblHead'>
			  <tr>
				{foreach from=$arr_field_info item=item key=key}
					{if $key!='_edit' || $smarty.get.no_edit!=1}
					<td class='headTd'><div class='headTdDiv'>{if $item|@is_string==1}{if $key!='_edit' || $smarty.get.no_edit!=1}{$item}{/if}{else}{if $item.sort}<a href='{url controller=$smarty.get.controller action=$smarty.get.action sortBy=$key}'>{$item.text}</a>{else}{$item.text}{/if}{/if}</div></td>
					{/if}
				{/foreach}
			  </tr>
			</table>
		</div>
    </div>
	<div class="x-clear"></div>
    <div id="divList">
    	<!--必须定义border="0" cellpadding="0" cellspacing="0",否则offsetWidth会超出-->
	   {*字段的值*}
		{foreach from=$arr_field_value item=field_value}
		<div id='divRow' name='divRow' style='background-color:{cycle values="#ffffff,#fafafa"}'  subJson='{$field_value|@json_encode|escape}'>
		<table border="0" cellpadding="0" cellspacing="0" id="tblRow" name="tblRow">
			{if $field_value.display != 'false'}	{*显示条件行*}
			  <tr class='trRow' >
				{foreach from=$arr_field_info key=key item=item}
					{if $key!='_edit' || $smarty.get.no_edit!=1}
					  {assign var=foo value="."|explode:$key}
					  {assign var=key1 value=$foo[0]}
					  {assign var=key2 value=$foo[1]}
					  {assign var=key3 value=$foo[2]}
				<td {if $field_value._bgColor!=''} style="background-color:{$field_value._bgColor}" {/if}><div class='valueTdDiv'>
					  {if $key2==''}
						{$field_value.$key|default:'&nbsp;'}
					  {elseif $key3==''}
						{$field_value.$key1.$key2|default:'&nbsp;'}
					  {else}
						{$field_value.$key1.$key2.$key3|default:'&nbsp;'}
					  {/if}
				</div></td>
					{/if}
				{/foreach}
			  </tr>
			  {/if}
		  </table></div>
		{/foreach}
    </div>
</div>

{*字表*}
<div id="divGridSon" class="divGrid x-hide-display">
	<div id="divHead">
		<!--增加一个headOffset层，宽度为list的宽度+滚动条的宽度，为了在拖动时始终能看到表头背景-->
        <div id='divHeadOffset'>
        	<!--必须定义border="0" cellpadding="0" cellspacing="0",否则offsetWidth会超出-->
			<table border="0" cellpadding="0" cellspacing="0" id='tblHead'>
			  <tr>
				{foreach from=$arr_field_info2 item=item key=key}
					{if $key!='_edit' || $smarty.get.no_edit!=1}
					<td class='headTd'><div class='headTdDiv'>{if $item|@is_string==1}{if $key!='_edit' || $smarty.get.no_edit!=1}{$item}{/if}{else}{if $item.sort}<a href='{url controller=$smarty.get.controller action=$smarty.get.action sortBy=$key}'>{$item.text}</a>{else}{$item.text}{/if}{/if}</div></td>
					{/if}
				{/foreach}
			  </tr>
			</table>
		</div>
    </div>
	<div class="x-clear"></div>
    <div id="divList"></div>
</div>

<div id='p_bar'>{$page_info}
	{*<div style='float:left; margin-left:1px;font-size:13px;'>
		{$page_info}
	</div>
	<div style='float:right; margin-right:10px;'>
		<a href="javascript:window.location.href=window.location.href" title="刷新" style="margin-left:10px;"><img src="Resource/Image/refresh.gif" /></a>
		{if $fn_export}<a href="#" style="margin-left:10px;"><img src="Resource/Image/daochu.gif" /> 导出</a>{/if}
		{if $print_href}<a href="{$print_href}" title="打印" style="margin-left:10px;" target="_blank"><img src="Resource/Image/print.png" /></a>{/if}
	</div>*}
</div>
<div id="divModel" class="s-isindex-wrap s-model-set-menu menu-top" style="display:none;"> 
	<div><input type="text" class="clientText" id="clientText"/></div>
</div>
</body>
</html>