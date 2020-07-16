<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
{literal}
<style type="text/css">
	td {font-size:12px}
</style>
{/literal}
</head>

<body>
<form id="form1" name="form1" method="post" action="{url controller=$smarty.get.controller action='SaveDanjia'}">
  <table width="400" border="0" align="center" cellpadding="1" cellspacing="1" style="border:1px solid #000">
    <tr>
      <td width="66" bgcolor="#CCCCCC">产品名称：</td>
      <td width="127">{$aRow.Product.proCode}</td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">出库数量：</td>
      <td>{$aRow.cnt}
      <input name="cnt" type="hidden" id="cnt" value="{$aRow.danjia}" /></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">订单单价：</td>
      <td>{$aRow.danjiaD}</td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">单价：</td>
      <td><input name="danjia" type="text" id="danjia" value="{$aRow.danjia}" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="submit" name="button" id="button" value="提交" />
      <input type="button" name="button2" id="button2" value="取消" onclick="window.parent.tb_remove()"/>
      <input name="id" type="hidden" id="id" value="{$aRow.id}" /></td>
    </tr>
  </table>
</form>
</body>
</html>
