<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/common.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
<base target="_self" />
{webcontrol type='LoadJsCss' src="Resource/Css/Main.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/page.css"}
{literal}
<script language="javascript">
function ret(obj) {
	//window.parent.ymPrompt.doHandler(obj,true);//return false;
	window.returnValue=obj;
	window.close();

	//如果是iframe,改变opener中的变量,并执行callback(arr);
}
</script>
<style type="text/css">
*{margin:0px; padding:0px;}
table tr td{font-size:13px;}
</style>
{/literal}
</head>
<body style="margin-left:5px; margin-right:5px;">
<table width="100%" cellpadding="0" cellspacing="0" style="">
{if $smarty.get.no_edit!=1}
<tr><td>{include file="_SearchItem.tpl"}</tr>
{/if}
<tr><td>{include file="Popup/_TableForBrowse.tpl"}</td></tr>
<tr><td>{$page_info}</td></tr>
<tr><td>{$print_item}</td></tr>
</table>
</body>
</html>