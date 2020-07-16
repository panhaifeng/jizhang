<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/Common.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ymPrompt/ymPrompt.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/EditCommon.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/autocomplete/autocomplete.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/autocomplete/autocomplete.js"}
{literal}
<style type="text/css">
table tr td{ white-space:nowrap;}
#divError {display:none;}
.msgError{ color:red; font-size:14px; font-weight:bold;}
.titles{ text-align:right; white-space:nowrap;}
.Tabdashed{
	border-bottom:1px dotted #aaa;
	border-right:1px dotted #aaa;
}
.Tabdashed tr td{
	border-top:1px dotted #999;
	border-left:1px dotted #999;
	white-space:nowrap;
}
.field{
	border:1px; padding-left:0px; padding-bottom:0px;
}
.field legend{margin:1px 1px 1px 1px;}
#tblJh tr td{
line-height:15px
}
#mainTable td{height: 30px;}
.readonly{
	background:#efefef;
}
</style>
<script language="javascript">
var ghHtml=null;
var kwHtml=null;
$(function(){
	ghHtml = $('[name="ganghao[]"]').parent().html();
 	kwHtml = $('[name="kucunWei[]"]').parent().html();
	$.validator.addMethod("onerow", function(value, element) {
		var o = document.getElementsByName('chukuCnt[]');
		for(var i=0;o[i];i++) {
			if(o[i].value!='') return true;
		}
		return false;
	}, "至少需要输入一个数量!");
	$('#form1').validate({
		rules:{
			'caiyangren':'required',
			'chukuDate':'required',
			'chukuCnt[]':'onerow'
		},
		submitHandler : function(form){
			var o=document.getElementsByName('submit');
			for(var i=0;i<o.length;i++){
				o[i].disabled=true;
			}
			form.submit();
		}
		//,debug:true
	});
	//复制
	$('.btnCopy').live('click',function(){
		var trs = $('.trRow');
		var tr = $(this).parents('.trRow');
		//倒数第一个clientHuaxing不为空的行,其后插入
		var pos = trs.length-1;
		var newTr = tr.clone(true);
		$(trs[pos]).after(newTr);
		$('[name="id[]"]',newTr).val('');
		return;
	});
	//删除按钮
	$('.btnDel').live('click',function(){
		var tr = $(this).parents('.trRow');
		var id = $('[name="id[]"]',tr).val();
		var objs = document.getElementsByName('btnDel');
		if(objs.length==1) {
			alert('请至少保留一个产品明细!');
			return false;
		}
		// if(!confirm('确认删除吗?')) return false;
		tr.remove();
		// if(!id) tr.remove();
		// else {
		// 	var url='?controller=trade_order&action=removeByAjax';
		// 	var param={id:id}
		// 	$.getJSON(url,param,function(json){
		// 		if(!json.success) {
		// 			alert(json.msg);
		// 			return false;
		// 		}
		// 		tr.remove();
		// 	});
		// }

	});

	//输入总金额  明细金额平分
	$('#sumMoney').change(function(){
		var len=$('[name="chukuCnt[]"]').length;
		var sumMoney=$(this).val();
		var money=(sumMoney/len).toFixed(2);
		$('[name="money[]"]').val(money);
	});
	//输入总运费  明细运费平分
	$('#sumYunfei').change(function(){
		var len=$('[name="chukuCnt[]"]').length;
		var sumMoney=$(this).val();
		var money=(sumMoney/len).toFixed(2);
		$('[name="yunfei[]"]').val(money);
	});

	$('[name="chukuCnt[]"],[name="danjia[]"]').live('change',function(){
		var tr=$(this).parents('.trRow');
	    var cnt = parseFloat($('[name="chukuCnt[]"]',tr).val())||0;
	    var danjia = parseFloat($('[name="danjia[]"]',tr).val())||0;
	    var money = (danjia*cnt).toFixed(2);
	    $('[name="money[]"]',tr).val(money);
  });
});
function getBarCodeInfo(obj) {
	var tr=$(obj).parents(".trRow");
	if(obj.value=="" || obj.value==null){
		setValue(tr);
		return false;
	}
	var id=$('[name="id[]"]',tr).val();
	var url = '?controller=Sample_Caiyang&action=GetCodeInfo';
	var param={proCode:obj.value,id:id};
	$.getJSON(url,param,function(json){
	auto(tr);
	if(json.success==false) {
		setValue(tr);
		return false;
	}else
	{
		$('[name="chengfen[]"]',tr).val(json.chengfen);
		$('[name="guige[]"]',tr).val(json.guige);
		$('[name="jingwei[]"]',tr).val(json.jingwei);
		$('[name="color[]"]',tr).val(json.color);
		$('[name="menfu[]"]',tr).val(json.menfu);
		$('[name="kezhong[]"]',tr).val(json.kezhong);
		$('[name="kucunCnt[]"]',tr).val(json.kucun);
		$('[name="sampleId[]"]',tr).val(json.id);
		$('[name="proCode[]"]',tr).val(json.proCode);
		$('[name="ganghao[]"]',tr).val("");
		$('[name="kucunWei[]"]',tr).val("");
	}
});
}
function setValue(tr){
	$('[name="chengfen[]"]',tr).val("");
	$('[name="guige[]"]',tr).val("");
	$('[name="jingwei[]"]',tr).val("");
	$('[name="color[]"]',tr).val("");
	$('[name="menfu[]"]',tr).val("");
	$('[name="kezhong[]"]',tr).val("");
	$('[name="kucunCnt[]"]',tr).val("");
	$('[name="sampleId[]"]',tr).val("");
	$('[name="proCode[]"]',tr).val("");
	$('[name="ganghao[]"]',tr).val("");
	$('[name="kucunWei[]"]',tr).val("");
}
function auto(tr){
    //查找现有的值
    var ganghao = $('[name="ganghao[]"]',tr).val();
    var kucunWei = $('[name="kucunWei[]"]',tr).val();

    //替换干净的控件代码
    $('[name="ganghao[]"]',tr).replaceWith(ghHtml);
    $('[name="kucunWei[]"]',tr).replaceWith(kwHtml);

    //保持值不变
    $('[name="ganghao[]"]',tr).val(ganghao);
    $('[name="kucunWei[]"]',tr).val(kucunWei);

    //开始渲染
    $('[name="ganghao[]"]',tr).autocomplete('?controller=Sample_Yangpin&action=autocompleteGh', {
        minChars: 0,
        remoteDataType:'json',
        onFocus:true,
        useCache:false,
        extraParams:{proCode:$('[name="proCode[]"]',tr).val()}
      });

      $('[name="kucunWei[]"]',tr).autocomplete('?controller=Sample_Yangpin&action=autocompleteKw', {
        minChars: 0,
        remoteDataType:'json',
        onFocus:true,
        useCache:false,
        extraParams:{proCode:$('[name="proCode[]"]',tr).val()}
      });
  }

