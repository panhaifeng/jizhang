<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{literal}
<script language="javascript">
$(function(){
	$('#form1').submit(function(){
		if($('#cName').val()=='') {
			alert('error');
			return false;
		}
	});
	$('#form1').validate({
		rules:{		
			'compCode':'required',
			'compName':'required',
			'codeAtOrder':'required'
		},
		submitHandler : function(form){
			$('#Submit').attr('disabled',true);
			form.submit();
		}
	});
});
</script>
{/literal}
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{literal}
{/literal}
</head>
<body>
<form action="{url controller=$smarty.get.controller action='save'}" method="post" enctype="multipart/form-data" name="form1" id="form1">
<input name="id" type="hidden" id="id" value="{$aRow.id}" />
<table id="mainTable">
<tr>
	<td class="title">客户编号：</td>
	<td><input name="compCode" type="text" id="compCode" value="{$aRow.compCode}" /><span class="bitian">*</span></td>
	<td rowspan="15" align="left" valign="top" class="title bitian">
    <div style='width:400px; height:400px;border:1px solid #000;' align="center">{if $aRow.zhizaoPic!=''}<img src="{$aRow.zhizaoPic}" width="400" height="400" border="1">{else}无营业执照图片{/if}
    </div>
    </td>
    </tr>
<tr>
  <td class="title">客户名称：</td>
  <td class="bitian"><input name="compName" type="text" id="compName" value="{$aRow.compName}"/><span class="bitian">*</span></td>
  </tr>
<tr>
  <td class="title">合同简称：</td>
  <td class="bitian"><input name="codeAtOrder" type="text" id="codeAtOrder" value="{$aRow.codeAtOrder}" /><span class="bitian">*</span></td>
  </tr>
<tr>
  <td class="title">业务员：</td>
  <td>
  <select name="traderId" id="traderId">
		  {webcontrol type='Traderoptions' model='jichu_employ' selected=$aRow.traderId emptyText='选择业务员'}
		</select>
  
  </td>
  </tr>
<tr>
  <td class="title">电话：</td>
  <td><input name="tel" type="text" id="tel" value="{$aRow.tel}" /></td>
  </tr>
<tr>
  <td class="title">客户助记码：</td>
  <td><input name="zhujiCode" type="text" id="zhujiCode" value="{$aRow.zhujiCode}" /></td>
  </tr>
<tr>
  <td class="title">联系人：</td>
  <td><input name="people" type="text" id="people" value="{$aRow.people}" />
  注：多个联系人请用半角逗号&quot;,&quot;进行分隔</td>
  </tr>
<tr>
  <td class="title">地址：</td>
  <td><input name="address" type="text" id="address" value="{$aRow.address}" /></td>
  </tr>
<tr>
  <td class="title">传真：</td>
  <td><input name="fax" type="text" id="fax" value="{$aRow.fax}" /></td>
  </tr>
<tr>
  <td class="title">手机：</td>
  <td><input name="mobile" type="text" id="mobile" value="{$aRow.mobile}" /></td>
  </tr>
<tr>
  <td class="title">email：</td>
  <td><input name="email" type="text" id="email" value="{$aRow.email}" /></td>
  </tr>
<tr>
  <td class="title">开票资料：</td>
  <td><textarea name="kaipiao" type="text" id="kaipiao">{$aRow.kaipiao}</textarea></td>
  </tr>
<tr>
  <td class="title">营业执照：</td>
  <td><input type="file" name="pic" id="pic"  style="width:100%"></td>
  </tr>
<tr>
  <td class="title">备注：</td>
  <td><textarea name="memo" type="text" id="memo">{$aRow.memo}</textarea></td>
	</tr>
<tr>
  <td valign="top" class="title">是否停止往来：</td>
  <td valign="top"><select name="isStop" id="isStop">
    <option value="0">正在往来</option>
    <option value="1" {if $aRow.isStop==1}selected{/if}>停止往来</option>
    </select></td>
</tr>
</table>

<table id="buttonTable">
<tr>
		<td>
		<!--<input type="submit" id="Submit" name="Submit" value='保存并新增下一个'>-->
		<input type="submit" id="Submit" name="Submit" value='保存'>
		<input type="button" id="Back" name="Back" value='返回' onClick="window.location.href='{url controller=$smarty.get.controller}&action=right'"></td>
	</tr>
</table>
</form>
</body>
</html>
