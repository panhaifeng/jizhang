<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title|default:"供应商资料编辑"}</title>

<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" src="Resource/Script/Common.js"></script>
<link href="Resource/Css/Edit200.css" type="text/css" rel="stylesheet">
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<script language="javascript" src="Resource/Script/Calendar/WdatePicker.js"></script>
<script language="javascript">
{literal}
$(function(){
	$('#form1').validate({
		rules:{
			cnt:"required number"
		}
	});	  
});

{/literal}
</script>
</head>

<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='saveTuisha'}" method="post">
<input name="id" type="hidden" id="id" value="{$aRow.id}">
<input name="chukuId" type="hidden" id="chukuId" value="{$aRow.chukuId}">
<input type="hidden" name="kind" id="kind" value="{$aRow.kind}">
<input type="hidden" name="chuku2proId" id="chuku2proId" value="{$aRow.chuku2proId}">
<!--
<input type="hidden" name="supplierId" id="supplierId" value="{$aRow.supplierId}">
<input type="hidden" name="kuweiId" id="kuweiId" value="{$aRow.kuweiId}">
<input type="hidden" name="productId" id="productId" value="{$aRow.productId}">
-->
<table  id='table_moreinfo' class="tableHaveBorder" style="width:100%;">
    <tr>
      <td align="right" class="tdTitle">出库编号：</td>
      <td align="left" style="vertical-align:middle"><input name="chukuNum" type="text" id="chukuNum" value="{$aRow.chukuNum}" readonly/></td>
    </tr>
    <tr>
      <td align="right" class="tdTitle">供应商：</td>
      <td align="left"><select name="supplierId2" id="supplierId2" disabled>
          {webcontrol type='TmisOptions' model='Jichu_Supplier' selected=$aRow.supplierId emptyText='选择供应商'}
            </select></td>
    </tr>
      <tr>
    <td align="right" class="tdTitle">出库日期：</td>
    <td align="left"><input name="chukuDate2" type="text" id="chukuDate2" value="{$aRow.chukuDate2}" readonly/></td>
    </tr>
    <tr>
      <td align="right" class="tdTitle">库位：</td>
      <td align="left"><select name="kuweiId" id="kuweiId">
          {webcontrol type='TmisOptions' model='Jichu_Kuwei' selected=$aRow.kuweiId emptyText='选择库位'}
            </select><span style="color:#999">选择需要放入的仓库</span></td>
    </tr>
  <tr>
    <td align="right" class="tdTitle">编号：</td>
    <td align="left"><input name="proCode" type="text" id="proCode" value="{$aRow.proCode}" readonly/></td>
  </tr>
  <tr>
    <td align="right" class="tdTitle">品名：</td>
    <td align="left"><input name="proName" type="text" id="proName" value="{$aRow.proName}" readonly/></td>
    </tr>
  <tr>
    <td align="right" class="tdTitle">规格：</td>
    <td align="left"><input name="guige" type="text" id="guige" value="{$aRow.guige}" readonly/></td>
    </tr>
    <tr>
    <td align="right" class="tdTitle">批号：</td>
    <td align="left"><input name="pihao" type="text" id="pihao" value="{$aRow.pihao}" readonly/></td>
    </tr>
  <tr>
    <td align="right" class="tdTitle">出库数量：</td>
    <td align="left"><input name="cnt2" type="text" id="cnt2" value="{$aRow.cnt2}" readonly/>吨</td>
    </tr>
    <tr>
    <td align="right" class="tdTitle">仓租单价：</td>
    <td align="left"><input name="czDanjia" type="text" id="czDanjia" value="{$aRow.czDanjia}"/></td>
    </tr>
  <tr>
    <td align="right" class="tdTitle">装卸单价：</td>
    <td align="left"><input name="zxDanjia" type="text" id="zxDanjia" value="{$aRow.zxDanjia}"/></td>
    </tr>
  <tr>
    <td align="right" class="tdTitle">退货日期：</td>
    <td align="left"><input name="chukuDate" type="text" id="chukuDate" value="{$aRow.chukuDate|default:$smarty.now|date_format:'%Y-%m-%d'}"onClick="calendar()"/></td>
  </tr>
   <tr>
    <td align="right" class="tdTitle">退货数量：</td>
    <td align="left"><input name="cnt" type="text" id="cnt" value="{$aRow.cnt}"/>吨</td>
  </tr>
  <tr>
    <td align="right" class="tdTitle">备注：</td>
    <td align="left"><input name="memo" type="text" id="memo" value="{$aRow.memo}"/></td>
  </tr>
</table>
<table>
	<tr>
    	<td><input type="submit" id="Submit" name="Submit" value='保存' class="button"></td>
        <td><input type="button" id="Back" name="Back" value='取消' onClick="window.parent.tb_remove()" class="button"></td>
    </tr>
</table>
</form>
</body>
</html>
