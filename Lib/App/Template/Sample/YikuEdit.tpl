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
			'kucunWei':'required',
			'kucunCnt':'required number'
		},
		submitHandler : function(form){
			$('[name="submit"]').attr('disabled',true);
			form.submit();
		}
    //,debug:true
	});
  $('#kucunCnt').change(function(){
      if($('#kucunCnt').val() > $('#kucun').val())
      {
        alert('移库的库存超过原有库存，请重新设置');
        $('#kucunCnt').val('');
      }
      if($('#kucunCnt').val() == 0 && $('#kucunCnt').val() != '')
      {
        alert('移库数量请勿为0，请重新设置');
        $('#kucunCnt').val('');
      }
  });

  $('#kucunWei').change(function(){
      if($('#kucunWei').val() == $('#yuankucunWei').val())
      {
        alert('库位设置相同，请重新设置');
        $('#kucunWei').val('');
      }
  });

  //
	// $('#proCode').focus();

  // $('#proCode').change(function(){
  //   var url = '?controller=Sample_Yangpin&action=getProInfoByAjax';
  //   var param={proCode:this.value};
  //   $.getJSON(url,param,function(json){
  //     if(json.success===false) {
  //       document.getElementById('chengfen').value="";
  //       document.getElementById('guige').value="";
  //       document.getElementById('color').value="";
  //       document.getElementById('jingwei').value="";
  //       document.getElementById('menfu').value="";
  //       document.getElementById('kezhong').value="";
  //       alert(json.msg);
  //     }else{     
  //       document.getElementById('chengfen').value=json.chengfen;
  //       document.getElementById('guige').value=json.guige;
  //       document.getElementById('color').value=json.color;
  //       document.getElementById('jingwei').value=json.jwmi;
  //       document.getElementById('menfu').value=json.menfu;
  //       document.getElementById('kezhong').value=json.kezhong;
  //     }
  //   });
  // });

});
	
// function setBox(){
// 	var imagevalue=document.getElementById('imageFile').value;
// 	var cbdelImage=document.getElementById('isDelImage');
// 	if(imagevalue!='' &&　cbdelImage!=null){
// 		document.getElementById('isDelImage').checked=true;			
// 	}else{
// 		document.getElementById('isDelImage').checked=false;
// 	}
// }
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
</style>
{/literal}
</head>
<body>
 <form name="form1" id="form1" action="{url controller=$smarty.get.controller action='SaveYiku'}" enctype="multipart/form-data" method="post">
<div class="main">
  <div class="pull-left">
  <table class="table">
    <tr>
      <td class="title">产品编号：</td>
      <td><input type="text" name="proCode" id="proCode" value="{$aRow.proCode}" readonly></td>
    </tr>
    <tr>
      <td class="title">规格：</td>
      <td><input type="text" name="guige" id="guige" value="{$aRow.guige}" readonly></td>
    </tr>
    <tr>
      <td class="title">颜色：</td>
      <td><input type="text" name="color" id="color" value="{$aRow.color}" readonly></td>
    </tr>
    <tr>
      <td class="title">门幅：</td>
      <td><input type="text" name="menfu" id="menfu" value="{$aRow.menfu}" readonly></td>
    </tr>
    <tr>
      <td class="title">克重：</td>
      <td><input type="text" name="kezhong" id="kezhong" value="{$aRow.kezhong}" readonly></td>
    </tr>
    <tr>
      <td class="title">成分：</td>
      <td><input type="text" name="chengfen" id="chengfen" value="{$aRow.chengfen}" readonly></td>
    </tr>
    <tr>
      <td class="title">经纬密：</td>
      <td><input type="text" name="jingwei" id="jingwei" value="{$aRow.jwmi}" readonly></td>
    </tr>
    <tr>
    <td class="title">缸号：</td>
    <td><input type="text" name="ganghao" id="ganghao" value="{$aRow.ganghao}" readonly></td>
    </tr>
    <tr>
    <td class="title">原库存位置：</td>
    <td><input type="text" name="yuankucunWei" id="yuankucunWei" value="{$aRow.kucunWei}" readonly></td>
    </tr>
    <tr>
    <td class="title">原库存数量：</td>
    <td><input type="text" name="kucun" id="kucun" value="{$aRow.kucun}" readonly></td>
    </tr>
<!--     <tr>
    <td class="title">调整日期：</td>
    <td><input type="hidden" name="Date" id="Date" value="{$aRow.Date|default:$smarty.now|date_format:'%Y-%m-%d'}" onClick="calendar()"><span class="bitian">*</span></td>
    </tr> -->
    <tr>
    <td class="title">移动库存位置：</td>
    <td><input type="text" name="kucunWei" id="kucunWei" value=""><span class="bitian">*</span></td>
    </tr>
  <tr>      
    <td class="title">移动数量：</td>
    <td>
      <input type="text" name="kucunCnt" id="kucunCnt" value="" ><span class="bitian">*</span>
      <input type="hidden" name="Date" id="Date" value="{$aRow.Date|default:$smarty.now|date_format:'%Y-%m-%d'}" onClick="calendar()">
    </td>
  </tr>

  </table>
  
  </div>

</div>

<table id="buttonTable">
<tr>
    <td>
    <!-- <input type="hidden" name="id" id="id" value="{$aRow.id}"> -->
    <input type="hidden" name="fromAction" id="fromAction" value="{$smarty.get.fromAction}">
    <input type="submit" id="submit" name="submit"  value='保存'>
    </td>
  </tr>
</table>
</form>
</body>
</html>
