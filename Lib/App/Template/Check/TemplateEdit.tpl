<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<script language="javascript" src="Resource/Script/autocomplete/autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="Resource/Css/clientAuto.css" />
<link href="Resource/Script/autocomplete/autocomplete.css" type="text/css" rel="stylesheet" />
{literal}
<script language="javascript">
$(function(){
    $('#form1').validate({
        rules:{
            'type':'required',
            'file':'required'
        },
        submitHandler : function(form){
            var type = $('#type').val();
            var relateClientId = $('#relateClientId').val();
            var relateOrderId = $('#relateOrderId').val();
            if(type=='client'){
              if(relateClientId==''){
                alert('客户必选！');return false;
              }
            }else if(type=='order'){
              if(relateOrderId==''){
                alert('订单必选！');return false;
              }
            }
            $('#Submit').attr('disabled',true);
            form.submit();
        }
    });
    // 客户关键字选择
    $('#clientText').autocomplete('?controller=Jichu_Client&action=GetJsonByKey', {
        minChars:1,
        remoteDataType:'json',
        useCache:false,
        sortResults:false,
        onItemSelect:function(v){
          $('#relateClientId').val(v.data[0].id);
          $('#divModel').hide();
          $('#btnMore').removeClass('active');
        }
    });
    //切换divModel的可见
    $('#btnMore').click(function(){
      $('#clientText').val('');
      if($('#divModel').is(':hidden')){
        var showTarget = $(this), target= $('#relateClientId');
        var showL = target.offset().left
           ,showT = target.offset().top
           ,showW = target.width()
           ,showH = target.height();
          $('#clientText').css("left", showL).css("top", showT).css("width",showW).css("height", showH+1);
          $('#divModel').css({'left':showL,'top':showT, 'width':(showW+2), 'height':(showH+2)}).show();
          $(this).addClass('active');
          $('#clientText').focus();
      } else {
        $('#divModel').hide();
        $(this).removeClass('active');
      }
    });

    // 订单关键字选择
    $('#orderText').autocomplete('?controller=Check_Template&action=GetOrderByKey', {
        minChars:1,
        remoteDataType:'json',
        useCache:false,
        sortResults:false,
        onItemSelect:function(v){
          $('#relateOrderId').val(v.data[0].id);
          $('#divModelOrd').hide();
          $('#btnMoreOrd').removeClass('active');
        }
    });
    //切换divModelOrd的可见
    $('#btnMoreOrd').click(function(){
      $('#orderText').val('');
      if($('#divModelOrd').is(':hidden')){
        var showTarget = $(this), target= $('#relateOrderId');
        var showL = target.offset().left
           ,showT = target.offset().top
           ,showW = target.width()
           ,showH = target.height();
          $('#orderText').css("left", showL).css("top", showT).css("width",showW).css("height", showH+1);
          $('#divModelOrd').css({'left':showL,'top':showT, 'width':(showW+2), 'height':(showH+2)}).show();
          $(this).addClass('active');
          $('#orderText').focus();
      } else {
        $('#divModelOrd').hide();
        $(this).removeClass('active');
      }
    });

    // 模板类型选择改变后，触发关联客户或关联订单显示
    $('#type').change(function(){
        var type = $(this).val();
        if(type=='client'){
          $('.relateClient').css('visibility','visible');
          $('.relateOrder').css('visibility','hidden');
        }else if(type=='order'){
          $('.relateOrder').css('visibility','visible');
          $('.relateClient').css('visibility','hidden');
        }else{
          $('.relateClient,.relateOrder').css('visibility','hidden');
        }
    });
    $('#type').change();
});
</script>
{/literal}
<title>{$title}</title>
<link href="Resource/Css/EditCommon.css" type="text/css" rel="stylesheet">
{literal}
{/literal}
</head>
<body>
<form action="{url controller=$smarty.get.controller action='save'}" method="post" enctype="multipart/form-data" name="form1" id="form1">
<input name="id" type="hidden" id="id" value="{$aRow.id}" />
<table id="mainTable_">
<tr>
  <td colspan="2" style="color:red;text-align:center;font-size:15px;">请使用安装Excel 2010，其它版本暂不支持</td>
</tr>
<tr>
  <td colspan="2" style="text-align:center;font-size:15px;">[<a href="upload/check/baseTpl.xlsx">点击下载基础模板</a>]<span style="color:red;text-align:center;font-size:15px;">（含基本字段及打印纸张设置）</span></td>
</tr>
<tr>
    <td class="title">选择导入文件：</td>
    <td><input type="file" name="file" id="file"/><span class="bitian">*</span></td>
    </tr>
<tr>
  <td class="title">模板类型：</td>
  <td class="bitian"><select name="type" type="text" id="type">
      <option value=''>请选择</option>
      <option value='sys' {if $aRow.type=='sys'}selected{/if}>系统级</option>
      <option value='client' {if $aRow.type=='client'}selected{/if}>客户级</option>
      <option value='order' {if $aRow.type=='order'}selected{/if}>订单级</option>
    </select><span class="bitian">*</span></td>
  </tr>
<tr class="relateClient" style="visibility:hidden">
  <td class="title">选择关联客户：</td>
  <td><select name="relateClientId" id="relateClientId">
        {webcontrol type='Clientoptions' selected=$aRow.relateClientId}
      </select><span class="btn_more_auto" id="btnMore">
  </td>
</tr>
<tr class="relateOrder" style="visibility:hidden">
  <td class="title">选择关联订单：</td>
  <td><select name="relateOrderId" id="relateOrderId">
        <option value="">请选择</option>
        {foreach from=$orderArr item=item}
        <option value={$item.id} {if $aRow.relateOrderId==$item.id}selected{/if}>{$item.orderCode}</option>
        {/foreach}
      </select><span class="btn_more_auto" id="btnMoreOrd">
  </td>
</tr>
</table>

<table id="buttonTable">
<tr>
        <td>
        <input type="submit" id="Submit" name="Submit" value='保存'>
        <input type="button" id="Back" name="Back" value='返回' onClick="window.location.href='{url controller=$smarty.get.controller}&action=right'"></td>
    </tr>
</table>
</form>
<div id="divModel" class="s-isindex-wrap s-model-set-menu menu-top" style="display:none;">
  <div><input type="text" class="clientText" id="clientText"/></div>
</div>
<div id="divModelOrd" class="s-isindex-wrap s-model-set-menu menu-top" style="display:none;">
  <div><input type="text" class="orderText" id="orderText"/></div>
</div>
</body>
</html>
