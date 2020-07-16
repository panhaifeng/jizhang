<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{literal}
<script language="javascript">
$(function(){
	$('#form1').validate({
		rules:{		
			'employCode':'required',
			'employName':'required',
			'DepId':'required'
		},
		submitHandler : function(form){
			$('#Submit').attr('disabled',true);
			form.submit();
		}
	});
	getFeilei();
});
function getFeilei(){
	var gz=document.getElementById('gongzhong');
	var fenlei=document.getElementById('fenlei');
	if(gz.value=='修布'){
		fenlei.style.display='';
	}
	else{
		fenlei.style.display='none';	
		fenlei.value='&nbsp;';
	}
}

</script>
{/literal}
</head>
<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post">
<input name="id" type="hidden" id="id" value="{$aRow.id}">
<table id="mainTable">
<tr>
	<td class="title">员工代码：</td>
	<td><input name="employCode" type="text" id="employCode" value="{$aRow.employCode}" /><input name="employCode1" type="hidden" id="employCode1" value="{$aRow.employCode}" /><span class="bitian">*</span></td>
	</tr>
<tr>
<td class="title">员工姓名：</td>
<td><input name="employName" type="text" id="employName" value="{$aRow.employName}" /><input name="employName1" type="hidden" id="employName1" value="{$aRow.employName}" /><span class="bitian">*</span></td>
</tr>
<tr>
  <td class="title">合同简码：</td>
  <td><input name="codeAtEmploy" type="text" id="codeAtEmploy" value="{$aRow.codeAtEmploy}" /></td>
</tr>

<tr>
  <td class="title">性别：</td>
  <td><input style="width:20px" type="radio" name="sex" value="0" {if $aRow.sex==0} checked {/if}>
      男
        <input style="width:20px" type="radio" name="sex" value="1" {if $aRow.sex==1} checked{/if}>
      女</td>
  </tr>
<tr>
  <td class="title">部门：</td>
	<td><select name='DepId'>		
	{webcontrol type='TmisOptions' model='Jichu_Department' selected=$aRow.depId}
	</select></td>
	</tr>
    
     <td class="title">工种：</td>
	<td><select name='gongzhong' onChange="getFeilei()">
	  <option value=''>请选择</option>
	  <option value="穿综" {if $aRow.gongzhong=='穿综'}selected{/if}>穿综</option>
	  <option value="机修" {if $aRow.gongzhong=='机修'}selected{/if}>机修</option>
	  <option value="机修小工" {if $aRow.gongzhong=='机修小工'}selected{/if}>机修小工</option>
	  <option value="修布" {if $aRow.gongzhong=='修布'}selected{/if}>修布</option>
	  <option value="挡车" {if $aRow.gongzhong=='挡车'}selected{/if}>挡车</option>
	  <option value="卷筒" {if $aRow.gongzhong=='卷筒'}selected{/if}>卷筒</option>		
		
	</select>
    <select name="fenlei">
      <option value="修布" {if $aRow.fenlei=='修布'}selected{/if}>修布</option>
      <option value="开剪" {if $aRow.fenlei=='开剪'}selected{/if}>开剪</option>
      <option value="复布" {if $aRow.fenlei=='复布'}selected{/if}>复布</option>
    </select>
    
    </td>
	</tr>
    <tr>
  <td class="title">类型：</td>
  <td><select name="type" id="type">
    <option value="正式" {if $aRow.type=='正式'}selected{/if}>正式</option>
    <option value="试用" {if $aRow.type=='试用'}selected{/if}>试用</option>
    <option value="临时" {if $aRow.type=='临时'}selected{/if}>临时</option>
    <option value="离职" {if $aRow.type=='离职'}selected{/if}>离职</option>
    </select></td>
</tr>
<tr>
        <td class="title">手机：</td>
        <td><input name="mobile" type="text" id="mobile" value="{$aRow.mobile}" /></td>
      </tr>
<tr>
  <td class="title">地址：</td>
  <td><input name="address" type="text" id="address" value="{$aRow.address}" /></td>
</tr>
    <tr>		  
	<td class="title">入职日期：</td>
	<td><input name="dateEnter" type="text" id="dateEnter" value="{if $aRow.dateEnter!='0000-00-00'}{$aRow.dateEnter}{/if}" onClick="calendar()" /></td></tr>
	<tr>		  
	<td class="title">离职日期：</td>
	<td><input name="dateLeave" type="text" id="dateLeave" value="{if $aRow.dateLeave!='0000-00-00'}{$aRow.dateLeave}{/if}" onClick="calendar()"/></td>
	</tr>
	<tr>
	  <td class="title">身份证号：</td>
	  <td><input name="shenfenNo" type="text" id="shenfenNo" value="{$aRow.shenfenNo}"/></td>
    </tr>
	<tr>
	  <td class="title">用工合同号：</td>
	  <td><input name="hetongCode" type="text" id="hetongCode" value="{$aRow.hetongCode}"/></td>
    </tr>
</table>

<table id="buttonTable">
<tr>
		<td>
		<!--<input type="submit" id="Submit" name="Submit" value='保存并新增下一个'>-->
		<input type="submit" id="Submit" name="Submit" value='保存'>
		<input type="button" id="Back" name="Back" value='返回' onClick="window.location.href='{url controller=$smarty.get.controller}&action=right'">
		</td>
	</tr>
</table>
</form>
</body>
</html>
