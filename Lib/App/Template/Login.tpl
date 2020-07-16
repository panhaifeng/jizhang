<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{webcontrol type='GetAppInf' varName='systemName'}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{literal}
<script language="javascript">
function form_submit(){
	document.getElementById("login").submit();
}
function form_reset(){
	document.getElementById("login").reset();
}
function reloadcode(){
    var verify=document.getElementById('safecode');
    verify.setAttribute('src','code.php?'+Math.random());
}

$(function(){
	document.onkeydown=function(e){
		var ev = document.all ? window.event : e;
		if(ev.keyCode==13){
			form_submit();
		}
	}

});
</script>

<style type="text/css">
BODY {
	PADDING-RIGHT: 0px; PADDING-LEFT: 0px; FONT-SIZE: 12px; BACKGROUND: url(Resource/Image/login_03.gif) #152753 repeat-x; PADDING-BOTTOM: 0px; MARGIN: 0px; OVERFLOW: hidden; COLOR: #adc9d9; PADDING-TOP: 0px
}
#center_right {
	BACKGROUND: url(Resource/Image/login_10.gif) no-repeat; FLOAT: left; WIDTH: 211px; HEIGHT: 84px
}
#footer {
	BACKGROUND: url(Resource/Image/login_11.gif) no-repeat; MARGIN: 0px auto; WIDTH: 847px; HEIGHT: 206px
}
INPUT {
	BORDER-RIGHT: #153966 1px solid; BORDER-TOP: #153966 1px solid; FONT-SIZE: 12px; BORDER-LEFT: #153966 1px solid; WIDTH: 100px; COLOR: #283439; BORDER-BOTTOM: #153966 1px solid; HEIGHT: 17px; BACKGROUND-COLOR: #87adbf
}
.chknumber_input {
	WIDTH: 40px
}
.user {
	MARGIN: 6px auto
}
UNKNOWN {
	MARGIN: 4px auto
}
.chknumber {
	PADDING-LEFT: 3px; MARGIN-BOTTOM: 3px; TEXT-ALIGN: left
}
.button {
	MARGIN: 15px auto
}
IMG {
	CURSOR: pointer; BORDER-TOP-STYLE: none; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none; BORDER-BOTTOM-STYLE: none
}
FORM {
	PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}

</style>
{/literal}
</head>
<body>
<form name="login" id="login" method="post" action="?controller=Login&action=Login">
<table width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr><td align="center"><img src="Resource/Image/login_04.jpg" width="847" height="318"/></td></tr>
	<tr>
	<td align="center">
  	<table height="84px" width="847" cellpadding="0" cellspacing="0">
		<tr>
			<td><img src="Resource/Image/login_06.gif" width="381" height="84"/></td>
			<td>
				<table background="Resource/Image/login_07.gif" width="162px" height="84px" cellpadding="0" cellspacing="0">
					<tr>
						<td>用户名：	<input type="text" name="username" id="username" /></td>
					</tr>
					<tr>
						<td>密　码：	<input type="password" name="password" id="password" /></td>
					</tr>
					<tr>
						<td>验证码：	<input type="text" name="sn" id="sn" /></td>
					</tr>
				</table>
			</td>
			<td><img src="Resource/Image/login_08.gif" width="26" height="84"/></td>
			<td background="Resource/Image/login_09.gif" width="67" height="84">
				<table cellpadding="0" cellspacing="0">
					<tr><td><img src="Resource/Image/dl.gif" width="57" height="20" onclick="form_submit()" ></td></tr>
					<tr><td style="height:10px;"></td></tr>
					<tr><td><img src="Resource/Image/cz.gif" width="57" height="20" onclick="form_reset()"></td></tr>
				</table>
			</td>
			<td><img src="Resource/Image/login_10.gif" width="211" height="84" /></td>
	</tr></table>
</td></tr></table>
</form>
<div id="footer"></div>
</body>
</html>
<script language="javascript">
{literal}
document.getElementById("username").focus();
//document.getElementById("username").onkeyup=function(){if(event.keyCode==13)setTimeout('document.getElementById("username").focus()',1)}
{/literal}
</script>
