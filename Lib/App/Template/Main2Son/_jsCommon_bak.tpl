{*T1.tpl中需要用到的js代码,注意这里只能写通用性代码，个性化的功能需要另外建立tpl,参考生产计划的编辑模板实现过程*}
<script language="javascript" src="Resource/Script/Calendar/WdatePicker.js"></script>
<script language="javascript" src="Resource/bootstrap/bootstrap3.0.3/js/jquery.min.js"></script>
<script language="javascript" src="Resource/Script/jquery.validate.js"></script><!-- Bootstrap -->
<script src="Resource/bootstrap/bootstrap3.0.3/js/bootstrap.js"></script>
<script src="Resource/bootstrap/bootstrap3.0.3/js/tooltip.js"></script>
<script language="javascript">
var _removeUrl='?controller={$smarty.get.controller}&action=RemoveByAjax';
var _rules = {$rules|@json_encode};
var controller = '{$smarty.get.controller}';
{literal}
$(function(){
  //日历下拉按钮点击后触发calendar;
  $('[id="btnCalendar"]').click(function(){
    var p = $(this).parents('.input-group');
    WdatePicker({el:$('input',p)[0]});
  });
  //删除行,临时写在这里，后期需要用sea.js封装
  $('[id="btnRemove"]').click(function(){
    //利用ajax删除,后期需要利用sea.js进行封装
    var url=_removeUrl;
    var trs = $('.trRow');
    if(trs.length<=1) {
      alert('至少保存一个明细');
      return;
    }

    var tr = $(this).parents('.trRow');
    var id = $('[name="id[]"]',tr).val();
    if(!id) {
      tr.remove();
      return;
    }

    if(!confirm('此删除不可恢复，你确认吗?')) return;
    var param={'id':id};
    $.post(url,param,function(json){
      if(!json.success) {
        alert('出错');
        return;
      }
      tr.remove();
      window.parent.showMsg('删除成功!');
    },'json');
    return;
  });


  //复制行,临时写在这里，后期需要用sea.js封装
  $('#btnAdd').click(function(){
    var rows = $('.trRow');
    var len = rows.length;
    for(var i=0;i<5;i++) {
      var nt = rows.eq(len-1).clone(true);
      $('input,select',nt).val('');
      rows.eq(len-1).after(nt);
    }
    return;
  });

  //输入数量单价自动计算金额
  $('[name="danjia[]"],[name="cnt[]"]','.trRow').change(function(){
    var tr=$(this).parents('.trRow');
    var danjia = $('[name="danjia[]"]',tr);
    var cnt = $('[name="cnt[]"]',tr);
    var money = parseFloat(danjia.val()||0)*parseFloat(cnt.val()||0);
    $('[name="money[]"]',tr).val(money.toFixed(2));
  });

  //输入金额，自动计算单价

  //表单验证
  //表单验证应该被封装起来。
  var rules = $.extend({},_rules);
  /**
  *添加几个常用的验证规则
  */
  //重复验证，默认验证该model对应表数据，指定的某字段是否重复，如果有其他需求需要个性化代码

  $.validator.addMethod("repeat", function(value, element) {
		var url="?controller="+controller+'&action=repeat';
		var param = {field:element.name,fieldValue:value,id:$('#id').val()};
   
    var repeat=true;
    //通过ajax获取值是否已经存在
    $.ajax({
      type: "GET",
      url: url,
      data: param,
      success: function(json){
        repeat = json.success;
      },
      dataType: 'json',
      async: false//同步操作
    });
    return repeat;
	}, "已存在");

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

  ///////////////////////////弹出选择控件
  //临时写在这里，后期需要用sea.js封装
  $('#btnclientName').click(function(){
    var url="?controller=Jichu_Client&action=Popup";
    var ret = window.showModalDialog(url,window);
     // debugger;
    if(!ret) ret=window.returnValue;
    if(!ret) return;
    var g = $(this).parents('.input-group')
    $('#clientId',g).val(ret.id);
    $('#clientName',g).val(ret.compName);

    //触发onSelClient函数
    if(onSelClient) onSelClient(this);
    return;
  });

  //产品选择,临时写在这里，后期需要用sea.js封装
  //产品选择,临时写在这里，后期需要用sea.js封装
  $('[name="btnProduct"]').click(function(){
    var url="?controller=jichu_product&action=popup";
    var ret = window.showModalDialog(url,window);
    if(!ret) ret=window.returnValue;

    if(!ret) return;
    var tr = $(this).parents(".trRow");
    $('[name="proCode"]',tr).val(ret.proCode);
    $('[name="productId[]"]',tr).val(ret.id);
    $('[name="proName[]"]',tr).val(ret.proName);
    $('[name="guige[]"]',tr).val(ret.guige);
    //alert(ret.color);
    $('[name="color[]"]',tr).val(ret.color);
    if(!ret.unit){
      $('[name="unit[]"]',tr).val('吨');
    }
    else{
      $('[name="unit[]"]',tr).val(ret.unit);
    }
    $('[name="cnt[]"]',tr).focus();

    if(onSelProduct) onSelProduct(this);
    return;
  });


  //订单选择
  $('#btnorderName').click(function(){
    var url="?controller=Trade_Order&action=popup";
    var ret = window.showModalDialog(url,window);
    if(!ret) ret=window.returnValue;
    if(!ret) return;
    //控件显示订单号
    var g = $(this).parents('.form-group');
    $('#orderName',g).val(ret.orderCode);
    $('#orderId',g).val(ret.orderId);
    //填充产品信息
    //找到该行对象
    var tr = $("table").find(".trRow").eq(0);
    $('[name="ord2proId[]"]',tr).val(ret.id);
    $('[name="proCode"]',tr).val(ret.proCode);
    $('[name="productId[]"]',tr).val(ret.productId);
    $('[name="proName[]"]',tr).val(ret.proName);
    $('[name="guige[]"]',tr).val(ret.guige);
    $('[name="cnt[]"]',tr).val(ret.cntYaohuo);
    $('[name="pihao[]"]',tr).focus();
    //删除空的行
    //$("[name='productId[]'][value='']",".trRow").parents(".trRow").remove();
    if(onSelOrder) onSelOrder(this,ret);
    return;
  })

  $('[name="btnPlanCode"]').click(function(){
    // alert(1);
    var url="?controller=Shengchan_Plan&action=popup";
    var ret = window.showModalDialog(url,window);
    if(!ret) ret=window.returnValue;
    if(!ret) return;
    //控件显示订单号
    var g = $(this).parents('.input-group');
    // debugger;
    // alert(ret.planCode);alert($('#planCode',g).val());
    $('#planCode',g).val(ret.orderCode);
    $('#planId',g).val(ret.orderId);
    $('#plan2proId',g).val(ret.id);
    //填充产品信息
    //找到该行对象
    // var tr = $("table").find(".trRow").eq(0);
    var tr = $(this).parents('.trRow');
    $('[name="plan2proId[]"]',tr).val(ret.id);
    $('[name="proCode"]',tr).val(ret.proCode);
    $('[name="productId[]"]',tr).val(ret.productId);
    $('[name="proName[]"]',tr).val(ret.proName);
    $('[name="guige[]"]',tr).val(ret.guige);
    $('[name="color[]"]',tr).val(ret.color);
    $('[name="cnt[]"]',tr).val(ret.cntShengchan);
    $('[name="pihao[]"]',tr).focus();
    //删除空的行
    //$("[name='productId[]'][value='']",".trRow").parents(".trRow").remove();
    if(onSelPlan) onSelPlan(this,ret);
    return;
  })

  //订单选择
  // $('#btnPlanCode').click(function(){
  //   var url="?controller=Shengchan_Plan&action=popup";
  //   var ret = window.showModalDialog(url,window);
  //   if(!ret) ret=window.returnValue;
  //   if(!ret) return;
  //   //控件显示订单号
  //   var g = $(this).parents('.form-group');
  //   $('#planCode',g).val(ret.orderCode);
  //   $('#planId',g).val(ret.orderId);
  //   alert(ret.orderCode);alert(ret.orderId);
  //   //填充产品信息
  //   //找到该行对象
  //   var tr = $("table").find(".trRow").eq(0);
  //   $('[name="plan2proId[]"]',tr).val(ret.id);
  //   $('[name="proCode"]',tr).val(ret.proCode);
  //   $('[name="productId[]"]',tr).val(ret.productId);
  //   $('[name="proName[]"]',tr).val(ret.proName);
  //   $('[name="guige[]"]',tr).val(ret.guige);
  //   $('[name="cnt[]"]',tr).val(ret.cntShengchan);
  //   $('[name="pihao[]"]',tr).focus();
  //   //删除空的行
  //   //$("[name='productId[]'][value='']",".trRow").parents(".trRow").remove();
  //   if(onSelPlan) onSelPlan(this,ret);
  //   return;
  // });
  $('[name="btnPop"]').click(function(e){
    var p = $(this).parents('.clsPop');
  	//弹出窗地址
  	var url = $(p).find('#url').val();
  	var ret = window.showModalDialog(url,window);
      if(!ret) ret=window.returnValue;
      if(!ret) return;
  	//选中行后填充textBox和对应的隐藏id
  	$(p).find('#textBox').val(ret.textValue);
  	$(p).find('.hideId').eq(0).val(ret[$(p).find('.hideId').attr('id')]);
  	//如果还需要其他脚本，可以加载方法
      var js_function = $(p).find('#js_function').val();
  	//加载fuction
  	if(js_function!='' && js_function!=undefined){
  		eval('('+js_function+'(ret))');
  	}
  });
});

