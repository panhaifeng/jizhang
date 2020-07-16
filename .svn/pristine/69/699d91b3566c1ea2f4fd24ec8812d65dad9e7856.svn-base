<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
<script src="Resource/dist/js/jquery.min.js"></script>
<script language="javascript" src="Resource/Script/Calendar/WdatePicker.js"></script>
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<script language="javascript" src="Resource/Script/Common.js"></script>
<script language="javascript" src="Resource/Script/ymPrompt/ymPrompt.js"></script>
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<script language="javascript"  src="Resource/Script/thickbox/thickbox.js"></script>
<script language="javascript"  src="Resource/Script/MyJqPlugin.js"></script>
<link href="Resource/Script/thickbox/thickbox.css" type="text/css" rel="stylesheet">
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<link href="Resource/Script/ymPrompt/skin/bluebar/ymPrompt.css" rel="stylesheet" type="text/css" />
<!--<link href="Resource/Css/Edit200.css" rel="stylesheet" type="text/css" />-->

<!-- Bootstrap -->
<link href="http://v3.bootcss.com/dist/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="Resource/dist/css/bootstrap.css">
{literal}
<style type="text/css">
 .lock {
	display: none;
	width:100%;
	height:100%;
	position: absolute;
	left:0px;
	background-color:#000;
	filter:alpha(opacity=5); /* IE */
	-moz-opacity:0.5; /* Moz + FF */
	opacity: 0.5;
	z-index:100;
 }
</style>
{/literal}
<script language="javascript">
var lock = {$smarty.get.lock|default:'""'};
{literal}
/*
	实时增加table行数
*/
var newRow;
var table;
$(function(){	
	ret2cab();
	if(lock){
		$(".lock").css("display",'inline');
	}
      $("[name='proCode[]']").showWin("proCode",applyPro,"kind=",$("#proKind"));
      $("#clientName").showWin("compName",function(json){
		//dump(json);return false;
		document.getElementById("clientName").value=json.compName;
		document.getElementById("clientId").value=json.id;
		if(json.traderId) {
			$('#traderId').val(json.traderId);
		}
	},"kind=1");
	$('input[name="memo[]"]').keydown(function(e){
		if(e.keyCode==13) {
			addOneRow();
			return false;
		}
		return true;
	});
	
	// table = document.getElementById('table_moreinfo');
	// var tr = table.rows[table.rows.length-1];
	// newRow = tr.cloneNode(true); 
	newRow = $('.trPro','#table_moreinfo').eq(0).clone(true);
	$("input[type=hidden],input[type=text]",newRow).val("");
	// debugger;
	 
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
			orderCode:"required",
			dateOrder:'required',
			clientId:"required",
			//traderId:"required",			
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
	 
	//如果是新增，自动产生5行记录
	if($('#orderId').val()=='') addRow();
	
	getTotal();
});

function addRow() {	

	for (var i=0;i<5;i++) {

		addOneRow();
	}	
}

function addOneRow(){
	var nt = newRow.clone(true);
	var trs = $('.trPro');
	trs.eq(trs.length-1).after(nt);
	$("input[name^='btnproCode']").addClass('btn frbtn btn-primary');
}

function delRow(obj){
	var tr = $(obj).parents("tr");	
	tr.remove();
}

function applyPro(ret,obj){
	// dump(ret);
 	var pos = $('input[name="proCode[]"]').index(obj);
 	//如果已经存在该产品就不用再添加了
 	// alert($("[name='proId[]'][value='"+ret.id+"']","#table_moreinfo").val());
 	//var proId = $("[name='proId[]'][value='"+ret.id+"']","#table_moreinfo");
 	// alert(proId.length);
 	// if(proId.length!=1){//$("[name='proId[]']")[pos].value==ret.id&&
 	// 	$("[name='proId[]']","#table_moreinfo")[pos].value='';
 	// 	obj.val("");
 	// 	return;
 	// }
	var proNames = document.getElementsByName('proName[]');
	var guiges = document.getElementsByName('guige[]');
	var units = document.getElementsByName('unit[]');
	var cnts = document.getElementsByName('cnt[]');
	var proKind1 = document.getElementsByName("proKind1");
	proKind1[pos].value=ret.kind;
	proNames[pos].value=ret.proName;
	guiges[pos].value=ret.guige;
	units[pos].value=ret.unit;
	cnts[pos].focus();
}

