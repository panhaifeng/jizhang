<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title|default:"权限编辑"}</title>
<link href="Resource/Css/Edit200.css" type="text/css" rel="stylesheet">
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{literal}
<script language="javascript">

$(function(){
	$('#form1').validate({
		rules:{
			funcName:"required"
		}
		,submitHandler:function(form){
			$('#Submit').attr('disabled',true);
			$('#Submit1').attr('disabled',true);
			form.submit();
		}
	});
	ret2cab();
});

</script>
{/literal}
</head>

<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post">
<input name="id" type="hidden" id="id" value="{$aRow.id}">
<input name="parentId" type="hidden" id="parentId" value="{$aRow.parentId}">
<div align="left" style="padding-left:10px">{$path_info}</div>

权限名称：<input name="funcName" type="text" id="funcName" value="{$aRow.funcName}" style="vertical-align:middle">


<table>
	<tr>
    	<td><input type="submit" id="Submit1" name="Submit1" value='保存并新增下一个' class="button"></td>
    	<td><input type="submit" id="Submit" name="Submit" value='保存' class="button"></td>
        <td><input type="button" id="Back" name="Back" value='返回' onClick="javascript:window.history.go(-1);" class="button"></td>
    </tr>
</table>
</form>
</body>
</html>
