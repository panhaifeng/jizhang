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
            'name':'required',
            'type':'required'
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
<table id="mainTable_">
<tr>
    <td class="title">疵点名称：</td>
    <td><input name="name" type="text" id="name" value="{$aRow.name}" /><span class="bitian">*</span></td>
    </tr>
<tr>
  <td class="title">快捷键：</td>
  <td class="title bitian"><input name="shortcutKey" type="text" id="shortcutKey" value="{$aRow.shortcutKey}"/></td>
  </tr>
<tr>
  <td class="title">疵点类型：</td>
  <td class="title bitian"><select name="type" type="text" id="type">
      <option value='0' {if $aRow.type=='0'}selected{/if}>普通疵点</option>
      <option value='1' {if $aRow.type=='1'}selected{/if}>门幅</option>
      <option value='2' {if $aRow.type=='2'}selected{/if}>克重</option>
    </select><span class="bitian">*</span></td>
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
