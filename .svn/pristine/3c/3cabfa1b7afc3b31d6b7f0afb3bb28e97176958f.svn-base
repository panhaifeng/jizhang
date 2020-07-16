<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Common.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
<base target="_self">
{literal}
<style type="text/css">
body {text-align: left; font-size: 12px;}
.row { height:30px; margin-top: 5px; clear: both;}
#divMain div {clear: both; width:100%}
.text {width: 120px; text-align: right;border-bottom: 1px dotted #000;}
.kongjian * {width:200px;}
.memo { margin-left: 10px; color: red;}
</style>
{/literal}</head>
<body>
<form name="form1" id="form1" method="post" action="">
<div id='divMain'>
	<div class='row'>
		<span class='text'>是否业务员登记船样</span>
		<span class='kongjian'>
		<select name='AddChuanyangByTrader'>
			<option value='0'>否</option>
			<option value='1' {if $aRow.AddChuanyangByTrader==1}selected{/if}>是</option>			
		</select>
		</span>
		<span class='memo'>如果是业务员登记船样,当前用户只可以对自己客户的订单进行船样登记，否则无限制(默认)！</span>
	</div>
	<div class='row'>
		<span class='text'>厂编/花型显示为</span>
		<span class='kongjian'>
		<select name='manuCodeName'>
			<option value='厂编'>厂编</option>
			<option value='花型' {if $aRow.manuCodeName=='花型'}selected{/if}>花型</option>			
		</select>
		</span>
		<span class='memo'>华源,超人等直接以客户花型作为厂编进行流转，佳楠，蒙达，天元等则以厂编(默认)进行流转</span>
	</div>
	<div class='row'>
		<span style="width:100px;"></span>
		<span class='kongjian'>
		<input type="submit" id="Submit" name="Submit" value='保存' class="button" style="width:80px;">
		</span>
		<span class='memo'></span>
	</div>
</div>
</form>
</body>
</html>
