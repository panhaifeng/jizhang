<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>
<body>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td align="center" bgcolor="#999999">保存</td>
    <td align="center" bgcolor="#999999">sn</td>
    <td align="center" bgcolor="#999999">snInfo</td>
    <td align="center" bgcolor="#999999">关联用户</td>
  </tr>
  {foreach from=$rowset item=item}
  <form action="{url controller=tool action=saveDongtai}" method="post" enctype="multipart/form-data" name="form1">
  <tr bgcolor="{cycle values="#eeeeee,#d0d0d0"}">
    <td align="center">
      <input type="submit" name="button" id="button" value="保存" />
      <input name="id" type="hidden" id="id" value="{$item.id}" /></td>
    <td align="center"><input name="sn" type="text" id="sn" value="{$item.sn}" size="20" /></td>
    <td align="center"><input name="sninfo" type="text" id="sninfo" value="{$item.sninfo}" size="20" /></td>
    <td align="center">
      <select name="userId" id="userId">
      {webcontrol type='Tmisoptions' model='Acm_User' selected=$item.userId}
      </select>
    </td>
  </tr>
  </form>
  {/foreach}
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
</body>
</html>
