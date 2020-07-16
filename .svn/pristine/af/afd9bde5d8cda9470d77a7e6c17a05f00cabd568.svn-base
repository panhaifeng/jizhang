<!--
	.在tablelist的基础上进行了美化
	.动态载入js与cs文件.
	.内容区域采用div形式,实现frame效果
-->
<html><head>
<title>{$title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
{foreach from=$arr_js_css.cssFile item=css}
<link href="Resource/Css/{$css}" rel="stylesheet" type="text/css" />
{/foreach}
{foreach from=$arr_js_css.jsFile item=js}
<script language="javascript" src="Resource/Script/{$js}"></script>
{/foreach}
<style type="text/css">
{literal}
form{margin:0px; padding:0px;}
div.c{overflow:auto;width:100%; border:solid 1px #86b5e7; padding:1px; background-color:#C9daf4;margin:1px;}
table.t thead tr{position:relative;top:expression(this.offsetParent.scrollTop-2);}
{/literal}
</style>

</head>
<body style="margin-left:2px; margin-right:8px; height:100%">
<table width="100%" cellpadding="0" cellspacing="0">
<tr style="height:20px;">
	<td style="padding-left:0px;">
		当前位置<font color="ff6600">&nbsp;&raquo;&nbsp;{$title}</font>
	</td>
	<td align="right">
		<span style="color:#CCCCCC">{$smarty.get.controller},{$smarty.get.action}</span>
		{if $add_display != 'none'}
		&nbsp;&nbsp;|&nbsp;<a href="Index.php?controller={$smarty.get.controller}&Action={$add_url|default:"Add"}&parentId={$smarty.get.parentId}" accesskey="A">新增记录</a>
		{/if}
		&nbsp;|&nbsp;
		<a href="javascript:window.top.frames['mainFrame'].document.location.reload()">刷新</a>
	</td>
</tr>
<tr><td colspan="2">{include file="_SearchItem.tpl"}</td></tr>
<tr><td style="height:2px;" colspan="2"></td></tr>
</table>
<div id="div_content" class="c">{include file="_TableForBrowse1.tpl"}</div>
{$page_info}

</body>
</html>

{literal}
<script language="javascript">
/*根据客户端浏览器的高度自动设定*/

	//var previousOnload = window.onload;
	//window.onload = function () {
	  //if(previousOnload) previousOnload();
	  	var topHeight 		= 24;
		var ieHeight		= document.body.clientHeight;
		var obj1 		= document.getElementById('div_content');
		//debugger;
		var contentHeight 	= ieHeight - obj1.offsetTop-topHeight;
		//alert(ieHeight);

		obj1.style.height	=	contentHeight-10;
		document.getElementById('TableContainer').style.height=obj1.style.height-4;
	//}
</script>
{/literal}

