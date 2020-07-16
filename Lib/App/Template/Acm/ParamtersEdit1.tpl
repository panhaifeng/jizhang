<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{literal}
<script language="javascript">
$(function(){
	$('#form1').validate({
		rules:{		
			'item':'required',
			'itemName':'required',
			'value':'required'
		},
		submitHandler : function(form){
			$('#Submit').attr('disabled',true);
			form.submit();
		}
	});
});
</script>
{/literal}
</head>
<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post" onSubmit="return CheckForm(this)">
<input name="id" type="hidden" id="id" value="{$aRow.id}" />
<table border="0" cellpadding="1" cellspacing="1" width="100%" >
<tr>
	<td align="center" bgcolor="#CCCCCC">项目</td>
    <td bgcolor="#CCCCCC">项目名称</td>
     <td bgcolor="#CCCCCC">值</td>
	
	</tr>
   
<tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">BCJGCZ</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">本厂剑杆穿综单价
	  <input type="hidden" id="itemName[]" name="itemName[]" value="本厂剑杆穿综单价">
	</label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.BCJGCZ}"/><input type="hidden" id="item[]" name="item[]" value="BCJGCZ"></td>
	</tr>  
    
    <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">BCPQCZ</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">本厂喷气穿综单价
	  <input type="hidden" id="itemName[]" name="itemName[]" value="本厂喷气穿综单价">
	</label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.BCPQCZ}"/><input type="hidden" id="item[]" name="item[]" value="BCPQCZ"></td>
	</tr> 
    
    <!--<tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">BCKJ</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">本厂修布开剪单价
	  <input type="hidden" id="itemName[]" name="itemName[]" value="本厂修布开剪单价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.BCKJ}"/><input type="hidden" id="item[]" name="item[]" value="BCKJ"></td>
	</tr>
    <tr>
      <td align="center" bgcolor="#efefef" ><label id="item">BCFB</label></td>
      <td bgcolor="#efefef"><label id="itemName">本厂修布复布单价
          <input type="hidden" id="itemName[]" name="itemName[]" value="本厂修布复布单价">
      </label></td>
      <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.BCFB}"/>
      <input type="hidden" id="item[]" name="item[]" value="BCFB"></td>
    </tr> -->
     <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">BCJG</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">本厂箭杆每纬单价<input type="hidden" id="itemName[]" name="itemName[]" value="本厂箭杆每纬单价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.BCJG}"/><input type="hidden" id="item[]" name="item[]" value="BCJG"></td>
	</tr>  
     <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">BCPQ</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">本厂喷气每纬单价<input type="hidden" id="itemName[]" name="itemName[]" value="本厂喷气每纬单价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.BCPQ}"/><input type="hidden" id="item[]" name="item[]" value="BCPQ"></td>
	</tr>  
     <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">WJGJG</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">外加工箭杆每纬单价<input type="hidden" id="itemName[]" name="itemName[]" value="外加工箭杆每纬单价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.WJGJG}"/><input type="hidden" id="item[]" name="item[]" value="WJGJG"></td>
	</tr>  
     <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">WJGPQ</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">外加工喷气每纬单价<input type="hidden" id="itemName[]" name="itemName[]" value="外加工喷气每位纬单价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.WJGPQ}"/><input type="hidden" id="item[]" name="item[]" value="WJGPQ"></td>
	</tr>  
    <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">xiubuJgBaoyue</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">修布剑杆单车包月价<input type="hidden" id="itemName[]" name="itemName[]" value="修布剑杆单车包月价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.xiubuJgBaoyue}"/><input type="hidden" id="item[]" name="item[]" value="xiubuJgBaoyue"></td>
	</tr>  
     <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">xiubuPqBaoyue</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">修布喷气单车包月价<input type="hidden" id="itemName[]" name="itemName[]" value="修布喷气单车包月价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.xiubuPqBaoyue}"/><input type="hidden" id="item[]" name="item[]" value="xiubuPqBaoyue"></td>
	</tr>   
    <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">xiubuDanjia</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">修布产量单价<input type="hidden" id="itemName[]" name="itemName[]" value="修布产量单价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.xiubuDanjia}"/><input type="hidden" id="item[]" name="item[]" value="xiubuDanjia"></td>
	</tr>  
     <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">xiubufxDanjia</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">修布返修产量单价<input type="hidden" id="itemName[]" name="itemName[]" value="修布返修产量单价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.xiubufxDanjia}"/><input type="hidden" id="item[]" name="item[]" value="xiubufxDanjia"></td>
	</tr> 
     <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">fubuDanjia</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">复布产量单价<input type="hidden" id="itemName[]" name="itemName[]" value="复布产量单价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.fubuDanjia}"/><input type="hidden" id="item[]" name="item[]" value="fubuDanjia"></td>
	</tr>   
    <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">kaijianDanjia</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">开剪产量单价<input type="hidden" id="itemName[]" name="itemName[]" value="开剪产量单价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.kaijianDanjia}"/><input type="hidden" id="item[]" name="item[]" value="kaijianDanjia"></td>
	</tr>  
     <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">kaijianFangongDanjia</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">返工布开剪产量单价<input type="hidden" id="itemName[]" name="itemName[]" value="返工布开剪产量单价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.kaijianFangongDanjia}"/><input type="hidden" id="item[]" name="item[]" value="kaijianFangongDanjia"></td>
	</tr>   
    <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">juantongDanjia</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">卷筒产量单价<input type="hidden" id="itemName[]" name="itemName[]" value="卷筒产量单价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.juantongDanjia}"/><input type="hidden" id="item[]" name="item[]" value="juantongDanjia"></td>
	</tr>   
    
    <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">DWJGPQ</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">对外加工喷气每纬单价<input type="hidden" id="itemName[]" name="itemName[]" value="对外加工喷气每纬单价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.DWJGPQ}"/><input type="hidden" id="item[]" name="item[]" value="DWJGPQ"></td>
	</tr>   
    <tr>
	<td width="20%" align="center" bgcolor="#efefef" ><label id="item">DWJGJG</label></td>
	<td width="30%" bgcolor="#efefef"><label id="itemName">对外加工剑杆每纬单价<input type="hidden" id="itemName[]" name="itemName[]" value="对外加工剑杆每纬单价"></label></td>
    <td bgcolor="#efefef"><input name="value[]" type="text" id="value[]" value="{$row.DWJGJG}"/><input type="hidden" id="item[]" name="item[]" value="DWJGJG"></td>
	</tr>   
    
    
    
</table>

<table id="buttonTable">
<tr>
		<td>
		<input type="submit" id="Submit" name="Submit" value='保存'>
		</td>
	</tr>
</table>
</form>
</body>
</html>
