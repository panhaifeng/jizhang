<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{literal}
<script language="javascript">

function setSel(){
	var sel=document.getElementsByName('sel');
	var jindu=document.getElementById('jindu');
	var shayang=document.getElementById('shayang');
	
			if(sel[1].checked==true){
				jindu.checked=false;
				shayang.checked=false;
			}
			else if(sel[0].checked==true){
				jindu.checked=true;
				shayang.checked=true;
			}
				   
	
}

</script>
{/literal}
</head>

<body>
<form id="form1" name="form1" method="post" action="{url controller=$smarty.get.controller action='savePishMode'}">
<br />
<div align="left">管理模式：</div>
<br />
  <table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td><input name="sel" type="radio" id="radio" value="0" {if $row.pishaMode==0}checked{/if} /></td>
      <td>坯纱基本存放在本厂，用多少送多少(选用坯纱时只需要精确到染纱计划上的纱支)</td>
    </tr>
    <tr>
      <td><input type="radio" name="sel" id="radio" value="1" {if $row.pishaMode==1}checked{/if}/></td>
      <td>坯纱全部存放染厂(选用坯纱时需要具体到染纱计划上的纱支颜色)</td>
    </tr>
    <tr>
    	<td></td>
        <td>初始化是否完成：<select name="pishaInit">
          <option value="0" {if $row.pishaInit==0}selected{/if}>否</option>
          <option value="1" {if $row.pishaInit==1}selected{/if}>是</option>
        </select></td>
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