function cal(o){
	//修改本行金额
	//debugger;
	var name = o.name;
	var i =$('input[name='+name+']').index(o);
	$('input[name="money[]"]')[i].value= (parseFloat($('input[name="danjia[]"]')[i].value)||0)*(parseFloat($('input[name="cnt[]"]')[i].value)||0)
	
	getTotal();
	$('input[name="danjia[]"]')[i].value=(parseFloat($('input[name="danjia[]"]')[i].value)).toFixed(5);//保留三位小数
}
function getTotal() {
	debugger;
	var tCnt =0;
	var tMoney =0;
	var c=document.getElementById('spanTotalCnt');
	var m = document.getElementById('spanTotalMoney');
	var cnt = document.getElementsByName('cnt[]');
	var danjia = document.getElementsByName('danjia[]');
	
	for (var i=0;cnt[i];i++) {
		tCnt += (parseFloat(cnt[i].value)||0);
		tMoney+= parseFloat(cnt[i].value*danjia[i].value);
	}
	$(c).text(tCnt.toFixed(2));
	$(m).text(tMoney.toFixed(2));

}
function ProKindChange(obj){

      var rows = $("tr",$("#table_moreinfo"));
      // alert(rows.length);
      for(var i=0;rows[i];i++){
      	       if($("#proKind1",$(rows[i])).val()!=obj.value&&$("#proKind1",$(rows[i])).val()){

             	alert("商品类型与所选产品不符");
             	if(parseInt(obj.value)){
             		obj.value=0;
             	}else{
             		obj.value=1
             	}
             	return false;
             }     	
      }
      
}
$(document).ready(function() {
	
	$('#btnclientName').addClass('btn btns btn-primary');
	$("input[name^='btnproCode']").addClass('btn frbtn btn-primary');
	//$('#addtooltip').tooltip('show');
	$('#topssss').tooltip('top');
	
});
	// alert(typeof(window.subWin));
</script>
<style type="text/css">
#table_moreinfo .th{background-color:#D4E2F4;font-size:12px; height:25px;}
body{margin-left:5px; margin-top:5px;}
.btns { position:absolute; right:16px; top:1px; height:28px;}
.relative { position:relative;}
.frbtn {position:absolute; top:1px; right:0px; height:28px;z-index:1000;}
.pd5{ padding-left:5px;}
#heji { padding-left:20px; height:20px; line-height:20px; margin-bottom:5px;}
</style>
{/literal}
</head>


<body>
<!-- <input type="text" data-toggle="calendar"/>  -->
<div class="lock" style="height:100%"></div>
<div style="text-align:left;">{include file="_ContentNav2.tpl"}</div>

<form name="form1" id="form1" class="form-horizontal" action="{url controller=$smarty.get.controller action='save'}" method="post">

