{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<html>
<head>
{literal}
<script language="javascript">
$(function(){
    $('#form1').validate({
        rules:{
            'ranchangId':'required',
            'realKucun':'required'
        }
        ,submitHandler : function(form){
            var o=document.getElementsByName('Submit');
            for(var i=0;i<o.length;i++){
                var t=o[i];
                t.disabled=true;
            }
            form.submit();
        }
    });
    $('#realKucun').focus();
});
</script>
{/literal}
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>库存调整</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="{url controller=$smarty.get.controller action=SaveDiaoBo}">
  <table id="mainTable">
   <tr>
      <td class="title">产品编码：</td>
      <td><input name="proCode" type="text" readonly id="proCode" value="{$aRow.proCode}"/>
      <input name="productId" type="hidden" id="productId" value="{$aRow.productId}" />
      <input name="currKuwei" type="hidden" id="currKuwei" value="{$aRow.kuwei}" />
      </td>
    </tr>
    <tr>
        <td class="title">库位:</td>
        <td>
          <select name="kuwei" id="kuwei">
          {foreach from=$kuwei item=item key=k}
            <option value='{$item.id}' >{$item.kuweiName}</option>
          {/foreach}
          </select>
        </td>
    </tr>
    <tr>
        <td class="title">品名:</td>
        <td><input name="proName" type="text" readonly id="proName" value="{$proDetail.proName}" /></td>
    </tr>
    <tr>
        <td class="title">规格:</td>
        <td><input name="guige" type="text" readonly id="guige" value="{$proDetail.guige}" /></td>
    </tr>
    {if !$hideMk}
    <tr>
        <td class="title">门幅:</td>
        <td><input name="menfu" type="text" readonly id="menfu" value="{$proDetail.menfu}" /></td>
    </tr>
    <tr>
        <td class="title">克重:</td>
        <td><input name="kezhong" type="text" readonly id="kezhong" value="{$proDetail.kezhong}" /></td>
    </tr>
    {/if}
    <tr>
        <td class="title">颜色:</td>
        <td><input name="color" type="text" readonly id="color" value="{$proDetail.color}" /></td>
    </tr>
    <tr>
        <td class="title">缸号(批号):</td>
        <td><input name="ganghao" type="text" readonly id="ganghao" value="{$aRow.ganghao}" /></td>
    </tr>
    <tr>
      <td class="title">当前库存：</td>
      <td><input name="cntKucun" type="text"  id="cntKucun" value="{$aRow.cntKucun}" readonly size="10"/>
     </td>
    </tr>
    <tr>
      <td class="title">调拨库存：</td>
      <td><input name="cntDb" type="text"  id="cntDb" value="" size="10"/>
     </td>
    </tr>
    <tr>
      <td class="title">调拨件数：</td>
      <td><input name="cntJianDb" type="text"  id="cntJianDb" value="" size="10"/>
     </td>
    </tr>
    <tr>
      <td class="title">备注：</td>
      <td><input type="text" id="memo" name="memo" value="" />
      </td>
    </tr>
    <tr>
      <td class="title">&nbsp;</td>
      <td><input type="submit" name="Submit" id="Submit" value=" 保存 " /></td>
    </tr>
    <tr>
      <td colspan="2" class="title" style="color:red">注：保存后会产生一笔出库记录和一笔入库记录</td>
    </tr>
  </table>
</form>
</body>
</html>
