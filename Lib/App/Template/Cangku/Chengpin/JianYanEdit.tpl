<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/common.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/direct.js"}
{literal}

<script language="javascript">
var _tbl = null;
var _tr = null;
$(function(){
    // alert(1)
  _tbl = document.getElementById('tableList');
  _tr = _tbl.rows[_tbl.rows.length-1].cloneNode(true);

    $('#form1').validate({
        rules:{
            'length':"required"
        },
        messages:{
            'length':"检验长度必填"
       },
        submitHandler : function(form){
            $('#Submit').attr('disabled',true);
            form.submit();
        }
        ,onfocusout:false
        ,onclick:false
        ,onkeyup:false
        ,showErrors:function(errorMap,errorList){
            //dump(errorMap);
            var t = '';
            for(var i=0;errorList[i];i++) {
                t +=errorList[i].message+"\r";
            }

            if(t!='') {
                alert(t);
            }
        }
    });
    // 设置方向键
    var name=['yPos[]','flawId[]','name[]','len[]'];
    //*******direct(true)表示聚焦到下一个焦点时候，会选中里面的内容,false或没有参数则表示不选中
    direct({
        cellname:name,
        selectedorfocus:true,
        optionfocus:true
    });
    //删除按钮
    $('.btnDel').live('click',function(){
        // alert(1);
        var tr = $(this).parents('.trPro');
        var id = $('[name="sonId[]"]',tr).val();
        var objs = document.getElementsByName('btnDel');
        if(objs.length==1) {
            alert('请至少保留一个疵点明细!');
            return false;
        }
        if(!confirm('确认删除疵点明细吗?')) return false;

        if(!id) tr.remove();
        else {
            var url='?controller=Cangku_Chengpin_Cpjy&action=removeByAjax';
            var param={id:id}
            $.getJSON(url,param,function(json){
                if(!json.success) {
                    alert(json.msg);
                    return false;
                }
                tr.remove();
            });
        }

    });

    //禁止回车键提交
    $('#form1').keydown(function(e){
        if(e.keyCode==13){
            if(e.target.type!='textarea')event.returnValue=false;
        }
    });
});
// 增加疵点的输入项
function addRow(that){
    var t= null;
  var trs = $('.trPro');
  var tr = trs[trs.length-1];
  for(var i=0;i<3;i++) {
    var newTr = $(_tr).clone(true);
    $(tr).after(newTr);

    $('[name="yPos[]"]',newTr).val('');
    $('[name="flawId[]"]',newTr).val('');
    $('[name="name[]"]',newTr).val('');
    $('[name="score[]"]',newTr).val('');
    $('[name="value[]"]',newTr).val('');
  }
}

</script>
<style type="text/css">
    .trPro td{
        white-space: nowrap;
        padding: 1px 3px 1px 3px;
    }
    .readonly{
        background-color: #dedede;
    }
</style>
{/literal}
</head>
<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='SaveCheck'}" method="post">
<fieldset>
<legend>订单信息</legend>
<table id="mainTable">
  <tr>
    <td class="title">订单编号：</td>
    <td><input name="orderCode" type="text" id="orderId" value="{$orderinfo.orderCode}" readonly="true" class="readonly">
    </td>
    <td class="title">检测日期：</td>
    <td><input name="checkTime" type="text" id="chenckTime" value="{$checkinfo.checkTime|default:$smarty.now|date_format:'%Y-%m-%d'}" onClick="calendarUntilNow()"></td>
  </tr>
  <tr>
    <td class="title">生产序号：</td>
    <td><input name="orderId" type="text" id="orderId" value="{$orderinfo.order2Id}" readonly="true" class="readonly">
    </td>
    <td class="title">条码：</td>
    <td><input name="ExpectCode" type="text" id="ExpectCode" value="{$checkinfo.ExpectCode}" readonly="true" class="readonly"></td>
  </tr>
  <tr>
    <td class="title">产品编号：</td>
    <td><input name="fabricID" type="text" id="fabricID" value="{$orderinfo.fabricID}" readonly="true" class="readonly"></td>
    <td class="title">客户：</td>
    <!-- <td><select name="customer" id="customer">
          {webcontrol type='Clientoptions' selected=$orderinfo.customer}
    </select></td> -->
    <td><input name="codeAtOrder" type="text" id="codeAtOrder" value="{$orderinfo.codeAtOrder}"readonly="true" class="readonly">
    </td>
  </tr>
  <tr>
      <td class="title">品名：</td>
     <td><input type="text" name="proName" value="{$orderinfo.proName}" readonly="true" class="readonly"></td>
     <td class="title">规格：</td>
     <td><input type="text" name="guige" value="{$orderinfo.guige}" readonly="true" class="readonly"></td>
  </tr>
  <tr>
      <td class="title">颜色：</td>
      <td><input type="text" name="color" value="{$orderinfo.color}" readonly="true" class="readonly"></td>
      <td class="title">门幅：</td>
      <td><input type="text" name="menfu" value="{$orderinfo.menfu}" readonly="true" class="readonly"></td>
  </tr>
  <tr>
     <td class="title">克重：</td>
      <td><input type="text" name="kezhong" value="{$orderinfo.kezhong}" readonly="true" class="readonly"></td>
      <td colspan="2"></td>
  </tr>