<input type="hidden" name="orderId" id="orderId" value="{$arr_field_value.$pk}">
<input type="hidden" name="isBack" id="isBack" value="{$is_back|default:0}">
<div class="panel panel-info">
    <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">订单基本信息</h3></div>
    <div class="panel-body">
    	<div class="row">
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="dateOrder" class="col-sm-4 control-label">订单日期:</label>
                    <div class="col-sm-8">
                      <input type="text" name="dateOrder" class="form-control" id="dateOrder" value="{$arr_field_value.dateOrder|default:$smarty.now|date_format:'%Y-%m-%d'}"  onClick="calendar()">
                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                <label for="traderId" class="col-sm-4 control-label">业务员:</label>
                <div class="col-sm-8">
                  <select name="traderId" id="traderId" class="form-control">{webcontrol type='Traderoptions' model='Jichu_Employ' selected=$arr_field_value.traderId emptyText='选择业务员'}</select>
                </div>
              </div>
            </div>
            <div class="col-xs-4">
                 <div class="form-group">
                    <label for="orderCode1" class="col-sm-4 control-label">订单号：</label>
                    <div class="col-sm-8">
                      <span class="tooltip-demo"><input name="orderCode1" data-toggle="tooltip" title="系统自动生成" type="text" class="form-control" id="orderCode1" value="{$arr_field_value.orderCode}" readonly ></span>
                <input id="orderCode" name="orderCode" type="hidden" value="{$arr_field_value.orderCode}">
                    </div>
                </div>
            </div>
    	</div>
        <div class="row">
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="daoKuanDate" class="col-sm-4 control-label">到款日期:</label>
                    <div class="col-sm-8">
                      <input type="text" name="daoKuanDate" class="form-control" id="daoKuanDate" value="{$arr_field_value.daoKuanDate}" size="15" onClick="calendar()">
                      
                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                <label for="clientOrderCode" class="col-sm-4 control-label">客户订单号:</label>
                <div class="col-sm-8">
                  <input name="clientOrderCode" class="form-control" type="text" id="clientOrderCode" value="{$arr_field_value.clientOrderCode}" size="15">
                  
                </div>
              </div>
            </div>
            <div class="col-xs-4">
                 <div class="form-group">
                    <label for="proKind" class="col-sm-4 control-label">产品类型：</label>
                    <div class="col-sm-8">
                      <select name="proKind" class="form-control" id="proKind" onChange="ProKindChange(this)">
                          <option value="0" {if $arr_field_value.proKind==0}selected{/if}>成品</option>
                          <option value="1" {if $arr_field_value.proKind==1}selected{/if}>半成品</option>
                      </select>
                    </div>
                </div>
            </div>
    	</div>
        <div class="row">
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="clientName" class="col-sm-4 control-label">客户名称:</label>
                    <div class="col-sm-8">
                      <input name="clientName" class="form-control"  type="text" id="clientName" value="{$arr_field_value.Client.compName}">
	    			  <input name="clientId" class="form-control"  type="hidden" id="clientId" value="{$arr_field_value.clientId}">
                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                <label for="xsType" class="col-sm-4 control-label">内/外销:</label>
                <div class="col-sm-8">
                	<select name="xsType" id="xsType" class="form-control" >
                      <option value="内销" {if $arr_field_value.xsType=='内销'}selected{/if}>内销</option>
                      <option value="外销" {if $arr_field_value.xsType=='外销'}selected{/if}>外销</option>
                    </select>
                </div>
              </div>
            </div>
            <div class="col-xs-12">
           		<div class="form-group">
                	<label for="orderMemo" class="col-sm-1 control-label"> 备注:</label>
                    <div class="col-sm-11">
                      <textarea class="form-control" rows="2" name="orderMemo" id="orderMemo" style=" margin-left:33px;width:90%;"></textarea>  
                    </div>
                </div>
           </div>
       </div>
       
    </div>
 </div>
 <fieldset>
 <div class="panel panel-info" id="div_content">
    <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">订单产品明细</h3></div>
    <div class="panel-body" style=" padding-bottom:0px; max-height:310px; overflow-y:scroll;">
    	<table class="table table-hover" id="table_moreinfo" style=" padding-bottom:5px;">
            <thead>
                <tr class="th">
                  <td>产品</td>
                  <td>名称</td>
                  <td>规格</td>
                  <!-- <td>订单规格</td> -->
                  <td>单位</td>
                  <td>数量</td>
                  <td>单价</td>
                  <td>金额</td>
                  <!--<td>交货日期</td>-->
                  <td>产品备注</td>
                  <td style="border-right:0px solid #cccccc; text-align:center;"><span class="tooltip-demo"><a data-toggle="tooltip" title="添加5行" href="javascript:;" onClick="javascript:addRow()"><span class="glyphicon glyphicon-plus-sign"></span></a></span></td>
                </tr>
            </thead>
        <tbody>
            <tr>
                {foreach from=$arr_field_value.Products item=item}
                <tr class='trPro'>
                  <td>
                  	  <div class="relative">
                          <input class="form-control" name="proCode[]" type="text" id="proCode[]" value="{$item.Pro.proCode}">
                          <input class="form-control" type="hidden" name="proKind1" id="proKind1" value="{$item.proKind}">
                          <input class="form-control" name="proId[]" type="hidden" id="proId[]" value="{$item.productId}">
                      </div>
                  </td>
                  <td><input class="form-control" name="proName[]" type="text" id="proName[]" value="{$item.proName}" size="10" readonly></td>
                  <td><input class="form-control" name="guige[]" type="text" id="guige[]" value="{$item.guige}" size="10" readonly></td>
                 <!--  <td><input name="guigeOrd[]" type="text" id="guigeOrd[]" value="{$item.guigeOrd}" size="10"></td> -->
                  <td><input class="form-control" name="unit[]" type="text" id="unit[]" value="{$item.unit}" size="10"></td>
                  <td><input class="form-control" name="cnt[]" type="text" id="cnt[]" size="8" value="{$item.cnt}" onChange="cal(this)"></td>
                  <td><input class="form-control" name="danjia[]" type="text" id="danjia[]" size="8" value="{$item.danjia}" onChange="cal(this)">                    
                  <!--<td><input name="danjia[]" type="text" id="danjia[]" size="8" value="{$item.danjia}"></td>
                  
                  <td><input name="money[]" type="text" id="money[]" size="8" value="{$item.cnt*$item.danjia}"></td>-->
                  <!--<td><input type="text" name="jiaohuoDate[]" id="jiaohuoDate[]" value="{$item.jiaohuoDate|default:$smarty.now|date_format:'%Y-%m-%d'}" onClick="calendar()">-->
                  
                  <td><input class="form-control" name="money[]" type="text" disabled id="money[]" value="{$item.danjia*$item.cnt}" size="8"></td>
                  <td><input class="form-control" name="memo[]" type="text" id="memo[]" size="8" value="{$item.memo}"></td>
                  <td><!-- input type="button" name="btnDel" id="btnDel" class="btn btn-sm btn-danger" value="删除" size="8" onClick="delRow(this)"-->
                  <a name="btnDel" id="btnDel" class="btn btn-sm btn-danger" href="javascript:;" onClick="delRow(this)"><span class='glyphicon glyphicon-remove'></span> 删除</a>
                  <input class="form-control" type="hidden" id="ifRemove[]" name="ifRemove[]" value="0">
                  <input class="form-control" name="id[]" type="hidden" id="id[]" value="{$item.id}"></td>
                </tr>
            {/foreach}
            </tr>
        </tbody>
        </table>
    </div>
    <div id="heji">
        <strong>合计:</strong> &nbsp;&nbsp;&nbsp;数量：<span id='spanTotalCnt' style=" background-color:#F93; width:60px; font-size:12px;">0</span>&nbsp;&nbsp;&nbsp;金额：<span id='spanTotalMoney' style=" background-color:#F93; width:60px; font-size:12px;">0</span>
        
	    </select></td>
		<td width="80"><div align="right"><font style="color:#666666">订单号：</font></div></td>
        <td width="105"><input name="orderCode1" type="text" disabled id="orderCode1" value="{$arr_field_value.orderCode}" size="15" readonly="readonly" style="border:0px;">
        <input id="orderCode" name="orderCode" type="hidden" value="{$arr_field_value.orderCode}">
        </td>
	</tr>
	<tr>
    	<td><div align="right">到款日期：</div></td>
    	<td><input type="text" name="daoKuanDate" id="daoKuanDate" value="{$arr_field_value.daoKuanDate}" size="15" onClick="calendar()"></td>
	  <td ><div align="right">客户订单号：</div></td>
	  <td ><input name="clientOrderCode" type="text" id="clientOrderCode" value="{$arr_field_value.clientOrderCode}" size="15"></td>
	  <td><div align="right">产品类型：</div></td>
	  
	  <td>
      <select name="proKind" id="proKind" onChange="ProKindChange(this)">
          <option value="0" {if $arr_field_value.proKind==0}selected{/if}>成品</option>
          <option value="1" {if $arr_field_value.proKind==1}selected{/if}>半成品</option>
      </select></td>
    </tr>
	<tr>
	  <td><div align="right">客户名称：</div></td>
	  <td><input name="clientName" type="text" id="clientName" value="{$arr_field_value.Client.compName}">
	    <input name="clientId" type="hidden" id="clientId" value="{$arr_field_value.clientId}"></td>
	  <td><div align="right">内/外销：</div></td>
	  <td>
      <select name="xsType" id="xsType" >
      <option value="内销" {if $arr_field_value.xsType=='内销'}selected{/if}>内销</option>
      <option value="外销" {if $arr_field_value.xsType=='外销'}selected{/if}>外销</option>
      </select>
      </td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
	  <td colspan="6">备注：
      <textarea name="orderMemo" id="orderMemo" style="width:78%;">{$arr_field_value.memo}</textarea></td>
    </tr>
