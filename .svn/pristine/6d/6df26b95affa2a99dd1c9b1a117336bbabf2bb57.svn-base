<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/common.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{literal}
<script language="javascript">
$(function(){

	$('#form1').validate({
		rules:{
			'proCode':'required',
			'kucunCnt':'required number',
      'rukuDate':'required'
		},
		submitHandler : function(form){
			$('[name="submit"]').attr('readonly',true);
			form.submit();
		}
    //,debug:true
	});

  //
	// $('#proCode').focus();

  $('#proCode').change(function(){
    var url = '?controller=Sample_Yangpin&action=getProInfoByAjax';
    var param={proCode:this.value};
    $.getJSON(url,param,function(json){
      if(json.success===false) {
        document.getElementById('chengfen').value="";
        document.getElementById('guige').value="";
        document.getElementById('color').value="";
        document.getElementById('menfu').value="";
        document.getElementById('kezhong').value="";
        alert(json.msg);
      }else{
        document.getElementById('chengfen').value=json.chengFen;
        document.getElementById('guige').value=json.guige;
        document.getElementById('color').value=json.color;
        document.getElementById('menfu').value=json.menfu;
        document.getElementById('kezhong').value=json.kezhong;
      }
    });
  });

});

function setBox(){
	var imagevalue=document.getElementById('imageFile').value;
	var cbdelImage=document.getElementById('isDelImage');
	if(imagevalue!='' &&　cbdelImage!=null){
		document.getElementById('isDelImage').checked=true;
	}else{
		document.getElementById('isDelImage').checked=false;
	}
}
</script>
<style type="text/css">
  .main{
    clear: both;
  }
  .pull-left{
    float: left;
    margin-left:20px;
  }
  .box{
    width: 300px;
    height: 400px;
  }
  .title{
    text-align: right;
  }
#kucunCnt{
  background-image: url(Resource/Image/unitKg.png);
  background-position: 125px 3px;
  background-repeat: no-repeat;
}
</style>
{/literal}
</head>
<body>
 <form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" enctype="multipart/form-data" method="post">
<div class="main">
  <div class="pull-left">
  <table class="table">
    <tr>
      <td class="title">产品编号：</td>
      <td><input tupe="text" name="proCode" id="proCode" value="{$aRow.proCode}"><span class="bitian">*</span></td>
    </tr>
    <tr>
      <td class="title">规格：</td>
      <td><input tupe="text" name="guige" id="guige" value="{$aRow.guige}" readonly></td>
    </tr>
    <tr>
      <td class="title">颜色：</td>
      <td><input tupe="text" name="color" id="color" value="{$aRow.color}" readonly></td>
    </tr>
    <tr>
      <td class="title">门幅：</td>
      <td><input tupe="text" name="menfu" id="menfu" value="{$aRow.menfu}" readonly></td>
    </tr>
    <tr>
      <td class="title">克重：</td>
      <td><input tupe="text" name="kezhong" id="kezhong" value="{$aRow.kezhong}" readonly></td>
    </tr>
    <tr>
      <td class="title">成分：</td>
      <td><input tupe="text" name="chengfen" id="chengfen" value="{$aRow.chengfen}" readonly></td>
    </tr>
    <tr>
    <td class="title">图片文件：</td>
  <td><input type="file" name="imageFile" id="imageFile" onChange="setBox()" style="width:215px;">
    {if $aRow.imageFile!=''}&nbsp;&nbsp;删除原来图片
    <input type="checkbox" name="isDelImage" value="yes" id="isDelImage" style="width:25px;" title="只选择该复选框则只删除原来图片，可以不选择图片">{/if}
    </td>
  </tr>
  <tr>
    <td class="title">件数：</td>
    <td>
      <input tupe="text" name="cntJian" id="cntJian" value="{$aRow.cntJian}" >
    </td>
  </tr>
  <tr>
    <td class="title">入库数量：</td>
    <td>
      <input tupe="text" name="kucunCnt" id="kucunCnt" value="{$aRow.kucunCnt}" ><span class="bitian">*</span>
    </td>
  </tr>
  <tr>
    <td class="title">米数：</td>
    <td>
      <input tupe="text" name="cntM" id="cntM" value="{$aRow.cntM}" >
    </td>
  </tr>
     <tr>
    <td class="title">上架日期：</td>
    <td><input tupe="text" name="rukuDate" id="rukuDate" value="{$aRow.rukuDate|default:$smarty.now|date_format:'%Y-%m-%d'}" onClick="calendar()"><span class="bitian">*</span></td>
    </tr>
    <tr>
    <td class="title">缸号：</td>
    <td><input tupe="text" name="ganghao" id="ganghao" value="{$aRow.ganghao}"></td>
    </tr>
    <tr>
    <td class="title">库存位置：</td>
    <td><input tupe="text" name="kucunWei" id="kucunWei" value="{$aRow.kucunWei}"></td>
    </tr>
    <tr>
    <td class="title">备注：</td>
    <td><textarea name="memo" id="memo" cols="50"  style="width:300px;" value="">{$aRow.memo}</textarea></td>
    </td>
    </tr>
  </table>

  </div>
  <div class="pull-left box">
    {if $aRow.imageFile!=''}
    <img src="{$aRow.imageFile}"  border="0.5">
    {else}
    <table width="200" height='70' border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center" valign="middle" style="color:#CCC; border:1px;">暂无图片</td>
        </tr>
    </table>
    {/if}
  </div>
</div>

<table id="buttonTable">
<tr>
    <td>
    <input type="hidden" name="id" id="id" value="{$aRow.id}">
    <input type="hidden" name="fromAction" id="fromAction" value="{$smarty.get.fromAction}">
    <input type="submit" id="submit" name="submit"  value='保存并打印条码'>
    <input type="submit" id="submit" name="submit"  value='保存并新增'>
    {if $smarty.get.fromAction}<input type="button" id="Back" name="Back" value='返回' onClick="window.location.href='{url controller=$smarty.get.controller action=$smarty.get.fromAction}'">{/if}
    </td>
  </tr>
</table>
</form>
</body>
</html>