</table>
</fieldset>

<fieldset>
<legend>检验信息</legend>
<table id="mainTable">
  <tr>
    <td class="title">机台号:</td>
    <td>
        <input name="c3" type="text" id="c3" value="{$checkinfo.c3}" >
    </td>
    <td class="title">件号:</td>
    <td>
        <input name="jianhao" type="text" id="jianhao" value="{$checkinfo.jianhao}" readonly="true" class="readonly">
    </td>
    <td class="title">缸号:</td>
    <td>
        <input name="c2" type="text" id="c2" value="{$checkinfo.c2}" >
    </td>
  </tr>
  <tr>
    <td class="title">验布工1:</td>
    <td>
        <input name="userName1" type="text" id="userName1" value="{$checkinfo.userName1}">
    </td>
    <td class="title">验布工2:</td>
    <td>
      <input name="userName2" type="text" id="userName2" value="{$checkinfo.userName2}">
    </td>

    <td class="title">称前重量:</td>
    <td>
        <input name="c1" type="text" id="c1" value="{$checkinfo.c1}">
    </td>
  </tr>

   <tr>
    <td class="title">重量:</td>
    <td>
        <input name="weight" type="text" id="weight" value="{$checkinfo.weight}">
    </td>

    <td class="title">区分:</td>
    <td>
        <input name="c4" type="text" id="c4" value="{$checkinfo.c4}" >
    </td>
    <td class="title"></td>
    <td>
    </td>
  </tr>

  <!-- <tr>
    <td class="title">修布工号:</td>
    <td>
        <input name="xiubuCode" type="text" id="xiubuCode" value="{$checkinfo.xiubuCode}" readonly="true" class="readonly">
    </td>
    <td class="title">修布车号:</td>
    <td>
        <input name="cheCode" type="text" id="cheCode" value="{$checkinfo.cheCode}" readonly="true" class="readonly">
    </td>
  </tr> -->
</table>
</fieldset>

<fieldset>
<legend>疵点信息</legend>
<div style="max-width:100%; max-height:300px;width:100%;overflow-x:auto;padding-bottom:30px; border:1px solid #999;margin-top:10px;">
<table id="tableList" style="width:100%;text-align:center;" cellpadding="0" cellspacing="0">
  <tr style="background-color:#D4E2F4; font-size:12px; height:28px;">
    <td>操作[ <a href='javascript:void(0)' onClick="addRow()">+3行</a> ]
    </td>
    <td>经向位置</td>
    <td>疵点号</td>
    <td>疵点名称</td>
    <td>扣分</td>
    <td>疵点长度</td>
    <!-- <td>产品编号</td> -->
    <!-- <td>疵点扣分</td> -->
    <!-- <td>纬向位置</td> -->
    </tr>
    {foreach from=$cidianInfo item=item}
  <tr class='trPro'>
    <td class="editCol">
      <a href='javascript:void(0)' class="btnDel" name="btnDel" id="btnDel" ><img src='Resource/Image/toolbar/delete.gif' border="0" title='删除行'/></a>
      <!-- <input name="orderId[]" type="hidden" id="orderId[]" value="{$rowset.orderId}">
      <input name="checkId[]" type="hidden" id="checkId[]" value="{$rowset.checkId}"> -->
    </td>
    <td>
      <input name="yStartPosCorrected[]" type="text" id="yStartPosCorrected[]" value="{$item.yStartPosCorrected}">
      <input name="sonId[]" type="hidden" id="sonId[]" value="{$item.id}">
    </td>
    <td>
    <input name="flawId[]" type="text" id="flawId[]" value="{$item.flawId}">
    </td>
    <td>
    <input name="name[]" type="text" id="name[]" value="{$item.name}">
    </td>
    <td>
    <input name="score[]" type="text" id="score[]" value="{$item.score}">
    </td>
    <td>
    <input name="value[]" type="text" id="value[]" value="{$item.value}">
    </td>
    </tr>
    {/foreach}
  </table>
  </div>

<div id="footer">
<table id="buttonTable" align="center">
<tr>
    <td>
    <input name="orderId" type="hidden" id="orderId" value="{$orderinfo.orderId}">
    <input name="id" type="hidden" id="id" value="{$checkinfo.id}">
        <input name="checkId" type="hidden" id="checkId" value="{$checkinfo.checkId}">
        <input name="fromAction" type="hidden" id="fromAction" value="{$smarty.get.fromAction}">
        <input type="Submit" id="Submit" name="Submit" value='确定'>
      <input type="button" id="Back" name="Back" value='返回' onClick="window.location.href='{url controller =$smarty.get.controller action=$smarty.get.fromAction}'">
      </td>
    </tr>
</table>
</div>
</fieldset>
</form>
<div id='divError' name='divError' style="color:red;">
</div>
</body>
</html>
