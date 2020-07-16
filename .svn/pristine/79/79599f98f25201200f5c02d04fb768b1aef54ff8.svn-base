{*2012-8-15 重构 by jeff,新特点
1,增加了扩展性，如果需要在Tbllist.tpl的基础上增加其他的功能，可以进行如下操作:
   比如，我需要在Tbllist.tpl现有的基础上增加其他的script，只需要
   a,在action中$smarty->assign('sonTpl', $sonTpl);//sonTpl为一个子模板，可在其中定义css文件和js代码
   b,新建一个sonTpl文件，定义function afterRender() {页面渲染后的执行动作},或者其他需要的函数等
1,固定表头*
2,表头超出内容宽度以内容宽度为准*
3,自带导出功能,导出使用javascript，必须进行相关设置，如果有异常，弹出提醒界面，提醒用户应该如何设置。自动去掉表格中的html代码
//改设计老车觉得不友好，去掉，还是使用后台导出的模式。
4,利用ext的布局管理自适应，自动匹配页面的高和宽。
5,样式改进，行背景色交替，onmouseover效果
6,打印需要使用lodop的自带分页功能进行打印。保证表头的锁定。
7,实现按字段排序的功能
td需要有padding
15,已设置的搜索条件，应该以红色表示，或者在页面的某个位置显示，以提醒客户目前的搜索条件。
9,搜索条件多了后，搜索栏不能自适应，很难看。
*}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/ext/adapter/ext/ext-base.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/ext-all.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.query.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/common.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/thickbox/thickbox.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/thickbox/thickbox.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/SearchItemTpl.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/resources/css/ext-all.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/TblList.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/tblList.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/TmisGrid.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/layer/layer.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/clientAuto.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/autocomplete/autocomplete.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/autocomplete/autocomplete.js"}
<script language="javascript">
var _head = {$arr_field_info|@json_encode};
var _hasSearch = {if $arr_condition}true{else}false{/if};
var _printUrl = '{$print_href|default:null}';//打印的url
var _showExport = '{$fn_export|default:null}';//是否显示导出
var _debug = false;//打开调试，不进行viewport，不显示蒙版
{literal}
try{//解决ie6下背景图片延迟的问题
	document.execCommand("BackgroundImageCache", false, true);
}
catch (e){
}
Ext.onReady(function() {
	var divGrid = document.getElementById('divGrid');

	$('#divList', divGrid)[0].onscroll = function() {
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
		xtype: 'tbtext',
		contentEl: 'p_bar'
	}, '->',
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
	if(divGrid) items.push({
		id: 'gridView',
		collapsible: false,
		region: 'center',
		layout: 'fit',
		contentEl: 'divGrid',
		autoScroll: false,
		bbar: bbar
	});
	if(!_debug) var viewport = new Ext.Viewport({
		layout: 'border',
		items: items,
		onLayout: function() {
			layoutGrid(divGrid)
		},
		onRender: function() {
			setCellsWidth(divGrid, _head);

			//鼠标移上变边框
			$('[name="divRow"]', divGrid).hover(fnOver, fnOut);
			//表头以上改变cursor
			$('.headTd', divGrid).mousemove(changeCursor);

			//使列宽可调整
			var splitZone = new SplitDragZone(divGrid);
			//载入插件
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
function changeDate(obj){
	//alert(obj.value);
	var df = document.getElementById('dateFrom');
	var dt = document.getElementById('dateTo');
	var d=new Date();
	var year=d.getFullYear();
	var m=parseInt(obj.value)+1;
	if(m<10) m="0"+m;
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

function form_submit(){
	document.getElementById("FormSearch").submit();
}
$(function(){
	//客户自动完成
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
    //供应商自动完成
	$('#supplyText').autocomplete('?controller=jichu_supplier&action=GetJsonByKey', {
			minChars:1,
			remoteDataType:'json',
			useCache:false,
			sortResults:false,
			onItemSelect:function(v){
				$('#supplierId').val(v.data[0].id);
        		$('#divModel2').hide();
        		$('#btnMore2').removeClass('active');
			}
	});
	//切换divModel的可见
	$('#btnMore2').click(function(){
        $('#supplyText').val('');
		if($('#divModel2').is(':hidden')){
			var showTarget = $(this), target= $('#supplierId');
			var showL = target.offset().left
			   ,showT = target.offset().top
			   ,showW = target.width()
			   ,showH = target.height();
		    $('#supplyText').css("left", showL).css("top", showT).css("width",showW).css("height", showH+1);
			$('#divModel2').css({'left':showL,'top':showT, 'width':(showW+2), 'height':(showH+2)}).show();
		    $(this).addClass('active');
			$('#supplyText').focus();
		} else {
			$('#divModel2').hide();
			$(this).removeClass('active');
		}
	});


	//加工户自动完成
	$('#jiagonghuText').autocomplete('?controller=jichu_jiagonghu&action=GetJsonByKey', {
			minChars:1,
			remoteDataType:'json',
			useCache:false,
			sortResults:false,
			onItemSelect:function(v){
				$('#jiagonghuId').val(v.data[0].id);
        		$('#divModel3').hide();
        		$('#btnMore3').removeClass('active');
			}
	});
	//切换divModel3的可见
	$('#btnMore3').click(function(){
        $('#jiagonghuText').val('');
		if($('#divModel3').is(':hidden')){
			var showTarget = $(this), target= $('#jiagonghuId');
			var showL = target.offset().left
			   ,showT = target.offset().top
			   ,showW = target.width()
			   ,showH = target.height();
		    $('#jiagonghuText').css("left", showL).css("top", showT).css("width",showW).css("height", showH+1);
			$('#divModel3').css({'left':showL,'top':showT, 'width':(showW+2), 'height':(showH+2)}).show();
		    $(this).addClass('active');
			$('#jiagonghuText').focus();
		} else {
			$('#divModel3').hide();
			$(this).removeClass('active');
		}
	});
	

	//颜色自动完成
	$('#colorText').autocomplete('?controller=jichu_color&action=GetJsonByKey', {
		minChars:1,
		remoteDataType:'json',
		useCache:false,
		sortResults:false,
		onItemSelect:function(v){
			$('#colorSelect').val(v.data[0].id);
    		$('#divModel4').hide();
    		$('#btnMore4').removeClass('active');
		}
	});
	$('#btnMore4').click(function(){
        $('#colorText').val('');
		if($('#divModel4').is(':hidden')){
			var showTarget = $(this), target= $('#colorSelect');
			var showL = target.offset().left
			   ,showT = target.offset().top
			   ,showW = target.width()
			   ,showH = target.height();
		    $('#colorText').css("left", showL).css("top", showT).css("width",showW).css("height", showH+1);
			$('#divModel4').css({'left':showL,'top':showT, 'width':(showW+2), 'height':(showH+2)}).show();
		    $(this).addClass('active');
			$('#colorText').focus();
		} else {
			$('#divModel4').hide();
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
<div id="divGrid" class="divGrid">
  <div id="divHead"> 
    <!--增加一个headOffset层，宽度为list的宽度+滚动条的宽度，为了在拖动时始终能看到表头背景-->
    <div id='divHeadOffset'> 
      <!--必须定义border="0" cellpadding="0" cellspacing="0",否则offsetWidth会超出-->
      <table border="0" cellpadding="0" cellspacing="0" id='tblHead'>
        <tr> {foreach from=$arr_field_info item=item key=key}
          {if $key!='_edit' || $smarty.get.no_edit!=1}
          <td class='headTd'><div class='headTdDiv'>{if $item|@is_string==1}{if $key!='_edit' || $smarty.get.no_edit!=1}{$item}{/if}{else}{if $item.sort}{$item.text}<a href='{url controller=$smarty.get.controller action=$smarty.get.action sortBy=$key sort="asc"}'><img src='Resource/Image/toolbar/up.gif' border="0" title='升序'/></a><a href='{url controller=$smarty.get.controller action=$smarty.get.action sortBy=$key sort="desc"}'><img src='Resource/Image/toolbar/down.gif' border="0" title='降序'/></a>{else}{$item.text}{/if}{/if}</div></td>
          {/if}
          {/foreach} </tr>
      </table>
    </div>
  </div>
  <div class="x-clear"></div>
  <div id="divList"> 
    <!--必须定义border="0" cellpadding="0" cellspacing="0",否则offsetWidth会超出--> 
    
    {*字段的值*}
    {include file=$TblListView|default:'TblListView.tpl'} </div>
</div>
<div id='p_bar'>{$page_info}
  {*
  <div style='float:left; margin-left:1px;font-size:13px;'> {$page_info} </div>
  <div style='float:right; margin-right:10px;'> <a href="javascript:window.location.href=window.location.href" title="刷新" style="margin-left:10px;"><img src="Resource/Image/refresh.gif" /></a> {if $fn_export}<a href="#" style="margin-left:10px;"><img src="Resource/Image/daochu.gif" /> 导出</a>{/if}
    {if $print_href}<a href="{$print_href}" title="打印" style="margin-left:10px;" target="_blank"><img src="Resource/Image/print.png" /></a>{/if} </div>
  *} </div>
<div id="divModel" class="s-isindex-wrap s-model-set-menu menu-top" style="display:none;"> 
	<div><input type="text" class="clientText" id="clientText"/></div>
</div>
<div id="divModel2" class="s-isindex-wrap s-model-set-menu menu-top" style="display:none;"> 
	<div><input type="text" class="supplyText" id="supplyText"/></div>
</div>
<div id="divModel3" class="s-isindex-wrap s-model-set-menu menu-top" style="display:none;"> 
	<div><input type="text" class="jiagonghuText" id="jiagonghuText"/></div>
</div>
<div id="divModel4" class="s-isindex-wrap s-model-set-menu menu-top" style="display:none;"> 
	<div><input type="text" class="colorText" id="colorText"/></div>
</div>

</body>
</html>