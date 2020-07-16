<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
<!-- <link href="Resource/Css/EditCommon.css" type="text/css" rel="stylesheet"> -->
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<!-- <link href="Resource/Css/Submit.css" type="text/css" rel="stylesheet" /> -->
{literal}
<script language="javascript">
$(function(){
	$('#form1').validate({
		rules:{		
			//'code':'required',
			'guige':'required',
			'pinming':'required'
		},
		submitHandler : function(form){
			$('#Submit').attr('disabled',true);
			form.submit();
		}
	});
});
</script>
<style type="text/css">
  #beizhu{width: 350px;height: 150px;}
</style>

{/literal}
</head>
<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='XinzengSave'}" method="post">
<div style="float:left; padding-left:50px; line-height:150%; width:100%;">
<div style="float:left; border-bottom:1px solid #CCC; padding-bottom:1px; white-space:nowrap;">

</div>
</div>
<input name="id" type="hidden" id="id" value="{$aRow.id}" />
<input name="flag" type="hidden" id="flag" value="{$smarty.get.flag}" />
<table id="mainTable">

<tr>
  <td class="title">查看备注：</td>
</tr>
<tr>
  <td><textarea type="text" id="beizhu" name="memo" readonly="true">{$aRow.memo}</textarea></td>
</tr>

</table>
</form>
</body>
</html>
