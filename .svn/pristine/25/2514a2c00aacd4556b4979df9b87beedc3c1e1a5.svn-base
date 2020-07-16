
{*
T1为T的升级版本,主要是将js剥离出来了。
注意:
proKind为产品弹出选择控件的必须参考的元素,特里特个性化需求,出了订单登记界面外其他使用产品弹出选择控件的模板必须制定proKind为hidden控件
*}<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
</head>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="Resource/bootstrap/bootstrap3.0.3/css/bootstrap.css">
{literal}
<style type="text/css">

body{padding-left:5px; padding-top:5px; padding-right: 5px;}
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

.trRow select {width:auto;}
.trRow input {min-width: 80px;}
</style>
{/literal}

<body>
<!--图片上传功能，表单添加新属性enctype="multipart/form-data"，2015-09-11,by liuxin -->
<form name="form1" id="form1" class="form-horizontal" enctype="multipart/form-data" action="{url controller=$smarty.get.controller action=$action_save|default:'save'}" method="post">
<!-- 主表字段登记区域 -->
<div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">{$areaMain.title}</h3></div>
  <div class="panel-body">
    <div class="row">
    {foreach from=$areaMain.fld item=item key=key}
      {assign var="f" value="Lib/App/Template/Main2Son/"|cat:$item.type|cat:".tpl"}
      {if file_exists($f)}
      {include file="Main2Son/"|cat:$item.type|cat:".tpl"}
      {else}
      {$key}:{$item.type}对应的模板文件{$f}不存在
      {/if}
    {/foreach}
    </div>
  </div>
</div>
<div style="overflow:auto; border:1px solid #bce8f1; margin-bottom:10px; max-height:280px;">
  <div style="width:60%;float:left;">
    {if $recheck}
      <button type="button" class="btn btn-primary" id="recheck" style="margin-left: 5px;">重新引入</button>
    {else}
      <button type="button" class="btn btn-primary" id="check" style="margin-left: 5px;">引入</button>
    {/if}
  </div>
  <div class="table-responsive" style="width:{$tbl_son_width|default:"100%"};">
    <table class="table table-condensed table-striped trRowMore" id='table_main' removeUrl='?controller={$smarty.get.controller}&action={$RemoveByAjax|default:RemoveByAjax}' key='id[]'>
      <thead>
        <tr>
        {foreach from=$headSon item=item key=key}
          {if $item.type!='bthidden'}
            {if $item.type=='btBtnRemove'}
              <th>{webcontrol type='btBtnAdd'}</th>
            {else}
            <th style='white-space:nowrap;'>{$item.title}</th>
            {/if}
          {/if}
        {/foreach}
        </tr>
      </thead>
      <tbody>
        {foreach from=$rowsSon item=item1 key=key1}
        <tr class='trRow'>
        {foreach from=$headSon item=item key=key}
          {if $item.type!='bthidden'}
            <td>{webcontrol
            type=$item.type
            itemName=$item.name
            readonly=$item.readonly
            disabled=$item.disabled
            model=$item.model
            options=$item.options
            condition=$item.condition
            optionType=$item.optionType

            url=$item.url
            textFld=$item.textFld
            hiddenFld=$item.hiddenFld
            text=$item1[$key].text

            inTable=$item.inTable|default:true
            value=$item1[$key].value|default:$item.defaultValue
            kind=$item.kind
            checked=$item1[$key].checked }</td>
            {else}
              {webcontrol
              type=$item.type
              value=$item1[$key].value|default:$item.defaultValue
              itemName=$item.name
              readonly=$item.readonly
              disabled=$item.disabled}
          {/if}
        {/foreach}
        </tr>
        {/foreach}
      </tbody>

    </table>
  </div>
</div>

{if $otherInfoTpl!=''}
{include file=$otherInfoTpl}
{/if}
<div class="form-group">
  <div class="col-sm-offset-4 col-sm-8">
      <input class="btn btn-primary" type="submit" id="Submit" name="Submit" value=" 保 存 " onclick="$('#submitValue').val('保存')">
      {*其他一些功能按钮,*}
      {$other_button}
      <input class="btn btn-default" type="reset" id="Reset" name="Reset" value=" 重 置 ">
      <input type='hidden' name='submitValue' id='submitValue' value=''/>
      <input type='hidden' name='fromController' id='fromController' value='{$fromController|default:$smarty.get.controller}'/>
      <input type='hidden' name='fromAction' id='fromAction' value='{$smarty.get.fromAction|default:$smarty.get.action}'/>
  </div>
