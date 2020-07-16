<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" src="Resource/Script/Common.js"></script>
<script language="javascript" src="Resource/Script/Calendar/WdatePicker.js"></script>
<script language="javascript"  src="Resource/Script/MyJqPlugin.js"></script>
<script language="javascript"  src="Resource/Script/thickbox/thickbox.js"></script>
<link href="Resource/Script/thickbox/thickbox.css" type="text/css" rel="stylesheet">
<link href="Resource/Css/Edit200.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<script language="javascript">
/*
	实时增加table行数
*/
var kind = '';
var danjia=parseFloat({$arr_field_value.danjiack})||0;
{literal}
var newRow;
var table;


$(function(){
	$("[name='proCode[]']").showWin("proCode",applyPro,"kind="+kind);
	$("#clientName").showWin("compName",function(){},'kind=1');
	$("#orderCode").showWin("orderCode",function(json){
		// dump(json);exit;
	    $("#orderId").val(json.orderId);
		$("#order2productId").val(json.xid);
		if(!json) return false;
		$('#proName').html('品名：'+json.proName+'；规格：'+json.guige+'；要货数：'+json.cnt+'吨'+'；单价：'+json.danjia);
		if(json.danjia)danjia=json.danjia;
			//将客户插入
		document.getElementById("clientName").value=json.compName;
		document.getElementById("clientId").value=json.clientId;

	});
	// $()
	$.validator.addMethod("onerow", function(value, element) {
		var o = document.getElementsByName('ruku2proId[]');	
		var o1 = document.getElementsByName('cnt[]');	
		for(var i=0;o[i];i++) {
			if(o[i].value!='') return true;
		}
		return false;
	}, "至少需要输入一个产品!");	 
	$('#form1').validate({
		rules:{
			clientId:"required",
			// orderId:"required",	
			chukuDate:"required",
			'ruku2proId[]':'onerow'
		},
		submitHandler : function(form){
			if(!$("#depId").val()&&$("#depId")[0]){
				alert("请选择部门！");
				return;
			}
			$('[name="Submit"]').attr('disabled',true);
			form.submit();
		},
		//errorLabelContainer:"#divError",
		//wrapper:"li",
		errorPlacement:function(error,element){
			if(element.attr('name')=='proId[]') {
				alert(error.text());
			} else error.insertAfter(element);
		}//,debug:true
	});
	
	$('input[name="memo[]"]').keydown(function(e){
		if(e.keyCode==13) {
			addOneRow();
			return false;
		}
		return true;
	});
	/*$('input[name="danjia[]"],input[name="cnt[]"]').change(function(e){
		cal();
	});*/
	ret2cab();
	//cal();	
	
	// table = document.getElementById('table_moreinfo');
	// var tr = table.rows[table.rows.length-1];
	table = $("#table_moreinfo");
	var tr = table.find(".trPro").eq(0);
	newRow = tr.clone(true);
	$("input[type='text'],input[type='hidden']",newRow).val("");
	for(var i=0;i<4;i++){
		addOneRow();
	}
	
	$('#btnSel').click(function(){
		var url="?controller=Cangku_Ruku&action=ListForCk&width=800&pos=0";
		url+="&TB_iframe=1";
		//alert(url);
		tb_show("坯纱入库列表",encodeURI(url),false);	
	});
	
	//删除按钮，删除辅料按钮
	$('.btnDel').live('click',function(){
		var tr = $(this).parents('.rowPro');
		if(!confirm('确认删除坯纱信息吗?')) return false;
		var id = $('[name="id[]"]',tr).val();
		if(!id) tr.remove();
		else {
			var url='?controller=Cangku_Chuku&action=RemoveByAjax';
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
});

function addRow() {	
	for (var i=0;i<5;i++) {
		addOneRow();
	}	
}
//设置单价为三位小数
function setXsws(o){
	var name = o.name;
	var i =$('input[name='+name+']').index(o);
	$('input[name="danjia[]"]')[i].value=(parseFloat($('input[name="danjia[]"]')[i].value)).toFixed(5);//保留三位小数
}

function addOneRow(){
	// var temp = table.rows[0].parentNode.appendChild(newRow.cloneNode(true));
	table.append(newRow.clone(true));
	// makeProSel(temp.cells[0].childNodes[0],true,applyPro);
}


function delRow(obj){
	if(!confirm('确认删除吗?')) return;
	 var tr=$(obj).parents('.trPro');
	 $('input',tr).val('');
	 tr.css('display','none');
	
}


function applyPro(ret,obj){
	// dump(ret);
	var pos = $('input[name="proCode[]"]').index(obj);
	//alert(pos);
	var proNames = document.getElementsByName('proName[]');
	var guiges = document.getElementsByName('guige[]');
	//var colors = document.getElementsByName('color[]');
	var units = document.getElementsByName('unit[]');
	var length = document.getElementsByName('length[]');
	// var bieming = document.getElementsByName('bieming[]');
	// var guigeClient = document.getElementsByName('guigeClient[]');
	var cntKucun=document.getElementsByName("cntKucun[]");
	// alert(proNames[pos].value);
	proNames[pos].value=ret.proName||"";
	guiges[pos].value=ret.guige;
	//colors[pos].value=ret.color;
	units[pos].value=ret.unit;
	// cntKucun[pos].value=ret.cntKucun;
	// length[pos].focus();
	
	// bieming[pos].value=ret.bieming||'';
	// guigeClient[pos].value=ret.guigeClient||'';
}
function getCnt(obj){
	var id=document.getElementsByName("id[]");
	var cnt=document.getElementsByName("cnt[]");
	var cnt1=document.getElementsByName("cnt1[]");
	var cntKucun=document.getElementsByName("cntKucun[]");
	var pos=-1;
	for(var i=0;cnt[i];i++){
		if(cnt[i]==obj){
			pos=i;
		}
	}
	if(pos==-1) return false;
	//alert(cnt[pos].value);alert(cntKucun[pos].value);
	if(parseFloat(cnt[pos].value)>parseFloat(cntKucun[pos].value)){
		alert('出库数大于库存数,不能出库!');
		cnt[pos].value='';
		return false;
	}else{
		cnt[pos].focus();
		return true;
	}
}
function checkForm(){
	//alert(2);return false;
	var proId=document.getElementsByName('proId[]');
	var cnt=document.getElementsByName('cnt[]');
	// var cntCi=document.getElementsByName('cntCi[]');
	var pos=0;
	for(var i=0;proId[i];i++){
		if(proId[i].value!=''){
			if(cnt[i].value==''){
				pos++;
			}
		}
	}
	if(pos>0){
		alert('合格数和次品数请至少输入一个!');
		return false;
	}else{
		return true;
	}
}

//当子窗口点击选择按钮后返回数据，该方法接受数据并处理
function thickboxCallBack(ret,pos){
	if(!ret) return false;
	//循环处理返回值，加载到界
	var tbl = document.getElementById('tableList');
	for(var i=0;ret[i];i++) {
		var tr = tbl.insertRow(-1);
		tr.className='rowPro';
		var _edit='<a href="javascript:void(0)" class="btnDel" name="btnDel" id="btnDel" ><img src="Resource/Image/toolbar/delete.gif" border="0" title="删除"/><input name="ruku2proId[]" type="hidden" id="ruku2proId[]" value="'+ret[i].id+'"><input name="id[]" type="hidden" id="id[]" value=""></a>';
		
		$(tr.insertCell(-1)).html(_edit);
		$(tr.insertCell(-1)).html(ret[i].proName);
		$(tr.insertCell(-1)).html(ret[i].guige+'<input name="productId[]" type="hidden" id="productId[]" value="'+ret[i].productId+'" size="10" readonly>');
		$(tr.insertCell(-1)).html('<input name="pihao[]" type="text" id="pihao[]" value="'+ret[i].pihao+'" size="10" readonly>');
		if(ret[i].kuweiName==null)ret[i].kuweiName='';
		$(tr.insertCell(-1)).html(ret[i].kuweiName+'<input name="kuweiId[]" type="hidden" id="kuweiId[]" value="'+ret[i].kuweiId+'" size="10" readonly>');
		$(tr.insertCell(-1)).html(ret[i].supplierName+'<input name="supplierId[]" type="hidden" id="supplierId[]" value="'+ret[i].supplierId+'" size="10">');
		$(tr.insertCell(-1)).text(ret[i].cnt);
		$(tr.insertCell(-1)).text(ret[i].llckCnt==''?' ':ret[i].llckCnt);
		$(tr.insertCell(-1)).text(ret[i].kucunCnt==''?' ':ret[i].kucunCnt);
		$(tr.insertCell(-1)).html('<input name="cnt[]" type="text" id="cnt[]" value="" size="10">');
		$(tr.insertCell(-1)).html('<input name="zxDanjia[]" type="text" id="zxDanjia[]" value="" size="10">');
		$(tr.insertCell(-1)).html('<input name="danjiack[]" type="text" id="danjiack[]" value="'+danjia+'" size="10">');
	}
}
</script>
<style>
.head td{
	background-color:#D4E2F4;
	font-size:12px;
	height:24px;
	position: relative;
	top:expression(this.parentNode.parentNode.parentNode.parentNode.scrollTop+'px');
	z-index:100;
}
.rowPro td{ border-bottom:1px dotted #D4E2F4;}
</style>
{/literal}
</head>

<body>
<div style="text-align:left">{include file="_ContentNav2.tpl"}</div>

<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post">
<input type="hidden" name="chukuId" id="chukuId" value="{$arr_field_value.$pk}">
<input type="hidden" name="isBack" id="isBack" value="{$is_back|default:0}">
<input type="hidden" name="operatorId" id="operatorId" value="{$arr_field_value.operatorId|default:$operator_id}">
<input type="hidden" name="order2productId" id="order2productId" value="{$arr_field_value.order2productId|default:0}">

<table id="table_baseinfo">
	<tr>
		
		<td >出库日期：
        <input name="chukuDate" type="text" id="chukuDate"  value="{$arr_field_value.chukuDate|default:$smarty.now|date_format:'%Y-%m-%d'}" size="15" onClick="calendar()"></td>
    <td>出库单号：
    <input name="chukuNum1" type="text" disabled id="chukuNum1" value="{$arr_field_value.chukuNum}" style="border:0px;"><input name="chukuNum" type="hidden" id="chukuNum" value="{$arr_field_value.chukuNum}" size="15" readonly="readonly"></td>
    </tr><tr>
    		<td align="left">相关订单：
<input name="orderCode" type="text" id="orderCode" value="{$arr_field_value.Order.orderCode}">
          <input name="orderId" type="hidden" id="orderId" value="{$arr_field_value.orderId}"></td>
    <td><!--流转单号：
      <input name="chukuOrder" type="text" id="chukuOrder" value="{$arr_field_value.clientId}">-->
      
        客户名称：
      <input name="clientName" type="text" id="clientName" value="{$arr_field_value.Client.compName}" style=" overflow:visible">
      <input name="clientId" type="hidden" id="clientId" value="{$arr_field_value.clientId}"></td>
	<tr>
    <tr>
    <td colspan="2">描述：<span id='proName' style="color:green">{$arr_field_value.proName}</span></td>
    </tr>
	<td colspan="3">备注：
	  <textarea name="chukuMemo" cols="50" rows="3" id="chukuMemo" style="width:65%">{$arr_field_value.memo}</textarea></td>
    <!--<td colspan="">成品入账：<input name="isRuzhang" type="checkbox" id="isRuzhang" style="border:0px;" value="1"></td>-->
	
    </tr>
</table>
<div style="border:1px solid #D4E2F4;" id='div_content'>
    <table id="tableList" style="width:100%; text-align:center" cellpadding="1" cellspacing="0">
      <tr class="head">
      	<td nowrap>[<span id="btnSel" style="color:#00F;cursor:pointer;" class="thickbox" title="单击选择要坯纱信息">选择坯纱</span>]</td>
        <td>品名</td>
        <td>规格</td>
        <td>批号</td>
        <td>库位</td>
        <td>供应商</td>
        <td>入库吨数</td>
        <td>累计出库</td>
        <td>余存</td>
        <td>本次出库(吨)</td>
        <td>装卸单价</td>
        <td>出库单价</td>
        </tr>
        {foreach from=$arr_field_value.Pro item=item}
      <tr class="rowPro">
      	<td><a href='javascript:void(0)' class="btnDel" name="btnDel" id="btnDel" ><img src='Resource/Image/toolbar/delete.gif' border="0" title='删除'/></a><input name="ruku2proId[]" type="hidden" id="ruku2proId[]" value="{$item.ruku2proId}"><input name="id[]" type="hidden" id="id[]" value="{$item.id}"></td>
        <td>{$item.proName}</td>
        <td>{$item.guige}</td>
        <td><input name="pihao[]" type="text" id="pihao[]" value="{$item.pihao}" size="10" readonly></td>
        <td>{$item.kuweiName}<input name="kuweiId[]" type="hidden" id="kuweiId[]" value="{$item.kuweiId}" size="10" readonly></td>
        <td>{$item.supplierName|default:'&nbsp;'}<input name="supplierId[]" type="hidden" id="supplierId[]" value="{$item.supplierId}" size="10"></td>
        <td>{$item.cntRuku|default:'&nbsp;'}</td>
        <td>{$item.cntChuku|default:'&nbsp;'}</td>
        <td>{$item.yucun|default:'&nbsp;'}</td>
        <td nowrap><input name="cnt[]" type="text" id="cnt[]" value="{$item.cnt}" size="10"></td>
         <td nowrap><input name="zxDanjia[]" type="text" id="zxDanjia[]" value="{$item.zxDanjia}" size="10"></td>
          <td nowrap><input name="danjiack[]" type="text" id="danjiack[]" value="{$item.danjiack}" size="10"></td>
      </tr>
     {/foreach}
  </table> 
  </div>
<!--
<div id=heji>
	<div align="left" id="d1">合计:</div>
    <div align="right" id="d2"><span>数量:<span id='spanTotalCnt' style=" background-color:#F93; width:60px;">0</span></span><span>金额:<span id='spanTotalMoney' style=" background-color:#F93; width:60px;">0</span></span></div>
</div>	  
-->
<table>
	<tr>
    	<td><input type="submit" id="Submit" name="Submit" value='保存并新增下一个' class="button" onClick="return checkForm()"></td>
    	<td><input type="submit" id="Submit" name="Submit" value='保存' class="button" onClick="return checkForm()"></td>
		<td><input type="reset" id="Reset" name="Reset" value='重置' class="button"></td>
    </tr>
</table>

</form>
</body>
</html>
<script language="javascript" src="Resource/Script/SetDivHeight.js"></script>