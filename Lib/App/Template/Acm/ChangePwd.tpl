<!DOCTYPE>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title|default:"密码修改"}</title>
<script language="javascript" src="Script/CheckForm.js"></script>
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
<script src="Resource/Script/Common.js" type="text/javascript" ></script>

{literal}
<script language="javascript">
function myCheck(objForm) {
	if (objForm.passwd.value!=objForm.PasswdConfirm.value) {
		alert("密码不匹配!");
		return false;
	}
	return CheckForm(objForm);
}
$(function(){
	ret2cab();
});

</script>
{/literal}

</head>

<body style="margin:0">
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post" onSubmit="return myCheck(this)">
<input name="id" type="hidden" id="id" value="{$aUser.id}" />
<table id="mainTable">
  <tr>
    <td class="title">用户名：</td>
    <td><input name="userName" type="text" id="userName" value="{$aUser.userName}" disabled="disabled" style="width:150px"/></td>
    </tr>
  <tr>
    <td class="title">登记密码：</td>
    <td><input name="passwd" type="password" id="passwd" value="{$aUser.passwd}" check="^\S+$" warning="密码不能为空！" style="width:150px"/></td>
  </tr>
  <tr>
    <td class="title">密码确认：</td>
    <td><input name="PasswdConfirm" type="password" id="PasswdConfirm" value="{$aUser.passwd}" check="^\S+$" warning="重复密码不能为空！" style="width:150px"/></td>
  </tr>
  <tr>
  <td></td>
  <td><input type="submit" id="Submit" name="Submit" value='保存' class="_button" style="width:55px !important;height:28px; padding:5px 15px 5px 15px"></td>
  </tr>
  </table> 
</form>
</body>
</html>

