<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{literal}
<script language="javascript">

</script>
{/literal}
</head>

<body>
<form id="form1" name="form1" method="post" action="{url controller=$smarty.get.controller action='save'}">
<br />
<div align="left">积分系统的网站路径：</div>
<br />
  <table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
   
  <tr>
    	<td align="right" width="100px" nowrap="nowrap">上传路径：
    	  <input type="hidden" id="itemName[]" name="itemName[]" value="积分系统网站路径">
	</label></td>
        <td align="left"><textarea name="value[]" id="value[]" style="width:300px">{$row.NetPath}</textarea>          <input type="hidden" id="item[]" name="item[]" value="NetPath"></td>
    </tr>
     <tr>
    	<td align="right" width="100px" nowrap="nowrap">本地路径：
    	  <input type="hidden" id="itemName[]" name="itemName[]" value="本地系统网站路径">
	</label></td>
        <td align="left"><textarea name="value[]" id="value[]" style="width:300px">{$row.localNetPath}</textarea>          <input type="hidden" id="item[]" name="item[]" value="localNetPath"></td>
    </tr>
   
 <tr>
     <td></td>
     <td></td>
 </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <input type="submit" name="button" id="button" value="确认" />
       
      </label></td>
    </tr>
  </table>
</form>
</body>
</html>
