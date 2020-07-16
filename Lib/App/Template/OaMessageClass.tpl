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
			'className':'required'
		}
    ,submitHandler : function(form){
      $('#Submit').attr('disabled',true);
      form.submit();
    }
	});

});


</script>
<style type="text/css">
.mainTableStyle100 .title {
	width:100px;
}
.mainTableStyle100 {
	width:100%;
}
.title {
	width:100px;
}
</style>
{/literal}
</head>
<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post">
  <input name="id" type="hidden" id="id" value="{$aRow.id}">  
  <table width="100%" >
    <tr>
      <td width="100" align="right"  style="width:130px;">类别名称：</td>
      <td><input name="className" type="text" id="className" value="{$aRow.className}"/></td>
    </tr>
    <tr>
      <td width="100" align="right"  style="width:130px;">是否弹出提示窗：</td>
      <td><select name="isWindow" id='isWindow'>
      <option value="0" {if $aRow.isWindow==0}selected{/if}>是</option>
      <option value="1" {if $aRow.isWindow==1}selected{/if}>否</option>
      </select></td>
    </tr>
  </table>
  <table id="buttonTable" >
    <tr>
      <td><!--<input type="submit" id="Submit" name="Submit" value='保存并新增下一个'>-->
        
        <input type="submit" id="Submit" name="Submit" value='保存'>
        <input type="button" id="Back" name="Back" value='返回' onClick="window.location.href='{url controller=$smarty.get.controller}&action=right'"></td>
    </tr>
  </table>
</form>
</body>
</html>
