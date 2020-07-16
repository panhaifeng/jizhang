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
            'fieldName':'required',
            'fieldType':'required',
            'fieldValue':'required',
            // 'defaultText':'required',
            'notNull':'required',
            'clear':'required',
            'sort':'required'
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
    <td class="title">属性名称：</td>
    <td width="70%"><input name="fieldName" type="text" id="fieldName" value="{$aRow.fieldName}" /><span class="bitian">*</span></td>
    </tr>
<tr>
  <td class="title">属性类别：</td>
  <td class="title bitian">
    <select name="fieldType" type="text" id="fieldType">
      <option value='select' {if $aRow.fieldType=='select'}selected{/if}>下拉选项</option>
      <option value='text' {if $aRow.fieldType=='text'}selected{/if}>文本框</option>
      <option value='radio' {if $aRow.fieldType=='radio'}selected{/if}>单选框</option>
    </select><span class="bitian">*</span></td>
  </tr>
<tr>
  <td class="title">属性值：</td>
  <td class="title bitian"><input name="fieldValue" type="text" id="fieldValue" value="{$aRow.fieldValue}" /><span class="bitian">*</span><br><span style="color:red">为select/radio时用英文逗号','分开</span></td>
  </tr>
<tr>
  <td class="title">默认文本或选中项：</td>
  <td class="title bitian"><input name="defaultText" type="text" id="defaultText" value="{$aRow.defaultText}" />
  </tr>
<tr>
    <td class="title">是否必填</td>
    <td>
    <select name="notNull" type="text" id="notNull">
      <option value='0' {if $aRow.notNull=='0'}selected{/if}>否</option>
      <option value='1' {if $aRow.notNull=='1'}selected{/if}>是</option>
    </select><span class="bitian">*</span></td>
    </tr>
<tr>
  <td class="title">新的检验是否清除原先设置的值：</td>
  <td class="title bitian">
    <select name="clear" type="text" id="clear">
      <option value='0' {if $aRow.clear=='0'}selected{/if}>否</option>
      <option value='1' {if $aRow.clear=='1'}selected{/if}>是</option>
    </select><span class="bitian">*</span></td>
  </tr>
<tr>
  <td class="title">排序：</td>
  <td class="title bitian"><input name="sort" type="text" id="sort" value="{$aRow.sort}" /><span class="bitian">*</span></td>
</tr>
<tr>
  <td class="title">类别：</td>
  <td class="title bitian">
    <select name="type" type="text" id="type">
      <option value='cj' {if $aRow.type=='cj'}selected{/if}>成检</option>
      <option value='pj' {if $aRow.type=='pj'}selected{/if}>坯检</option>
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
