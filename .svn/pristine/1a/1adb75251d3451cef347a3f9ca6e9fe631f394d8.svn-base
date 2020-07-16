{*为popup定制的通用的模板，在showModaldialog时使用*}
<html>
<head>
<title>选择对话框</title>
<base target="_self"/>
{foreach from=$arr_js_css.cssFile item=css}
<link href="Resource/Css/{$css}" rel="stylesheet" type="text/css" />
{/foreach}
{foreach from=$arr_js_css.jsFile item=js}
<script language="javascript" src="Resource/Script/{$js}"></script>
{/foreach}
{literal}
<script language="javascript">
function onSelect(trObj){
	var ret = new Array();
	var arrMap = new Array();

{/literal}

	{foreach from=$arr_field_info key=key item=item}
		arrMap.push('{$key}');
	{/foreach}

{literal}

	for (var i=0;i<trObj.cells.length;i++){
		ret[arrMap[i]] = (trim(trObj.cells[i].innerText)||trim(trObj.cells[i].textContent));
		//ret[arrMap[i]] = (trObj.cells[i].innerText||trObj.cells[i].textContent);
	}
	//alert(ret["clientPihao"]);
	window.returnValue=ret;
	window.close();
}
</script>
{/literal}
</head>
<body style="margin-left:2px; margin-right:8px; height:100%">
<table width="100%" cellpadding="0" cellspacing="0">
<tr><td colspan="2">{include file="_SearchItem.tpl"}</tr>
<tr><td style="height:5px;" colspan="2"></td></tr>
</table>
<div id="div_content" class="c">{include file="_TableForSelect.tpl"}</div>
{$page_info}
</body>
</html>

{literal}
<script language="javascript">
/*根据客户端浏览器的高度自动设定*/
	var previousOnload = window.onload;
	window.onload = function () {
	  if(previousOnload) previousOnload();
	  	var topHeight 		= 24;
		var ieHeight		= document.body.clientHeight;
		var obj1 			= document.getElementById('div_content');
		//debugger;
		var contentHeight 	= ieHeight - obj1.offsetTop-topHeight;
		//alert(ieHeight+'-'+obj1.offsetTop+'-'+topHeight);
		obj1.style.height	=	contentHeight;
		document.getElementById('TableContainer').style.height=contentHeight-4;
	}
</script>
{/literal}