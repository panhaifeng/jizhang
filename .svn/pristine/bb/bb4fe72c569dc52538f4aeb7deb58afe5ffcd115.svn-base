<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>

<script language="javascript" src="Resource/Script/Calendar/WdatePicker.js"></script>
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<script language="javascript" src="Resource/Script/Common.js"></script>
<script language="javascript"  src="Resource/Script/MyJqPlugin.js"></script>
<script language="javascript" src="Resource/Script/ymPrompt/ymPrompt.js"></script>
<script language="javascript"  src="Resource/Script/thickbox/thickbox.js"></script>
<link href="Resource/Script/thickbox/thickbox.css" type="text/css" rel="stylesheet">
<link href="Resource/Script/ymPrompt/skin/bluebar/ymPrompt.css" rel="stylesheet" type="text/css" />
<link href="Resource/Css/Edit200.css" rel="stylesheet" type="text/css" />

<script language="javascript">
var kind = {$arr_field_value.kind2};
{literal}
/*
	实时增加table行数
*/
var newRow;
var table;

$(function(){
	$("[name='proCode[]']").showWin("proCode",applyPro,"kind="+kind);
	$.validator.addMethod("onerow", function(value, element) {
		var o = document.getElementsByName('proId[]');	
		var o1 = document.getElementsByName('cnt[]');	
		for(var i=0;o[i];i++) {
			if(o[i].value!=''&&o1[i].value!='') return true;
		}
		return false;
	}, "至少需要输入一个产品!");	 
	$('#form1').validate({
		rules:{	
		      "supplierId":"required",		
			rukuNum:"required",	
			rukuDate:"required",
			'proId[]':'onerow'
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
	$('input[name="danjia[]"],input[name="cnt[]"]').live('change',function(e){
		cal();
		getMoney(this);
	});
	ret2cab();
	cal();
	table = document.getElementById('table_moreinfo');
	var tr = table.rows[table.rows.length-1];
	newRow = tr.cloneNode(true);
	$("input[type='text'],input[type='hidden']",newRow).val('');
	// makeProSel(document.getElementsByName('proCode[]'),true,applyPro);

	for(var i=0;i<4;i++){
		addOneRow();
	}
	
	
	//删除行处理
	$('input[name="btnDel"]').live('click',function(){
			if(!confirm("确认删除入库明细信息？")){
				return false;	
			}
			var tr = $(this).parents('.trPro');
			var table=document.getElementById('table_moreinfo');
			//当前行数
			var pos=$('[name="btnDel"]').index(this);
			//alert(pos);
			//获取行数有问题就暂停
			if(pos<0)return;
			//是否有id,id不存在则直接删除行，否则通过ajax删除表中的信息
			var id=$('[name="id[]"]').eq(pos).val();
			if(!id){
				//tr.remove();
				table.deleteRow(pos+1);
				return;
			}
			//地址
			var url="?controller=Cangku_Ruku&action=removebyajax";
			var param={id:id}
			$.getJSON(url,param,function(json){
						if(!json.success) {
							alert(json.msg);
							return false;
						}
						//tr.remove();
						table.deleteRow(pos+1);
			});
	});
});

function addRow() {	
	for (var i=0;i<5;i++) {
		addOneRow();
	}	
}

function getMoney(o){
	var pos=$('[name="'+o.name+'"]').index(o);
	var danjia=parseFloat($('[name="danjia[]"]').eq(pos).val())||0;
	var cnt=parseFloat($('[name="cnt[]"]').eq(pos).val())||0;
	var money=(danjia*cnt).toFixed(2);
	$('[name="money[]"]').eq(pos).val(money);
}

function addOneRow(){
	var temp = table.rows[0].parentNode.appendChild(newRow.cloneNode(true));
	// makeProSel(temp.cells[0].childNodes[0],true,applyPro);
}

function delRow(obj){
	/*
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
	*/
}
function cal(){
	var tCnt =0;
	var tMoney =0;
	var c=document.getElementById('spanTotalCnt');
	var m = document.getElementById('spanTotalMoney');
	var cnt = document.getElementsByName('cnt[]');
	//var danjia = document.getElementsByName('danjia[]');
	
	for (var i=0;cnt[i];i++) {
		tCnt += (parseFloat(cnt[i].value)||0);
		//tMoney+= parseFloat(cnt[i].value*danjia[i].value);
	}
	$(c).text(tCnt.toFixed(3));
	//$(m).text(tMoney.toFixed(2));
}
function applyPro(ret,obj){
	// dump(ret);
	var pos = $('input[name="proCode[]"]').index(obj);
	//alert(pos);
	var proNames = document.getElementsByName('proName[]');
	var guiges = document.getElementsByName('guige[]');
	var units = document.getElementsByName('unit[]');
	var cnts = document.getElementsByName('cnt[]');
	
	proNames[pos].value=ret.proName;
	guiges[pos].value=ret.guige;
	//units[pos].value=ret.unit;
	// cnts[pos].focus();
}
//设置单价为三位小数
function setXsws(o){
	var name = o.name;
	var i =$('input[name='+name+']').index(o);
	$('input[name="danjia[]"]')[i].value=(parseFloat($('input[name="danjia[]"]')[i].value)).toFixed(3);//保留三位小数
}
</script>
{/literal}
</head>

<body>
<div style="text-align:left">{include file="_ContentNav2.tpl"}</div>

<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post" style=" margin-left:5px;margin-top:5px">
<input type="hidden" name="rukuId" id="rukuId" value="{$arr_field_value.$pk}">
<input type="hidden" name="isBack" id="isBack" value="{$is_back|default:0}">
<input name="kind" type="hidden" id="kind" value="{$arr_field_value.kind}">
<fieldset>
<legend>入库基本信息</legend>

<table id="table_baseinfo">
	<tr>
		
		<td align="right">入库日期：</td>
		<td><input name="rukuDate" type="text" id="rukuDate"  value="{$arr_field_value.rukuDate|default:$smarty.now|date_format:'%Y-%m-%d'}" size="15" onClick="calendar()"></td>
		<td align="right">入库类型：</td>
		<td>
        <select name="kind" id="kind">
        <option value='0' {if $arr_field_value.kind==='0'}selected{/if}>正常入库</option>
        <option value='1' {if $arr_field_value.kind==='1'}selected{/if}>初始化</option>
        <!--<option value='9' {if $arr_field_value.rukuType==='9'}selected{/if}>其他入库</option>-->
        </select>
        </td>
		<td align="right">入库单号：</td>
		<td><input name="rukuNum1" type="text" disabled id="rukuNum1" value="{$arr_field_value.rukuNum}" size="15" style="border:0px;" >
        <input name="rukuNum" type="hidden" id="rukuNum" value="{$arr_field_value.rukuNum}" size="15" readonly="readonly" warning="请输入单号!" check="^\w+$" ></td>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right">供应商：</td>
          <td colspan="5" align="left">
            <select name="supplierId" id="supplierId">
          {webcontrol type='TmisOptions' model='Jichu_Supplier' selected=$arr_field_value.supplierId emptyText='选择供应商'}
            </select>
</td>
          <td align="right">&nbsp;</td>
          <td align="left">&nbsp;</td>
        </tr>
        <tr>
		<td align="right">备注：</td>
		<td colspan="7" align="left"><textarea name="rukuMemo" cols="50" rows="3" id="rukuMemo" style="width:65%">{$arr_field_value.memo}</textarea></td>
	  </tr>
</table>
</fieldset>
<fieldset style=" margin-top:10px">
<legend>入库详细信息</legend>
<div id="div_content" style="overflow:scroll;height:400px">
<table id='table_moreinfo' cellpadding="0" cellspacing="0">
	{include file='EditList.tpl'}
</table>
</div>
</fieldset>

<div id=heji>
	<div align="left" id="d1">合计:</div>
    <div align="right" id="d2"><span>数量:<span id='spanTotalCnt' style=" background-color:#F93; width:60px;">0</span></span><!--<span>金额:<span id='spanTotalMoney' style=" background-color:#F93; width:60px;">0</span></span>--></div>
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
<!-- <script language="javascript" src="Resource/Script/SetDivHeight.js"></script> -->