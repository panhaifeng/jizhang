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
<tr height="26">
	<td width="20%" align="center" bgcolor="#CCCCCC">项目</td>
    <td width="30%" bgcolor="#CCCCCC">项目名称</td>
     <td bgcolor="#CCCCCC">值</td>
	
	</tr>  
    <tr>
      <td align="center" bgcolor="#efefef" >Product_Memo</td>
      <td bgcolor="#efefef">默认产品描述
      <input name="itemName[]" type="hidden" id="itemName[]" value="产品描述"></td>
      <td bgcolor="#efefef">
      <input type="text" id="value[]" name="value[]" value="{$row.Product_Memo|default:'产品描述'}">
      <input type="hidden" id="item[]" name="item[]" value="Product_Memo"></td>
    </tr> 
   <tr>
      <td align="center" bgcolor="#efefef" >Order_Left</td>
      <td bgcolor="#efefef">订单编码前缀
      <input name="itemName[]" type="hidden" id="itemName[]" value="订单编码前缀"></td>
      <td bgcolor="#efefef">
      <input type="text" id="value[]" name="value[]" value="{$row.Order_Left|default:'D'}">
      <input type="hidden" id="item[]" name="item[]" value="Order_Left"></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#efefef" >Baojing_Time</td>
      <td bgcolor="#efefef"><span title="首次行动超过设置时间才行动或未行动的为报警">首次行动超过该时间报警，报警时间设置</span>
      <input name="itemName[]" type="hidden" id="itemName[]" value="报警时间设置（小时）"></td>
      <td bgcolor="#efefef">
      <input type="text" id="value[]" name="value[]" value="{$row.Baojing_Time|default:'48'}">
      小时<input type="hidden" id="item[]" name="item[]" value="Baojing_Time"></td>
    </tr>
     <tr>
      <td align="center" bgcolor="#efefef" >Baojia_Day</td>
      <td bgcolor="#efefef"><span title="提醒需要报价的时间">提醒需要报价的时间</span>
      <input name="itemName[]" type="hidden" id="itemName[]" value="提醒需要报价的时间"></td>
      <td bgcolor="#efefef">
      <input type="text" id="value[]" name="value[]" value="{$row.Baojia_Day|default:'90'}">
      <input type="hidden" id="item[]" name="item[]" value="Baojia_Day">天</td>
    </tr> 
    <tr>
      <td align="center" bgcolor="#efefef" >PingDing_Day</td>
      <td bgcolor="#efefef">提醒需要初次评定客户的时间
      <input name="itemName[]" type="hidden" id="itemName[]" value="提醒需要初次评定客户的时间"></td>
      <td bgcolor="#efefef">
      <input type="text" id="value[]" name="value[]" value="{$row.PingDing_Day|default:'20'}">
      <input type="hidden" id="item[]" name="item[]" value="PingDing_Day">天</td>
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