//加5行
function addRow() {
	var t= null;
	var trs = $('.trRow');
	var tr = trs[trs.length-1];
	for(var i=0;i<5;i++) {
		var newTr = $(tr).clone(true);
		$(tr).after(newTr);
		$('[name="chengfen[]"]',newTr).val("");
		$('[name="guige[]"]',newTr).val("");
		$('[name="jingwei[]"]',newTr).val("");
		$('[name="color[]"]',newTr).val("");
		$('[name="menfu[]"]',newTr).val("");
		$('[name="kezhong[]"]',newTr).val("");
		$('[name="kucunCnt[]"]',newTr).val("");
		$('[name="sampleId[]"]',newTr).val("");
		$('[name="proCode[]"]',newTr).val("");
		$('[name="ganghao[]"]',newTr).val("");
		$('[name="id[]"]',newTr).val("");
		$('[name="proCode[]"]',newTr).val("");
		$('[name="danjia[]"]',newTr).val("");
		$('[name="money[]"]',newTr).val("");
		$('[name="yunfei[]"]',newTr).val("");
		$('[name="memo[]"]',newTr).val("");
	}
}
</script>

{/literal}
</head>
<body><form name="form1" id="form1" action="{url controller=$smarty.get.controller action='saveMany'}" enctype="multipart/form-data" method="post">
<fieldset style='padding:5px;'>
<legend style="margin-bottom:0px;">采样基本信息</legend>
<table id="mainTable">
<tr>
  	<td class="title">采样人：</td>
    <td><select name="caiyangren" id="caiyangren" style="width:auto;">
	{webcontrol type='Employoptions' model='jichu_Employ' selected=$rowset.caiyangren}
	</select><span class="bitian">*</span>
      </td>
   <td class="title">客户：</td>
    <td><input name="clientName" type="text" id="clientName" value="{$rowset.clientName}">
	  <input name="clientId" type="hidden" id="clientId" value="{$rowset.clientName}">
   </td>
    <td class="title">采样日期：</td>
    <td><input tupe="text" name="chukuDate" id="chukuDate"  value="{$rowset.chukuDate|default:$smarty.now|date_format:'%Y-%m-%d'}" onClick="calendar()"><span class="bitian">*</span></td>