//选择入库，这个方法名是和在类中指定的js_function名字一致的，
function getShaRuku140513(ret){
	// $('#proName').val(ret.proName);//产品名字
 //  $('#guige').val(ret.guige);//产品规格
  $('#qitaMemo').val(ret.qitaMemo);//颜色
  $('#rukuId').val(ret.ruKuId);//主表订单Id
   // $('#ruku2ProId').val(ret.ruku2ProId);//产品ID
	$('#supplierId').val(ret.supplierId);//产品ID
	$('#productId').val(ret.productId);//产品ID
	$('#rukuDate').val(ret.rukuDate);//产品ID
	$('#cnt').val(ret.cnt);//产品ID
	$('#danjia').val(ret.danjia);//产品ID
	$('#_money').val(ret.money);//产品ID
	// $('#kuweiName').val(ret.kuweiName);//产品ID
	$('#kind').val(ret.kind);//产品ID
	// $('#kuweiId').val(ret.kuwei);//产品ID
	$('#_money').change();
}


//选择出库，这个方法名是和在类中指定的js_function名字一致的，
function getChengpinChuku(ret){
  // $('#proName').val(ret.proName);//产品名字
  // $('#guige').val(ret.guige);//产品规格
  $('#qitaMemo').val(ret.qitaMemo);//颜色
  $('#chukuId').val(ret.chukuId);//主表订单Id
  $('#clientId').val(ret.clientId);//产品ID
  $('#productId').val(ret.productId);//产品ID
  $('#chukuDate').val(ret.chukuDate);//产品ID
  $('#cnt').val(ret.cnt);//产品ID
  $('#danjia').val(ret.danjia);//产品ID
  $('#clientName').val(ret.compName);//产品ID
  $('#money').val(ret.money);//产品ID
  $('#kind').val(ret.kind);//产品ID
  $('#unit').val(ret.unit);//产品ID
  $('#orderId').val(ret.orderId);//产品ID
  $('#ord2proId').val(ret.ord2proId);//产品ID
  $('#kind').val(ret.kind);//产品ID
}
{/literal}
</script>