</table>
</fieldset>
 
<fieldset style=" margin-top:10px">
<legend>订单产品明细</legend>
<div id="div_content" style="height:400px">

<table id='table_moreinfo'  cellpadding="0" cellspacing="0">
	<tr class="th">
	  <td>产品</td>
	  <td>名称</td>
	  <td>规格</td>
	  <!-- <td>订单规格</td> -->
	  <td>单位</td>
	  <td>数量</td>
	  <td>单价</td>
	  <td>金额</td>
	  <!--<td>交货日期</td>-->
	  <td>产品备注</td>
	  <td style="border-right:0px solid #cccccc;">操作&nbsp;<a href="#" onClick="javascript:addRow()">+5行</a></td>
    </tr>
	{foreach from=$arr_field_value.Products item=item}
		<tr class='trPro'>
		  <td>
			  <input name="proCode[]" type="text" id="proCode[]" value="{$item.Pro.proCode}">
			  <input type="hidden" name="proKind1" id="proKind1" value="{$item.proKind}">
		  <input name="proId[]" type="hidden" id="proId[]" value="{$item.productId}"></td>
		  <td><input name="proName[]" type="text" id="proName[]" value="{$item.proName}" size="10" readonly="readonly"></td>
		  <td><input name="guige[]" type="text" id="guige[]" value="{$item.guige}" size="10" readonly="readonly"></td>
		 <!--  <td><input name="guigeOrd[]" type="text" id="guigeOrd[]" value="{$item.guigeOrd}" size="10"></td> -->
		  <td><input name="unit[]" type="text" id="unit[]" value="{$item.unit}" size="10"></td>
		  <td><input name="cnt[]" type="text" id="cnt[]" size="8" value="{$item.cnt}" onChange="cal(this)"></td>
		  <td><input name="danjia[]" type="text" id="danjia[]" size="8" value="{$item.danjia}" onChange="cal(this)">                    
		  <!--<td><input name="danjia[]" type="text" id="danjia[]" size="8" value="{$item.danjia}"></td>
		  
		  <td><input name="money[]" type="text" id="money[]" size="8" value="{$item.cnt*$item.danjia}"></td>-->
		  <!--<td><input type="text" name="jiaohuoDate[]" id="jiaohuoDate[]" value="{$item.jiaohuoDate|default:$smarty.now|date_format:'%Y-%m-%d'}" onClick="calendar()">-->
	      
		  <td><input name="money[]" type="text" disabled id="money[]" value="{$item.danjia*$item.cnt}" size="8"></td>
		  <td><input name="memo[]" type="text" id="memo[]" size="8" value="{$item.memo}"></td>
		  <td><input type="button" name="btnDel" id="btnDel" value="删除" size="8" onClick="delRow(this)" class="button">
	      <input type="hidden" id="ifRemove[]" name="ifRemove[]" value="0">
	      <input name="id[]" type="hidden" id="id[]" value="{$item.id}"></td>
		</tr>
	{/foreach}
		
