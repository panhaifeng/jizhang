<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>


<script language="javascript" src="Resource/Script/Calendar/WdatePicker.js"></script>
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" src="Resource/Script/Common.js"></script>
<script language="javascript" src="Resource/Script/TmisMenu.js"></script>

<link href="Resource/Css/Edit200.css" type="text/css" rel="stylesheet">

</script>
</head>
<body>
<div id='container'>
	<div style="text-align:left">{include file="_ContentNav2.tpl"}</div>

	<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post">
		<input name="id" type="hidden" id="id" value="{$aRow.id}">

		<fieldset style="border:0px;">     
			<legend class="style1"></legend>
			<table  id='table_moreinfo' class="tableHaveBorder" style="width:400px;">
				<tr>
					<td align="right" class="tdTitle">产品名称：</td>
					<td align="left">
					{webcontrol type='Select' name='productId' itemName='产品' textName='proCode' controller='Jichu_Product' selected=$aRow.productId}&nbsp;{$aRow.Products.proName}&nbsp;{$item.Products.guige}
					</td></tr>
				<tr>
					<td align="right" class="tdTitle">产品数量：</td>
					<td align="left"><input name="initCnt" type="text" id="initCnt" value="{$aRow.initCnt}"></td></tr>
				<tr>
					<td align="right" class="tdTitle">备注：</td>
					<td align="left"><input name="memo" type="text" id="memo" value="{$aRow.memo}"></td></tr>
			</table>
		</fieldset>

		<!--底部两操作按钮-->
		<div id="footButton" style="width:400px;">
			<ul style="width:400px;">
				<li><input type="submit" id="Submit" name="Submit" value='保存并增加下一个'  style="width:150px"></li>
				<li><input type="button" id="Back" name="Back" value='取  消' onClick="javascript:window.close()"></li>
			</ul>
		</div>
	</form>
</div>
</body>
</html>