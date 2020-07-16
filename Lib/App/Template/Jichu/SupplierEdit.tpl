<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{literal}
<script language="javascript">
$(function(){
	$('#form1').validate({
		rules:{		
			'compCode':'required',
			'compName':'required'
		},
		submitHandler : function(form){
			$('#Submit').attr('disabled',true);
			form.submit();
		}
	});
    
});
</script>
{/literal}
</head>
<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post" onSubmit="return CheckForm(this)">
<input name="id" type="hidden" id="id" value="{$aRow.id}" />
<table id="mainTable">
<tr>
	<td class="title">供应商编号：</td>
	<td><input name="compCode" type="text" id="compCode" value="{$aRow.compCode}" check="^[a-zA-Z0-9_]+$" warning="编码必须为字母数字或者下划线!"  onDblClick="setMaxCompCode(this)" readonly="true" /><span class="bitian">*</span></td>
	</tr>
<tr>
  <td class="title">名称：</td>
  <td><input name="compName" type="text" id="compName" value="{$aRow.compName}"/><span class="bitian">*</span></td>
</tr>


<tr>
  <td class="title">负责人：</td>
  <td><input name="people" type="text" id="people" value="{$aRow.people}" /></td>
  </tr>
<tr>
  <td class="title">地址：</td>
  <td><input name="address" type="text" id="address" value="{$aRow.address}" /></td>
</tr>
<tr>
  <td class="title">电话：</td>
  <td><input name="tel" type="text" id="tel" value="{$aRow.tel}" /></td>
</tr>
</table>

<table id="buttonTable">
<tr>
		<td>
		<!--<input type="submit" id="Submit" name="Submit" value='保存并新增下一个'>-->
		<input type="submit" id="Submit" name="Submit" value='保存'>
		<input type="button" id="Back" name="Back" value='返回' onClick="window.location.href='{url controller=$smarty.get.controller}&action=right'">
		</td>
	</tr>
</table>
</form>
</body>
</html>
