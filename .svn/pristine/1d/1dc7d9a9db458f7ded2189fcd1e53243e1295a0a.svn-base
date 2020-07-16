<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}

{literal}
<script language="javascript">
$(function(){
	$.validator.addMethod("checkPass", function(value, element) {
		var o = document.getElementById('passwd');	
		if(o.value!=value || value=='')
		return false;
		return true;
	}, "密码不匹配!");
	
	$('#form1').validate({
		rules:{
			userName:"required",
			realName:"required",
			passwd:"required",
			PasswdConfirm:"checkPass"
		},
		submitHandler : function(form){
			$('[name="Submit"]').attr('disabled',true);
			form.submit();
		}
	});
	//ret2cab();
});
</script>
<style type="text/css">
#divMain{width:100%; border:0px #D4E2F4 solid; overflow:auto; height:305px;}
#divLeft{width:47%; border:1px #D4E2F4 solid; float:left; overflow:auto;height:300px;}
#divRight{width:47%; border:1px #D4E2F4 solid; float:right;overflow:auto;height:300px;}
#tblLeft{ width:100%;}
#tblLeft tr td{border:0px; border-bottom:1px #D4E2F4 dotted; padding-left:20px;}
#tblLeft tr td input{border:0px;}
#tblRight{ width:100%;}
#tblRight tr td{border:0px; border-bottom:1px #D4E2F4 dotted; padding-left:20px;}
#tblRight tr td input{border:0px;}
</style>
{/literal}

</head>

<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post">
<input name="id" type="hidden" id="id" value="{$aUser.id}" />
<input name="from" type="hidden" id="from" value="right" />
<fieldset>
<legend>基本信息</legend>
<table id="mainTable">
  <tr>
    <td align="right" class="tdTitle">用户名：</td>
    <td><input name="userName" type="text" id="userName" value="{$aUser.userName}"/></td>
    <td align="right" class="tdTitle">真实姓名：</td>
    <td><input name="realName" type="text" id="realName" value="{$aUser.realName}"/></td>
    <td align="right" class="tdTitle">身份证号：</td>
    <td><input name="shenfenzheng" type="text" id="shenfenzheng" value="{$aUser.shenfenzheng}"/></td>
    </tr>
  <tr>
    <td align="right" class="tdTitle">登陆密码：</td>
    <td><input name="passwd" type="password" id="passwd" value="{$aUser.passwd}"/></td>
    <td align="right" class="tdTitle">密码确认：</td>
    <td><input name="PasswdConfirm" type="password" id="PasswdConfirm" value="{$aUser.passwd}" check="^\S+$" warning="重复密码不能为空！"/></td>
    </tr>
 </table>
 </fieldset>
 <fieldset>
<legend>指定信息</legend>
<div id="divMain">
<div id='divLeft'>
<table id="tblLeft">
<tr>
<td align="center" style="background-color:#D4E2F4; font-size:12px; height:22px;">选择角色</td>
</tr>
{foreach from=$aUser.Role item=item}
<tr>
	<td><input type="checkbox" name="roles[]" id="ckb{$item.id}" value="{$item.id}" {if $item.isChecked==1}checked{/if}>
    <label for="ckb{$item.id}">{$item.roleName}</label></td>
</tr>
{/foreach}
</table>
</div>
<div id="divRight">
<table id="tblRight">
<tr>
<td align="center" style="background-color:#D4E2F4; font-size:12px; height:22px;">选择业务员
<font color="#999999">（指定业务员后，可看到该业务员的所有订单）</font></td>
</tr>
{foreach from=$aUser.Trader item=item}
<tr>
	<td><input type="checkbox" name="traders[]" id="trader{$item.id}" value="{$item.id}" {if $item.isChecked==1}checked{/if}>
    <label for="trader{$item.id}">{$item.employName}</label></td>
</tr>
{/foreach}
</table>
</div>
</div>
</fieldset>
<table id="buttonTable">
<tr>
    	<td><input type="submit" id="Submit" name="Submit" value='保存并新增下一个' class="button"></td>
    	<td><input type="submit" id="Submit" name="Submit" value='保存' class="button"></td>
        <td><input type="button" id="Back" name="Back" value='返回' onClick="javascript:window.history.go(-1);" class="button"></td>
    </tr>
</table>
</form>
</body>
</html>
