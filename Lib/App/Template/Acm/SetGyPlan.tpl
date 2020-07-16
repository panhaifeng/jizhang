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
<form id="form1" name="form1" method="post" action="{url controller=$smarty.get.controller action='SaveGyPlan'}">
<br />
<div align="left">关闭开关表示不用做工艺直接做染纱计划，<br>开启则做工艺。</div>
<br />
  <table width="60%" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
     <td width="10%">
     <select name="plan" id="plan">
        <option value="0" {if $row.value==0}selected{/if}>关闭</option>
        <option value="1" {if $row.value==1}selected{/if}>开启</option>
      </select>
      </td>
      <td valign="top"><input type="submit" id="Submit" name="Submit" value='确定'>
      <input type="hidden" name="itemName" id="itemName" value="0为关闭；1为开启,工艺开关"> 
      <input type="hidden" name="item" id="item" value="Plan_Gongyi"> 
      </td>
    </tr>
  </table>
</form>
</body>
</html>
