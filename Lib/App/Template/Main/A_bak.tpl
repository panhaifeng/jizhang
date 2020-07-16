{*
次模板为单一界面的通用模板，主要在基础档案中应用或其他单一表的编辑时使用
*}<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
</head>

<script language="javascript" src="Resource/Script/Calendar/WdatePicker.js"></script>
<script language="javascript" src="Resource/bootstrap/bootstrap3.0.3/js/jquery.min.js"></script>

<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">

<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<link rel="stylesheet" href="Resource/bootstrap/bootstrap3.0.3/css/bootstrap.css">

<script src="Resource/bootstrap/bootstrap3.0.3/js/bootstrap.js"></script>
<script src="Resource/bootstrap/bootstrap3.0.3/js/tooltip.js"></script>
{literal}
<style type="text/css">

body{margin-left:5px; margin-top:5px; margin-right: 8px;}
.btns { position:absolute; right:16px; top:1px; height:28px;}
.relative { position:relative;}
.frbtn {position:absolute; top:1px; right:0px; height:28px;z-index:1000;}
.pd5{ padding-left:5px;}
#heji { padding-left:20px; height:20px; line-height:20px; margin-bottom:5px;}
label.error {
  color: #FF0000;
  font-style: normal;
	position:absolute;
	right:-50px;
	top:5px;
}
.lableMain {
  padding-left: 2px !important;
  padding-right: 2px !important;
}
</style>
{/literal}
<body>
<div class='container'>
  <form name="form1" id="form1" class="form-horizontal" action="{url controller=$smarty.get.controller action='save'}" method="post">

  <!-- 主表字段登记区域 -->
  <div class="panel panel-info">
    <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">{$title}</h3></div>
    <div class="panel-body">
      <div class="row">
        {foreach from=$fldMain item=item key=key}
        {include file="Main/"|cat:$item.type|cat:".tpl"}
        {/foreach}
      </div>
    </div>
  </div>

  {if $otherInfoTpl!=''}
  {include file=$otherInfoTpl}
  {/if}
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-8">
        <input class="btn btn-primary" type="submit" id="Submit" name="tijiao" value=" 保 存 ">
      <!-- <input class="btn btn-default" type="submit" id="Submit" name="tijiao" value="保存并新增"> -->
        <input class="btn btn-default" type="reset" id="Reset" name="Reset" value=" 重 置 ">
    </div>
  </div>
  <div style="clear:both;"></div>
  <input type='hidden' name='fromAction' value='{$smarty.get.fromAction|default:"right"}' />
  </form>
</div>
<script language="javascript" src="Resource/bootstrap/bootstrap3.0.3/js/jquery.min.js"></script>
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<script src="Resource/bootstrap/bootstrap3.0.3/js/bootstrap.js"></script>
<script src="Resource/bootstrap/bootstrap3.0.3/js/jeffCombobox.js"></script>
<script language="javascript">
var _rules = {$rules|@json_encode};
{literal}
$(function(){

  $('#btnOrd2pro').click(function(){
    //alert(123);
    var url="?controller=Shengchan_Chanliang&action=Popup";
    var ret = window.showModalDialog(url,window);
    if(!ret) ret=window.returnValue;
    if(!ret) return;
    //alert(ret.productId);
    $(this).siblings('#proCode').val(ret.proCode);//产品编码
    $(this).siblings('#ord2proId').val(ret.id);//从表id
    $('#proName').val(ret.proName);//产品名字
    $('#guige').val(ret.guige);//产品规格
    $('#color').val(ret.color);//颜色
    $('#orderId').val(ret.orderId);//主表订单Id
    $('#productId').val(ret.productId);//产品ID
    return;

  });

   $('#btnRukuYuanliao').click(function(){
    var url="?controller=Shengchan_Yuliao_ruku&action=Popup";
    var ret = window.showModalDialog(url,window);
    if(!ret) ret=window.returnValue;
    if(!ret) return;
    //alert(ret.productId);
    $(this).siblings('#rukuCode').val(ret.rukuCode);//产品编码
    $(this).siblings('#ord2proId').val(ret.id);//从表id
    $('#proName').val(ret.proName);//产品名字
    $('#guige').val(ret.guige);//产品规格
    $('#pihao').val(ret.pihao);//颜色
    $('#rukuId').val(ret.rukuId);//主表订单Id
    $('#ruku2ProId').val(ret.ruku2ProId);//产品ID
	$('#supplierId').val(ret.supplierId);//产品ID
	$('#productId').val(ret.productId);//产品ID
	$('#rukuDate').val(ret.rukuDate);//产品ID
	$('#cnt').val(ret.cnt);//产品ID
	$('#danjia').val(ret.danjia);//产品ID
	$('#_money').val(ret.money);//产品ID
    return;

  });

  //表单验证
  //表单验证应该被封装起来。
  var rules = $.extend({},_rules);
  $('#form1').validate({
    rules:rules,
    submitHandler : function(form){
      var r=true;
      if(typeof(beforeSubmit)=="function") {
        r = beforeSubmit();
      }
      if(!r) return;
      $('[name="Submit"]').attr('disabled',true);
      form.submit();
    }
    // ,debug:true
    ,onfocusout:false
    ,onclick:false
    ,onkeyup:false
  });

  {/literal}
  {if $jsPatch}{include file=$jsPatch}{/if}
  {literal}
});
</script>
{/literal}
</body>
</html>