</tr>
<tr>

    <td class="title">总金额：</td>
    <td><input type="text" name="sumMoney" id="sumMoney" vlue=""/></td>
    <td class="title">总运费：</td>
    <td><input type="text" name="sumYunfei" id="sumYunfei" vlue=""/></td>
    <td>采样单号：</td>
    <td><input type="text" name="caiCode" id="caiCode" value="自动生成" readonly="true" /></td>
  </tr>
</table>
</fieldset>


<div style="max-width:100%; max-height:300px;width:100%;overflow-x:auto;padding-bottom:30px; border:1px solid #999;margin-top:10px;">
<table id="tableList" style="width:100%;text-align:center;" cellpadding="0" cellspacing="0">
  <tr style="background-color:#D4E2F4; font-size:12px; height:28px;">
    <td>[ <a href='javascript:void(0)' onClick="addRow()">+5行</a> ]
    </td>
    <td>条码扫描</td>
    <td>规格</td>
    <td>成分</td>
    <td>颜色</td>
    <td>门幅</td>
    <td>经纬密</td>
    <td>克重</td>
    <td>缸号</td>
    <td>库存位置</td>
    <td>采样件数</td>
    <td>采样数量(KG)<span class="bitian">*</span></td>
    <td>采样米数</td>
    <td>单价</td>
    <td>金额</td>
    <td>运费</td>
    <td>备注</td>
    </tr>
    {foreach from=$rowset item=item}
  <tr class='trRow'>
    <td class="editCol">
      <a href='javascript:void(0)' name="btnCopy" id="btnCopy" class='btnCopy'><img src='Resource/Image/toolbar/copy.gif' border="0" title='复制该行'/></a>
      <a href='javascript:void(0)' class="btnDel" name="btnDel" id="btnDel" ><img src='Resource/Image/toolbar/delete.gif' border="0" title='删除行'/></a>
     </td>
    <td><input type="hidden" name="id[]" id="id[]" value="{$item.id}"/>
    	<input type="hidden" name="sampleId[]" id="sampleId[]" value="{$item.sampleId}"/>
    <input type="text" id="proCode[]" name="proCode[]" value="{$item.sampleInfo.proCode}" onChange="getBarCodeInfo(this)" size="6"></td>
    <td><input tupe="text" name="guige[]" id="guige[]" value="{$item.sampleInfo.guige}" readonly='true' size="14"></td>
    <td><input tupe="text" name="chengfen[]" id="chengfen[]" value="{$item.sampleInfo.chengfen}" readonly='true' size="20"></td>
    <td><input tupe="text" name="color[]" id="color[]" value="{$item.sampleInfo.color}" readonly='true' size="10"></td>
    <td><input tupe="text" name="menfu[]" id="menfu[]" value="{$item.sampleInfo.menfu}" readonly='true' size="10"></td>
    <td><input tupe="text" name="jingwei[]" id="jingwei[]" value="{$item.sampleInfo.jingwei}" readonly='true' size="10"></td>
    <td><input tupe="text" name="kezhong[]" id="kezhong[]" value="{$item.sampleInfo.kezhong}" readonly='true' size="2"></td>
    <td><input tupe="text" name="ganghao[]" id="ganghao[]" value="{$item.ganghao}" size="2"></td>
    <td><input tupe="text" name="kucunWei[]" id="kucunWei[]" value="{$item.kucunWei}" size="2"></td>
    <td><input tupe="text" name="cntJian[]" id="cntJian[]" value="{$item.cntJian}" size="2"></td>
    <td><input tupe="text" name="chukuCnt[]" id="chukuCnt[]"  onBlur="chekRukuCnt()"  value="{$item.chukuCnt}" size="2"></td>
    <td><input tupe="text" name="cntM[]" id="cntM[]" value="{$item.cntM}" size="2"></td>
    <td><input tupe="text" name="danjia[]" id="danjia[]" value="{$item.danjia}" size="2"></td>
    <td><input tupe="text" name="money[]" id="money[]" value="{$item.money}" size="4"></td>
    <td><input tupe="text" name="yunfei[]" id="yunfei[]" value="{$item.yunfei}" size="2"></td>
    <td><input name="memo[]" type="text" id="memo[]" value="{$item.memo}" size="4"></td>
    </tr>
    {/foreach}

  </table>
  </div>


<table id="buttonTable">
<tr>
		<td>
		<input type="submit" id="Submit" name="Submit" value='保存'>
	  {if $aRow.id>0}<input type="button" id="Back" name="Back" value='返回' onClick="window.location.href='{url controller=$smarty.get.controller action=$smarty.get.fromAction}'">{/if}</td>
	</tr>
</table>
<input name="fromAction" type="hidden" id="fromAction" value="{$smarty.get.fromAction}">
<input name="flag" type="hidden" id="flag" value="{$smarty.get.flag}">
</form>

</body>
</html>
