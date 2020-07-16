<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
此功能可以将一个坯纱规格下的所有采购信息，领用信息，送染厂信息等全部调整为另外一个坯纱规格，这样，原坯纱规格就可以删除了
<form id="form1" name="form1" method="post" action="{url controller=tool action=GuigeMove}">
  <p>需要清除的guigeId:
    <input type="text" name="guigeId0" id="guigeId0" />
  </p>
  <p>保留的guigeId:
    <input type="text" name="guigeId1" id="guigeId1" />
  </p>
  <p>
    <input type="submit" name="button" id="button" value="提交" />
  </p>
  <p>注：改动前请注意备份数据</p>
</form>
</body>
</html>
