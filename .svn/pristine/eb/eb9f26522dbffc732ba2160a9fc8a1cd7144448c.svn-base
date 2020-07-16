<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
{literal}
<script language="javascript">
$(function(){
    $('#form1').validate({
        rules:{
            'item':'required',
            'itemName':'required',
            'value':'required'
        },
        submitHandler : function(form){
            $('#Submit').attr('disabled',true);
            form.submit();
        }
    });
});
</script>
{/literal}
<title>{$title}</title>
<link href="Resource/Css/EditCommon.css" type="text/css" rel="stylesheet">
{literal}
{/literal}
</head>
<body>
<form action="{url controller=$smarty.get.controller action='save'}" method="post" enctype="multipart/form-data" name="form1" id="form1">
<input name="id" type="hidden" id="id" value="{$aRow.id}" />
<table id="mainTable">
<tr>
    <td class="title">配置项英文名称：</td>
    <td><input name="item" type="text" id="item" value="{$aRow.item}" /><span class="bitian">*</span></td>
    </tr>
<tr>
  <td class="title">配置中文项名称：</td>
  <td class="title bitian"><input name="itemName" type="text" id="itemName" value="{$aRow.itemName}"/><span class="bitian">*</span></td>
  </tr>
<tr>
  <td class="title">配置项的值：</td>
  <td class="title bitian"><input name="value" type="text" id="value" value="{$aRow.value}" /><span class="bitian">*</span></td>
  </tr>
</table>

<table id="buttonTable">
<tr>
        <td>
        <input type="submit" id="Submit" name="Submit" value='保存'>
        <input type="button" id="Back" name="Back" value='返回' onClick="window.location.href='{url controller=$smarty.get.controller}&action=right'"></td>
    </tr>
</table>
</form>
</body>
</html>
