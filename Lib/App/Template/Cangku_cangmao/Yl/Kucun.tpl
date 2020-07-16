<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" src="Resource/Script/jquery.query.js"></script>
<script language="javascript" src="Resource/Script/common.js"></script>
<script language="javascript" src="Resource/Script/Calendar/WdatePicker.js"></script>
<script language="javascript" src="Resource/Script/TmisGrid.js"></script>
<script language="javascript" src="Resource/Script/ymPrompt/ymPrompt.js"></script>
<link href="Resource/Script/ymPrompt/skin/bluebar/ymPrompt.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="Resource/Script/TmisPopup.js"></script>

<script language="javascript" src="Resource/Script/thickbox/thickbox.js"></script>
<link href="Resource/Script/thickbox/thickbox.css" rel="stylesheet" type="text/css" />

<link href="Resource/Css/Main.css" rel="stylesheet" type="text/css" />
<link href="Resource/Css/page.css" rel="stylesheet" type="text/css" />
<link href="Resource/Css/Print.css" type="text/css" rel="stylesheet" />
{literal}
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
<style type="text/css">
*{margin:0px; padding:0px;}
table tr td{font-size:14px;}
select{font-size:14px;}
/*input{width:40px}*/
</style>
{/literal}
</head>
<body style="margin-left:5px; margin-right:5px;">
<table width="100%" cellpadding="0" cellspacing="0" style="">
<tr><td>{include file="_SearchItem.tpl"}</tr>
<tr><td>{include file="_TableForBrowse.tpl"}</td></tr>
<tr><td>{$page_info}</td></tr>
<tr><td>{$print_item}</tr>

</table>
<div id="prn" align="center">
<input type="button" id="button1" name="button1" value="打印" onclick="prnbutt_onclick()" />
</div>
</body>
</html>