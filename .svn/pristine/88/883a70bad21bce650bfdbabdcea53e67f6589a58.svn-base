<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" src="Resource/Script/Calendar/WdatePicker.js"></script>
<script language="javascript" src="Resource/Script/Common.js"></script>
<script language="javascript" src="Resource/Script/ymPrompt/ymPrompt.js"></script>
<link href="Resource/Script/ymPrompt/skin/bluebar/ymPrompt.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<link href="Resource/Css/Edit200.css" rel="stylesheet" type="text/css" />
{literal}
<script language="javascript">
/*
	实时增加table行数
*/
var newRow;
var table;

$(function(){
	$.validator.addMethod("onerow", function(value, element) {
		var o = document.getElementsByName('ylId[]');	
		//var o1 = document.getElementsByName('cnt[]');	
		for(var i=0;o[i];i++) {
			if(o[i].value!='') return true;
		}
		return false;
	}, "至少需要输入一个物料!");	
	$('#form1').validate({
		rules:{			
			supplierId:"required",
			'ylId[]':'onerow'
		},
		errorPlacement:function(error,element){
			if(element.attr('name')=='ylId[]') {
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
	table = document.getElementById('table_moreinfo');
	var tr = table.rows[table.rows.length-1];
	newRow = tr.cloneNode(true);	
	
	ret2cab();
	cal();
	
	makeYlSel(document.getElementsByName('ylCode[]'),true,applyYl);
	makeSupplierSel(document.getElementById('supplierKey'));
	getTotal();
});

function addRow() {	
	for (var i=0;i<5;i++) {
		addOneRow();
	}	
}
function addOneRow(){
	var temp=table.rows[0].parentNode.appendChild(newRow.cloneNode(true));
	makeYlSel(temp.cells[0].childNodes[0],true,applyYl);
}

function delRow(obj){
	var arrButton = document.getElementsByName('btnDel');	
	var rev = document.getElementsByName('ifRemove[]');
	for(var i=0; i<arrButton.length; i++){
		if (arrButton[i] == obj){
			//table.deleteRow(i+1); 
			table.rows[i+1].style.display='none';
			rev[i].value=1;
			break;
		}
	}
}

function cal(obj){
	var tCnt =0;
	var tMoney =0;
	var c=document.getElementById('spanTotalCnt');
	var m = document.getElementById('spanTotalMoney');
	var pos=-1;
	var cnt = document.getElementsByName('cnt[]');
	var danjia = document.getElementsByName('danjia[]');
	var money = document.getElementsByName('money[]');
	var danwei=document.getElementsByName('danwei[]');
	var zhishu=document.getElementsByName('zhishu[]');
	for(var j=0;cnt[j];j++){
		if(cnt[j]==obj||danjia[j]==obj||danwei[j]==obj||zhishu[j]==obj){
			pos=j;
		}
	}
	//alert(pos);return false;
	if(pos==-1) return false;
	var c1=cnt[pos].value==''?0:parseFloat(cnt[pos].value);
	var d=danjia[pos].value==''?0:parseFloat(danjia[pos].value);
	var zs=zhishu[pos].value==''?0:parseFloat(zhishu[pos].value);
	//alert(c1);
	//debugger;
	if(danwei[pos].value==0){
		money[pos].value=(c1*d).toFixed(3);
	}else{
		money[pos].value=(zs*d).toFixed(3);
	}
	getTotal();
}

function getTotal() {
	var tCnt =0;
	var tZhishu=0;
	var tMoney =0;
	var c=document.getElementById('spanTotalCnt');
	var z=document.getElementById('spanTotalZhishu');
	var m = document.getElementById('spanTotalMoney');
	var cnt = document.getElementsByName('cnt[]');
	var danjia = document.getElementsByName('danjia[]');
	var zhishu=document.getElementsByName('zhishu[]');
	var danwei=document.getElementsByName('danwei[]');
	for (var i=0;cnt[i];i++) {
		tCnt += (parseFloat(cnt[i].value)||0);
		tZhishu+=(parseFloat(zhishu[i].value)||0);
		if(danwei[i].value==0){
			tMoney+= parseFloat(cnt[i].value*danjia[i].value);
		}else{
			tMoney+= parseFloat(zhishu[i].value*danjia[i].value);
		}
	}
	$(c).text(tCnt.toFixed(2));
	$(z).text(tZhishu.toFixed(2));
	$(m).text(tMoney.toFixed(3));
}
function applyYl(ret,obj){
	var pos = $('input[name="ylCode[]"]').index(obj);
	//alert(pos);
	var ylNames = document.getElementsByName('ylName[]');
	var guiges = document.getElementsByName('guige[]');
	var units = document.getElementsByName('unit[]');
	var cnts = document.getElementsByName('cnt[]');
	
	ylNames[pos].value=ret.ylName;
	guiges[pos].value=ret.guige;
	units[pos].value=ret.unit;
	cnts[pos].focus();
}
</script>
{/literal}
</head>

<body>
<div style="text-align:left">{include file="_ContentNav2.tpl"}</div>

<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post">
<input type="hidden" name="rukuId" id="rukuId" value="{$arr_field_value.$pk}">
<input type="hidden" name="isBack" id="isBack" value="{$is_back|default:0}">

<table id="table_baseinfo">
	<tr>
		
     	<td>送货单号：<input name="songhuCode" type="text" id="songhuCode" value="{$arr_field_value.songhuCode}" size="15"></td>
        <td>入库单号：<input name="rukuNum1" type="text" disabled id="rukuNum1" value="{$arr_field_value.rukuNum}" size="15" readonly="readonly" style="border:0px;">
        	<input name="rukuNum" type="hidden" id="rukuNum" value="{$arr_field_value.rukuNum}" size="15" readonly="readonly">
            </td>
    </tr><tr>
		<td>入库日期：<input name="rukuDate" type="text" id="rukuDate"  value="{$arr_field_value.rukuDate|default:$smarty.now|date_format:'%Y-%m-%d'}" size="15" onClick="calendar()"></td>
		<td>供应商：
		  <input name="supplierKey" type="text" id="supplierKey" value="{$arr_field_value.Supplier.compName}">
		  <input name="supplierId" type="hidden" id="supplierId" value="{$arr_field_value.supplierId}"></td>
     </tr><tr>
	<td colspan="2">备注：
	  <textarea name="rukuMemo" style="width:63%;" rows="3" id="rukuMemo">{$arr_field_value.memo}</textarea></td>
	</tr>
	{*<tr>
	  <!--<td>相关采购计划：
	    <input name="planCode" type="text" id="planCode" value="{$arr_field_value.Plan.planCode}">
	  <input name="planId" type="hidden" id="planId" value="{$arr_field_value.planId}"></td>-->
      <td colspan="2">采购入账：<input name="isRuzhang" type="checkbox" id="isRuzhang" style="border:0px;" value="1"></td>
    </tr>*}
</table>

<div id="div_content">
<table id='table_moreinfo' cellpadding="0" cellspacing="0">
	<tr style="background-color:#D4E2F4;font-size:12px; height:25px;">
	  <td>原料编码</td>
	  <td>名称</td>
	  <td>规格</td>
	  <td>单位</td>
      <td>库位</td>
      <td>长度</td>
	  <td>重量</td>
	  <td>数量</td>
	  <td>单价</td>
	  <td>单位</td>
	  <td>金额</td>
      <td>原料备注</td>
	  <td style="border-right:0px solid #cccccc;">操作&nbsp;<a href="#" onClick="javascript:addRow()">+5行</a></td>
	</tr>
	{foreach from=$arr_field_value.Yl item=item}
    	{if $item.guozhangId>0}
		<tr>
		  <td style="padding-left:10px">{$item.Yl.ylCode}</td>
		  <td>{$item.Yl.ylName}</td>
		  <td>{$item.Yl.guige}</td>
		  <td>{$item.Yl.unit}</td>
          <td>{$item.kuweiName}</td>
          <td>{$item.len}</td>
		  <td>{$item.cnt}</td>
		  <td>{$item.zhishu}</td>
		  <td>{$item.danjia}</td>
		  <td>{$item.unit}</td>
          
		  <td>{$item.money}</td>
		  <td>{$item.memo}</td>
		  <td>已入账</td>
	  </tr>
      {/if}
      {if $item.guozhangId==0}
		<tr>
		  <td style="padding-left:10px">		  		
            <input type="text" name='ylCode[]' id='ylCode[]' value='{$item.Yl.ylCode}'/>
            <input type="hidden" name="ylId[]" id="ylId[]" value="{$item.ylId}"></td>
		  <td><input name="ylName[]" type="text" id="ylName[]" value="{$item.Yl.ylName}" size="8" readonly="readonly"></td>
		  <td><input name="guige[]" type="text" id="guige[]" value="{$item.Yl.guige}" size="8" readonly="readonly"></td>
		  <td><input name="unit[]" type="text" id="unit[]" value="{$item.Yl.unit}" size="8" readonly="readonly"></td>
          <td><select name='kuweiId[]'>		
				{webcontrol type='TmisOptions' model='Jichu_Kuwei' selected=$item.kuweiId}
				</select></td>
          <td><input name="len[]" type="text" id="len[]" size="8" value="{$item.len}"></td>
		  <td><input name="cnt[]" type="text" id="cnt[]" size="8" value="{$item.cnt}" onChange="cal(this)"></td>
		  <td><input name="zhishu[]" type="text" id="zhishu[]" size="8" value="{$item.zhishu}" onChange="cal(this)"></td>
		  <td><input name="danjia[]" type="text" id="danjia[]" onChange="cal(this)" value="{$item.danjia}" size="8"></td>
		  <td><select name="danwei[]" id="danwei[]" onChange="cal(this)">>
          <option value="0" {if $item.danwei==0}selected{/if}>按重量计算</option>
          <option value="1" {if $item.danwei==1}selected{/if}>按数量计算</option>
	      </select></td>
		  <td><input name="money[]" type="text" disabled id="money[]" value="{$item.money}" size="8" readonly="readonly"></td>
		  <td><input name="memo[]" type="text" id="memo[]" size="8" value="{$item.memo}"></td>
		  <td><input type="button" name="btnDel" id="btnDel" value="删除" size="8" onClick="delRow(this)" class="button">
		  <input type="hidden" id="ifRemove[]" name="ifRemove[]" value="0">
		  <input type="hidden" name="id[]" id="id[]" value="{$item.id}">
		  </td>
		</tr>
        {/if}
	{/foreach}
		<tr>
		  <td style="padding-left:10px">
          	<input type="text" name='ylCode[]' id='ylCode[]' />
		  </td>
		  <td><input name="ylName[]" type="text" id="ylName[]" size="8" readonly="readonly"></td>
		  <td><input name="guige[]" type="text" id="guige[]" size="8" readonly="readonly"></td>
		  <td><input name="unit[]" type="text" id="unit[]" size="8" readonly="readonly"></td>
          <td><select name='kuweiId[]'>		
				{webcontrol type='TmisOptions' model='Jichu_Kuwei'}
				</select></td>
          <td><input name="len[]" type="text" id="len[]" size="8"></td>
		  <td><input name="cnt[]" type="text" id="cnt[]" size="8" onChange="cal(this)"></td>
		  <td><input name="zhishu[]" type="text" id="zhishu[]" size="8" onChange="cal(this)"></td>
		  <td><input name="danjia[]" type="text" id="danjia[]" size="8" onChange="cal(this)"></td>
		  <td><select name="danwei[]" id="danwei[]" onChange="cal(this)">>
           <option value="0">按重量计算</option>
          <option value="1">按数量计算</option>
	      </select></td>
		  <td><input name="money[]" type="text" disabled id="money[]" size="8" readonly="readonly"></td>
			<td><input name="memo[]" type="text" id="memo[]" size="8"></td>
		  <td><input type="button" name="btnDel" id="btnDel" value="删除" size="8" onClick="delRow(this)" class="button">
		  <input type="hidden" id="ifRemove[]" name="ifRemove[]" value="0"></td>
		</tr>		
</table>
</div>
<div id=heji>
	<div align="left" id="d1">合计:</div>
    <div align="right" id="d2"><span>重量:<span id='spanTotalCnt' style=" background-color:#F93; width:60px;">0</span>数量:<span id='spanTotalZhishu' style=" background-color:#F93; width:60px;">0</span></span><span>金额:<span id='spanTotalMoney' style=" background-color:#F93; width:60px;">0</span></span></div>
</div>	  

<table>
	<tr>    	
    	<td><input type="submit" id="Submit" name="Submit" value='保存并新增下一个' class="button"></td>
    	<td><input type="submit" id="Submit" name="Submit" value='保存' class="button"></td>
		<td><input type="reset" id="Reset" name="Reset" value='重置' class="button"></td>
    </tr>
</table>

</form>
</body>
</html>
<script language="javascript" src="Resource/Script/SetDivHeight.js"></script>