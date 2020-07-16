<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
<script charset="utf-8" src="Resource/Script/kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="Resource/Script/kindeditor/lang/zh_CN.js"></script>
<script charset="utf-8" src="Resource/Script/kindeditor/plugins/code/prettify.js"></script>
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{literal}
<script language="javascript">
$(function(){
	$('#form1').validate({
		rules:{
			'title':'required',
			'buildDate':'required'
		}
		,submitHandler : function(form){
			$('#Submit').attr('disabled',true);
			form.submit();
		}
	});

});

KindEditor.ready(function(K) {
			var editor1 = K.create('textarea[name="content"]', {
				cssPath : 'Resource/Script/kindeditor/plugins/code/prettify.css',
				uploadJson : 'Resource/Script/kindeditor/php/upload_json.php',
				fileManagerJson : 'Resource/Script/kindeditor/php/file_manager_json.php',
				allowFileManager : true,
				afterCreate : function() {
					var self = this;
				}
			});
			prettyPrint();
		});
</script>
<style type="text/css">
.mainTableStyle100 .title{width:100px;}
.mainTableStyle100 {width:100%;}
.title { width:100px;}
</style>
{/literal}
</head>
<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='savePop'}" method="post">
<input name="id" type="hidden" id="id" value="{$aRow.id}">
<input type="hidden" name="kindName" value="订单变动通知" >
<table width="100%" >
<tr>
<td width="100" align="right"  style="width:100px;">弹窗标题：</td>
<td width="681">
  <input name="title" type="text" id="title" value="{$aRow.title}" style="width:300px;" />
</td>
</tr>

<tr>
  <td align="right" class="title">弹窗有效日期：</td>
  <td><input name="dateFrom" type="text" id="dateFrom" value="{$aRow.dateFrom|default:$smarty.now|date_format:'%Y-%m-%d'}" onclick="calendar()" /> ~
  <input name="dateTo" type="text" id="dateTo" value="{$aRow.dateTo|default:$smarty.now|date_format:'%Y-%m-%d'}" onclick="calendar()" />
  注:如果要取消弹窗，请将有效日期设置为今天之前！
  </td>
  </tr>

<tr>
  <td align="right" class="title">内容：</td>
	<td style="height:400px">
    	<textarea name="content" style="width:700px;height:400%;visibility:hidden;">{$aRow.content}</textarea>
    <!--<textarea name="content" id="content" style="width:300px; height:200px;">{$aRow.content}</textarea>--></td>
</tr>

</table>

<table id="buttonTable" >
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