</table>
</div>
</fieldset>
 
<div id=heji>
	<div align="left" id="d1">合计:</div>
    <div align="right" id="d2"><span>数量:<span id='spanTotalCnt' style=" background-color:#F93; width:60px;">0</span></span><span>金额:<span id='spanTotalMoney' style=" background-color:#F93; width:60px;">0</span></span></div>
</div>		  
 
<table style="position:absolute; z-index:999;" align="center">
	<tr>
    	<td><input type="submit" id="Submit" name="Submit" value='保存并新增下一个' class="button"></td>
    	<td><input type="submit" id="Submit" name="Submit" value='保存' class="button"></td>
		<!-- <td><input type="reset" id="Reset" name="Reset" value='重置' class="button"></td> -->
    </tr>
</table>

<div class="form-group">
  <div class="col-sm-offset-2 col-sm-12">
      <input class="btn btn-primary" type="submit" id="Submit" name="Submit" value=' 保存 '>
    <input class="btn btn-default" type="submit" id="Submit" name="Submit" value='保存并新增下一个'>
      <input class="btn btn-default" type="reset" id="Reset" name="Reset" value=' 重置 '>
  </div>
</div>
<div style="clear:both;"></div>
</form>

<script src="http://v3.bootcss.com/dist/js/bootstrap.js"></script>
<script src="http://v3.bootcss.com/docs-assets/js/application.js"></script>
</body>
</html>
<!-- <script language="javascript" src="Resource/Script/SetDivHeight.js"></script> -->
