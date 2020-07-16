<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>寄养详细</title>
{literal}
<style type="text/css">
td {font-size:12px; line-height:20px;};
</style>
{/literal}
</head>

<body>
<div id='divImg' style="margin-bottom:10px;">
<img src='{$smarty.get.path}' width='600' height='300' border="1" />
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th bgcolor="#CCCCCC" scope="col">产品编码</th>
    <th bgcolor="#CCCCCC" scope="col">品种</th>
    <th bgcolor="#CCCCCC" scope="col">颜色</th>
    <th bgcolor="#CCCCCC" scope="col">规格</th>
    <th bgcolor="#CCCCCC" scope="col">门幅</th>
    <th bgcolor="#CCCCCC" scope="col">克重</th>
  </tr>
  {foreach from=$rowset item=item}
  <tr>
    <td align="center">{$item.proCode}</td>
    <td align="center">{$item.proName}</td>
    <td align="center">{$item.color}</td>
    <td align="center">{$item.guige}</td>
    <td align="center">{$item.menfu}</td>
    <td align="center">{$item.kezhong}</td>
  </tr>
  {/foreach}
</table>
</body>
</html>
