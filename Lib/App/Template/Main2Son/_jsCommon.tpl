<!-- _jsCommon.tpl -->
{*T1.tpl中需要用到的js代码,注意这里只能写通用性代码，个性化的功能需要另外建立tpl,参考生产计划的编辑模板实现过程*}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap3.0.3/js/jquery.min.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jqueryClone.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/common.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/direct.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap3.0.3/js/bootstrap.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap3.0.3/js/tooltip.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap3.0.3/js/jeffCombobox.js"}
<script type="text/javascript">
var controller = '{$smarty.get.controller}';
var _rules = {$rules|@json_encode};
var tblIds = [];//缓存，记录需要添加行，删除行的table id
var onSelect = null;
{literal}
$(function(){
  //日历下拉按钮点击后触发calendar;
  $('[id="btnCalendar"]').click(function(){
    var p = $(this).parents('.input-group');
    WdatePicker({el:$('input',p)[0]});
  });

  //需要新增行，删除行的表id
  $('.trRowMore').each(function(){
    tblIds.push('#'+$(this).attr('id'));
  });

  /*
  *新增行，删除行方法，复制行到缓存，用于新增的时候用
  */
  for(var i=0;tblIds[i];i++){

    //删除行,临时写在这里，后期需要用sea.js封装
    $('[id="btnRemove"]',tblIds[i]).live('click',function(){
      var tbl=$(this).parents('.trRowMore');
      var id='#'+$(tbl).attr('id');
      var removeUrl=$(tbl).attr('removeUrl');
      //alert(removeUrl);
      var key=$(tbl).attr('key');
      fnRemove(id,this,removeUrl,key);
    });

    //复制行,临时写在这里，后期需要用sea.js封装
    $('#btnAdd',tblIds[i]).click(function(){

      var tbl=$(this).parents('.trRowMore').attr('id');

      fnAdd('#'+tbl);
    });

    //复制当前行
    $('#table_main').on('click','[id="btnCopy"]',function(){
      fnCopy('#table_main',this);
    });


  }

  //输入数量单价自动计算金额
  // alert(1);
  $('[name="danjia[]"],[name="cnt[]"],[name="cntOrg[]"],[name="cntYaohuo[]"]','.trRow').change(function(){
    var tr=$(this).parents('.trRow');
    var cntOrg=$('[name="cntOrg[]"]',tr);
    if(cntOrg.length==0){
      var danjia = $('[name="danjia[]"]',tr);
      var cnt = $('[name="cnt[]"]',tr);
      if(cnt.length==0) cnt= $('[name="cntYaohuo[]"]',tr);
      // alert(danjia);alert(cnt);
      var money = parseFloat(danjia.val()||0)*parseFloat(cnt.val()||0);
      $('[name="money[]"]',tr).val(money.toFixed(2));
    }else{
      var danjia = $('[name="danjia[]"]',tr);
       var money = parseFloat(danjia.val()||0)*parseFloat(cntOrg.val()||0);
       $('[name="money[]"]',tr).val(money.toFixed(2));
    }



  });

  //当单位选择的是公斤的时候，把数量 复制给 折合公斤数
  $('[name="cntOrg[]"],[name="unit[]"]','.trRow').change(function(){
    var tr=$(this).parents('.trRow');
    var cntOrg = $('[name="cntOrg[]"]',tr);
    var select=$('[name="unit[]"]',tr).val();
    if(select=="公斤" && cntOrg.val()>0){
      $('[name="cnt[]"]',tr).val(cntOrg.val());
    }else{
      $('[name="cnt[]"]',tr).val('');
    }
  });

  //输入金额，自动计算单价

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

  ///////////////////////////弹出选择控件
  //临时写在这里，后期需要用sea.js封装
  $('#btnclientName').click(function(){
      //重写onSelect接口后再弹出
    var _this = this;
    onSelect = function(ret) {
      $(_this).siblings('#clientId').val(ret.id);
      $(_this).siblings('#clientName').val(ret.compName);
      return;
    }
    var url="?controller=Jichu_Client&action=Popup";
   var ret = window.open(url,'newwindow','height=500,width=1000,top=200,left=400');
    // var ret = window.showModalDialog(url,window);
    // if(!ret) ret=window.returnValue;
    // if(!ret) return;
    //触发onSelClient函数
    if(onSelect) onSelect(this);
    return;
  });

  /**
  * 添加5行方法，适应于多个table
  */
  function fnAdd(tblId) {
    var rows = $('.trRow',tblId);
    var len = rows.length;

    for(var i=0;i<5;i++) {
      var nt = rows.eq(len-1).clone(true);
      $('input,select',nt).val('');
      $('input[type="radio"],input[type="checkbox"]',nt).attr('checked',false);

       //加载新增后运行的代码
      if(typeof(beforeAdd) == 'function'){
        beforeAdd(nt,tblId);
      }
      //拼接
      rows.eq(len-1).after(nt);
    }

    return;
  }

  /**
  * 复制当前行，适应于多个table
  */
  function fnCopy(tblId,obj) {
    var tbl=tblId.substr(1);
    var _tbl = document.getElementById(tbl);
    var pos =-1;
    pos=$('[name="'+obj.name+'"]').index(obj);
    // alert(pos);
    if(pos==-1) return false;

    var tr = _tbl.rows[pos+1];
    var newTr = $(tr).clone()[0];//tr.cloneNode(true);
    // 去掉新行中的id[]的值
    $('input[name="id[]"]',newTr).val('');
    if(_tbl.rows[pos+2])
      tr.parentNode.insertBefore(newTr,_tbl.rows[pos+2]);
    else tr.parentNode.appendChild(newTr);

  }
  /**
  * 删除行方法，适应于多个table
  */
  function fnRemove(tblId,obj,url,idname) {
    //利用ajax删除,后期需要利用sea.js进行封装
    var url=url;
    var trs = $('.trRow',tblId);
    // alert(trs.length);
    if(trs.length<=1) {
      alert('至少保存一个明细');
      return;
    }

    var tr = $(obj).parents('.trRow');
    var id = $('[name="'+idname+'"]',tr).val();
    if(!id) {
      tr.remove();
      return;
    }

    if(!confirm('此删除不可恢复，你确认吗?')) return;
    var param={'id':id};
    $.post(url,param,function(json){
      if(!json.success) {
        if(json.msg){
           alert(json.msg);
        }else{
           alert('出错');
        }
        return;
      }
      tr.remove();
    },'json');
    return;
  }

  //产品选择,临时写在这里，后期需要用sea.js封装
  $('[name="btnProduct"]').click(function(){
    //重写onSelect接口后再弹出
    var _this = this;
    onSelect = function(ret) {
      var tr = $(_this).parents(".trRow");
      $('[name="proCode"]',tr).val(ret.proCode);
      $('[name="productId[]"]',tr).val(ret.id);
      $('[name="proName[]"]',tr).val(ret.proName);
      $('[name="guige[]"]',tr).val(ret.guige);
      $('[name="chengfen[]"]',tr).val(ret.chengFen);
      // alert(ret.guige);
      $('[name="proName[]"]',tr).attr("title",ret.proName);
      //alert(ret.color);
      $('[name="color[]"]',tr).val(ret.color);
      if(!ret.unit){
        $('[name="unit[]"]',tr).val('公斤');
      }
      else{
        $('[name="unit[]"]',tr).val(ret.unit);
      }
      $('[name="cnt[]"]',tr).focus();
      return;
    }
    var url="?controller=jichu_product&action=popup2";
    var ret = window.open(url,'window','height=600,width=1200,top=150,left=300');
  });

  //订单选择
  $('#btnorderName').click(function(){
    var url="?controller=Trade_Order&action=popup";
       //var ret = window.open(url,'newwindow','height=500,width=1000,top=200,left=400');
    var ret = window.showModalDialog(url,window);
    if(!ret) ret=window.returnValue;
    if(!ret) return;
    //控件显示订单号
    var g = $(this).parents('.form-group');
    $('#orderName',g).val(ret.orderCode);
    $('#orderId',g).val(ret.orderId);
    // $('#color',g).val(ret.color);
    //填充产品信息
    //找到该行对象
    //删除空的行
    //$("[name='productId[]'][value='']",".trRow").parents(".trRow").remove();
    if(onSelOrder) onSelOrder(this,ret);
    return;
  })

  // //订单选择
  // $('[name="btnPlanCode"]').click(function(){
  //   var url="?controller=Shengchan_Plan&action=popup";
  //      var ret = window.open(url,'newwindow','height=500,width=1000,top=200,left=400');
  //   if(!ret) ret=window.returnValue;
  //   if(!ret) return;
  //   //控件显示订单号
  //   var g = $(this).parents('.input-group');
  //   // debugger;
  //   // alert(ret.planCode);alert($('#planCode',g).val());
  //   $('#planCode',g).val(ret.planCode);
  //   // $('#planId',g).val(ret.planId);
  //   $('#plan2proId',g).val(ret.id);
  //   //填充产品信息
  //   //找到该行对象
  //   //删除空的行
  //   if(onSelPlan) onSelPlan(this,ret);
  //   return;
  // })

  //通用的弹出选择控件的事件定义,
  //里面暴露一个onSelect
  //另有
  $('[name="btnPop"]').click(function(e){
//    debugger;
    var p = $(this).parents('.clsPop');
    //弹出窗地址
    var url = $(this).attr('url');
    var textFld= $(this).attr('textFld');
    var hiddenFld= $(this).attr('hiddenFld');
    var id = $('.hideId',p).attr('id');

    onSelect = function(ret) {
	  //选中行后填充textBox和对应的隐藏id
	  $('#textBox',p).val(ret[textFld]);
	  $('.hideId',p).val(ret[hiddenFld]);

	  //执行回调函数,就是触发自定义事件:onSel
	  if(!$("[name='"+id+"']").data("events") || !$("[name='"+id+"']").data("events")['onSel']) {
		  //alert('未发现对popup控件 '+id+ ' 的回调函数进行定义,您可能需要在sonTpl中用bind进行事件绑定:\n$("[name=\'productId[]\']").bind(\'onSel\',function(event,ret){...})');
		  // debugger;
	      alert('未发现对popup控件 '+id+ ' 的回调函数进行定义,您可能需要在sonTpl中用bind进行事件绑定:\n\$(\'[name="'+id+'"]\').bind(\'onSel\',function(event,ret){...})');
	      return;
	   }

      $('.hideId',p).trigger('onSel',[ret]);
      return;
    }

    //打开窗口之前处理url地址
    if($("[name='"+id+"']").data("events") && $("[name='"+id+"']").data("events")['beforeOpen']){
      url=$('.hideId',p).triggerHandler('beforeOpen',[url]);
      if(url==false)return;
    }

    window.open(url,'newwindow','height=500,width=1000,top=200,left=400');

  });

  //combobox的选中效果
  $('.jeffCombobox li').click(function(){
    var p = $(this).parents('.input-group');
    $('input',p).val($(this).attr('v'));
  });

});
{/literal}
</script>