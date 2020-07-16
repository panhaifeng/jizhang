<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" src="Resource/Script/jquery.json.js"></script>
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<script language="javascript" src="Resource/Script/common.js"></script>
<link href="Resource/Script/autocomplete/autocomplete.css" type="text/css" rel="stylesheet" />
<link href="Resource/Css/EditCommon.css" type="text/css" rel="stylesheet" />
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<link href="Resource/Css/page.css" rel="stylesheet" type="text/css" />

<script language="javascript">
var _cache={$madan|default:'[]'};
{literal}
</script>
<style type="text/css">
.button{height:22px;}
</style>
{/literal}
</head>
<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='PrintFahuo'}" method="post" >
<fieldset>
<legend>打印基本信息</legend>
<table id="mainTable">
    <tr>
    <td class="title">缸号：</td>
    <td>
      <select id="ganghao" name="ganghao">
        <option value="1">显示</option>
        <option value="0">不显示</option>
        
      </select>
    </td>
  </tr>
  <tr>
    <td class="title">机号：</td>
    <td>
      <select id="zhiJiCode" name="zhiJiCode">
        <option value="1">显示</option>
        <option value="0">不显示</option>
        
      </select>
    </td>
  </tr>
    <tr>
    <td class="title">工序：</td>
    <td>
      <select id="gongxuName" name="gongxuName">
        <option value="1">显示</option>
        <option value="0">不显示</option>
      </select>
    </td>
  </tr>
</table>
</fieldset>
<table id="buttonTable">
<tr>
		<td>
      <input name="receiveId" type="hidden" id="receiveId" value="{$receiveId}">
  		<input type="submit" id="Submit" name="Submit" value='确定'>
  	  <!-- <input type="button" id="Back" name="Back" value='返回' onClick="window.location.href='{url controller=$smarty.get.controller}&action={$aRow.action}&kind=0'"> -->
    </td>
	</tr>
</table>
</form>
</body>
</html>
