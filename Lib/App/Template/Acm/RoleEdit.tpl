<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title|default:"角色信息编辑"}</title>
<script language="javascript" src="Script/CheckForm.js"></script>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{literal}
<script language="javascript">
$(function(){
	$('#form1').validate({
		rules:{
			roleName:"required"
		}
		,submitHandler : function(form){
			$('[name="Submit"]').attr('disabled',true);
			form.submit();
		}
	});
	
	//ret2cab();
});
</script>
{/literal}
</head>
<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post">
<input name="id" type="hidden" id="id" value="{$aRole.id}" />
<input name="fromAction" type="hidden" id="fromAction" value="{$smarty.get.fromAction}" />
<table id="mainTable">
  <tr>
    <td class="title">角色名称：</td>
    <td><input name="roleName" type="text" id="roleName" value="{$aRole.roleName}" check="^\S+$" warning="部门名称不能为空！"/></td>
    </tr>
</table>
<table id="buttonTable">
	<tr>
    	<td><input type="submit" id="Submit" name="Submit" value='保存并新增下一个'></td>
    	<td><input type="submit" id="Submit" name="Submit" value='保存'></td>
        <td><input type="button" id="Back" name="Back" value='返回' onClick="javascript:window.location.href='{url controller=Acm_Role action='right'}'"></td>    
    </tr>
</table>
</form>
</body>
</html>

