<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>

<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<script language="javascript" src="Resource/Script/Common.js"></script>
<script language="javascript" src="Resource/Script/ymPrompt/ymPrompt.js"></script>
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<link href="Resource/Script/ymPrompt/skin/bluebar/ymPrompt.css" rel="stylesheet" type="text/css" />
<link href="Resource/Css/Edit200.css" rel="stylesheet" type="text/css" />
<link href="Resource/Css/EditCommon.css" type="text/css" rel="stylesheet" />
<script language="javascript">
{literal}
$(function(){
	$('#btnOk').click(function(){
		window.returnValue = $('#guozhangDate').val();
		window.close();
	});
});
{/literal}
</script>
</head>
<body>
<fieldset>
<legend>设置过账时间</legend>
<table id="mainTable">
  <tr>
    <td class="title">输入过账时间</td>
  <td><input tupe="text" name="guozhangDate" id="guozhangDate" value="" onclick='calendar()'></td>
  </tr>
  
</table>
</fieldset>
<table id="buttonTable">
<tr>
		<td>
        <input type="button" id="btnOk" name="btnOk"  value=' 确 定 '>
		</td>
  </tr>
</table>

</body>
</html>
