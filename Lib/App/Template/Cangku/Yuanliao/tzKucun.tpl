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
<form id="form1" name="form1" method="post" action="{url controller=$smarty.get.controller action=SaveTzKucun}">
  <table id="mainTable">
   <tr>
      <td class="title">产品编码：</td>
      <td><input name="proCode" type="text" readonly id="proCode" value="{$aRow.proCode}"/>
      <input name="productId" type="hidden" id="productId" value="{$aRow.productId}" /></td>
    </tr>
    <tr>
        <td class="title">库位:</td>
        <td><input name="kuwei" type="text" readonly id="kuwei" value="{$aRow.kuwei}" /></td>
    </tr>
    <tr>
        <td class="title">批号:</td>
        <td><input name="pihao" type="text" readonly id="pihao" value="{$aRow.pihao}" /></td>
    </tr>
    <tr>
        <td class="title">缸号:</td>
        <td><input name="ganghao" type="text" readonly id="ganghao" value="{$aRow.ganghao}" /></td>
    </tr>
    <tr>
      <td class="title">当前库存：</td>
      <td><input name="cntKucun" type="text"  id="cntKucun" value="{$aRow.cntKucun}" readonly size="10"/>
     </td>
    </tr>
    <tr>
      <td class="title">实际库存：</td>
      <td><input name="cntReal" type="text"  id="cntReal" value="" size="10"/>
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
      <td colspan="2" class="title" style="color:red">注：保存后会产生一笔调账记录，</td>
    </tr>
  </table>
</form>
</body>
</html>