</div>
<div style="clear:both;"></div>

</form>
<div id="divModel" class="s-isindex-wrap s-model-set-menu menu-top" style="display:none;">
  <div><input type="text" class="supplierText" id="supplierText"/></div>
</div>
{*通用的js代码放在_jsCommon中,主要是一些组件的效果*}
{include file='Main2Son/_jsCommon.tpl'}
{*下面是个性化的js代码,和特殊的业务逻辑挂钩,比如某些模板中自动合计的效果等*}
{if $sonTpl}{include file=$sonTpl}{/if}
{if $sonTpl2}{include file=$sonTpl2}{/if}

<script language="javascript">
{literal}
$(function(){
  //供应商自动完成
  if($.autocomplete){
    $('#supplierText').autocomplete('?controller=jichu_Supplier&action=GetJsonByKey', {
      minChars:1,
      remoteDataType:'json',
      useCache:false,
      sortResults:false,
      onItemSelect:function(v){
        $('#supplierId').val(v.data[0].id);
            $('#divModel').hide();
            $('#btnMore').removeClass('active');
      }
    });
  }

  //切换divModel的可见
  $('#btnMore').click(function(){
        $('#supplierText').val('');
    if($('#divModel').is(':hidden')){
      var showTarget = $(this), target= $('#supplierId');
      var showL = target.offset().left
         ,showT = target.offset().top
         ,showW = target.width()
         ,showH = target.height();
        $('#supplierText').css("left", showL).css("top", showT).css("width",showW).css("height", showH+1);
      $('#divModel').css({'left':showL,'top':showT, 'width':(showW+2), 'height':(showH+2)}).show();
        $(this).addClass('active');
      $('#supplierText').focus();
    } else {
      $('#divModel').hide();
      $(this).removeClass('active');
    }
  });
  //引入
  $('#check').click(function(){
    var jghFromId=$('#jghFromId').val();
    var jiagonghuId=$('#jiagonghuId').val();
    var gongxuId=$('#gongxuId').val();
    var url="?controller="+controller+"&action=ViewChoose&jghFromId="+jghFromId+'&jiagonghuId='+jiagonghuId+'&gongxuId='+gongxuId+'&isRecheck=1';
    var urlTwo = "?controller="+controller+"&action=AjaxFawai";

    var trs = $('.trRow','#table_main');
    // for(var i=0;trs[i];i++) {
    //   var planId = $('[name="planId[]"]',trs[i]);
    //   if(planId.val()!='') continue;
    //   trs.eq(i).remove();
    // }
    var len = $('.trRow','#table_main').length;
    // alert(len);
    var trTpl = trs.eq(len-1).clone(true);
    var parent = trs.eq(0).parent();
    $('input,select',trTpl).val('');
    choose_layer = $.layer({
          type: 2,
          shade: [1],
          fix: true,
          title: '选择发外',
          maxmin: true,
          iframe: {src : url},
          // border:false,
          area: ['1000px' , '500px'],
          close: function(index){//关闭时触发
          },
          //回调函数定义
          callback:function(ret) {
            urlTwo = urlTwo+ret;
            var param={'jghFromId':jghFromId,'jiagonghuId':jiagonghuId,'gongxuId':gongxuId};
            $.getJSON(urlTwo,param,function(json){
              if (!json) {
                alert('未发现计划产量数据！');
                return;
              }

              // for (var i = arrayA.length - 1; i >= 0; i--) {
              //     for (var j = 0; j < json.length; j++) {
              //       if (arrayA[i]['seCode'] == json[j]['seCode']){
              //         if (arrayA[i]['planId'] == json[j]['planId']){

              //         }
              //       }
              //     };
              // };
              for (var i = 0; i < json.length; i++) {
                var num=0;
                $('.trRow','#table_main').each(function(){
                  var tr = $(this); 
                  var seCode = $('[name="seCode[]"]',tr).val();
                  var planId = $('[name="planId[]"]',tr).val();
                  if ((seCode == json[i]['seCode'])&&(planId == json[i]['planId'])) {
                    $('[name="cntJian[]"]',tr).val(json[i]['cntJian']);
                    $('[name="cnt[]"]',tr).val(json[i]['cnt']);
                    $('[name="Madan[]"]',tr).val(json[i].Madan);
                    //退出
                    return;
                  }else{
                    num+=1;
                  }
                });
                if (num == len) {
                  var newTr = trTpl.clone(true);
                  $('[name="planCode[]"]',newTr).val(json[i].planCode);
                  $('[name="proCode[]"]',newTr).val(json[i].proCode);
                  $('[name="proName[]"]',newTr).val(json[i].proName);
                  $('[name="guige[]"]',newTr).val(json[i].guige);
                  $('[name="color[]"]',newTr).val(json[i].color);
                  $('[name="cnt[]"]',newTr).val(json[i].cnt);
                  $('[name="juanhao[]"]',newTr).val(json[i].juanhao);
                  $('[name="seCode[]"]',newTr).val(json[i].seCode);
                  $('[name="plan2hzlId[]"]',newTr).val(json[i].plan2hzlId);
                  $('[name="planId[]"]',newTr).val(json[i].planId);
                  $('[name="ord2proId[]"]',newTr).val(json[i].ord2proId);
                  $('[name="orderId[]"]',newTr).val(json[i].orderId);
                  $('[name="productId[]"]',newTr).val(json[i].productId);
                  $('[name="cntJian[]"]',newTr).val(json[i].cntJian);
                  $('[name="cnt[]"]',newTr).val(json[i].cnt);
                  $('[name="Madan[]"]',newTr).val(json[i].Madan);
                  parent.append(newTr);
                  return;
                }
              };    
            });
        }
    });
    // layer.full(choose_layer,{area: ['1200px','640px'], offset: ['',''],border:['1']} );//弹出全屏
  })
  //重新引入功能.
  $('#recheck').click(function(){
    var jghFromId=$('#jghFromId').val();
    var jiagonghuId=$('#jiagonghuId').val();
    var gongxuId=$('#gongxuId').val();
    var url="?controller="+controller+"&action=ViewChoose&jghFromId="+jghFromId+'&jiagonghuId='+jiagonghuId+'&gongxuId='+gongxuId;
    var urlTwo = "?controller="+controller+"&action=AjaxFawai";

    var trs = $('.trRow','#table_main');
    var trTpl = trs.eq(0).clone(true);
    var parent = trs.eq(0).parent();
    $('input,select',trTpl).val('');
    // var len = $('.trRow','#table_main').length;
    //for(var i=0;trs[i];i++) {
      // var planId = $('[name="planId[]"]',trs[i]);
      // if(planId.val()!='') continue;
      //trs.eq(i).remove();
    //}
    // alert(len);
    choose_layer = $.layer({
          type: 2,
          shade: [1],
          fix: true,
          title: '选择发外',
          maxmin: true,
          iframe: {src : url},
          // border:false,
          area: ['1000px' , '500px'],
          close: function(index){//关闭时触发
          },
          //回调函数定义
          callback:function(ret) {
            if (ret) {
              for(var i=0;trs[i];i++) {
                trs.eq(i).remove();
              }
            }
            urlTwo = urlTwo+ret;
            var param={'jghFromId':jghFromId,'jiagonghuId':jiagonghuId,'gongxuId':gongxuId};
            $.getJSON(urlTwo,param,function(json){
              if (!json) {
                alert('未发现计划产量数据！');
                return;
              }
              for (var i = 0; i < json.length; i++) {
                  var newTr = trTpl.clone(true);
                  $('[name="planCode[]"]',newTr).val(json[i].planCode);
                  $('[name="proCode[]"]',newTr).val(json[i].proCode);
                  $('[name="proName[]"]',newTr).val(json[i].proName);
                  $('[name="guige[]"]',newTr).val(json[i].guige);
                  $('[name="color[]"]',newTr).val(json[i].color);
                  $('[name="cnt[]"]',newTr).val(json[i].cnt);
                  $('[name="juanhao[]"]',newTr).val(json[i].juanhao);
                  $('[name="seCode[]"]',newTr).val(json[i].seCode);
                  $('[name="plan2hzlId[]"]',newTr).val(json[i].plan2hzlId);
                  $('[name="planId[]"]',newTr).val(json[i].planId);
                  $('[name="ord2proId[]"]',newTr).val(json[i].ord2proId);
                  $('[name="orderId[]"]',newTr).val(json[i].orderId);
                  $('[name="productId[]"]',newTr).val(json[i].productId);
                  $('[name="cntJian[]"]',newTr).val(json[i].cntJian);
                  $('[name="cnt[]"]',newTr).val(json[i].cnt);
                  $('[name="Madan[]"]',newTr).val(json[i].Madan);
                  parent.append(newTr);
              };    
            });
        }
    });

  });
});
function tb_remove(){
    layer.close(choose_layer); //执行关闭
}
</script>
{/literal}
</body>
{webcontrol type='LoadJsCss' src="Resource/Script/layer/layer.js"}
</html>