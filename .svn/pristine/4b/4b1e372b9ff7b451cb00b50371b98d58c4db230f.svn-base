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
<form id="form1" name="form1" method="post" action="{url controller=$smarty.get.controller action='changeSave'}">
  <table width="400" border="0" align="center" cellpadding="1" cellspacing="1" style="border:1px solid #000">
    <tr>
      <td width="66" bgcolor="#CCCCCC">编码</td>
      <td width="127">{$row.proCode}</td>
      <td width="197">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">名称</td>
      <td>{$row.proName}</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">规格</td>
      <td>{$row.guige}</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">单位</td>
      <td>{$row.unit}</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">当前库存数</td>
      <td>{$row.kucunCnt}</td>
      <td>调整为
      <input name="kucunCnt" type="text" id="kucunCnt" size="15" /></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">库存金额</td>
      <td>{$row.kucunMoney}</td>
      <td>调整为
      <input name="kucunMoney" type="text" id="kucunMoney" size="15" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><input type="submit" name="button" id="button" value="提交" />
      <input type="button" name="button2" id="button2" value="取消" onclick="window.parent.tb_remove()"/>
      <input name="productId" type="hidden" id="productId" value="{$smarty.get.productId}" />
      <input name="kucunId" type="hidden" id="kucunId" value="{$row.kucunId}" /></td>
    </tr>
    <tr>
      <td colspan="3" style="color:#F00">说明:库存调整会自动在其他出库中新增调整记录。</td>
    </tr>
  </table>
</form>
</body>
</html>
