<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <th align="right" scope="row">姓名：</th>
      <td><input name="employName" type="text" id="employName" value="{$aRow.employName}" /></td>
    </tr>
    <tr>
      <th align="right" scope="row">关联ERP用户：</th>
      <td><select name="userId" id="userId">
      <option value=''>选择用户</option>
      {webcontrol type='TMISOptions' model='Acm_User' selected=$aRow.userId}
      </select></td>
    </tr>
    <tr>
      <th scope="row">&nbsp;</th>
      <td><input type="submit" name="button" id="button" value="提交" />
      <input name="id" type="hidden" id="id" value="{$aRow.id}" /></td>
    </tr>
  </table>
</form>
</body>
</html>
