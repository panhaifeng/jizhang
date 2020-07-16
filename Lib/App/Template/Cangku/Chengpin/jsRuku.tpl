<script language="javascript">
var _jiagonghuId = '{$jiagonghuId}';
{literal}
$(function(){
	//设置方向键,方法一包装在jquery 中
	var dir=['ganghao[]','dengji[]','cntJian[]','cnt[]','cntM[]','jiagonghuId[]','memo[]'];
	//*******direct(true)表示聚焦到下一个焦点时候，会选中里面的内容,false或没有参数则表示不选中
	direct({
		cellname:dir,
		selectedorfocus:true,
		optionfocus:true
	});
	// $('#_money,#zhekouMoney').change(function(){
	// 	var _money = parseFloat($('#_money').val())||0;
	// 	var zhekouMoney = parseFloat($('#zhekouMoney').val())||0;
	// 	var money = (_money-zhekouMoney).toFixed(2);
	// 	$('#money').val(money);
	// });
	// debugger;
	//因为需要使用ret作为毁掉函数的参数，所以需要使用bind,
	//选择订单后，明细栏中显示订单明细情况
	$('[name="orderId"]').bind('onSel',function(event,ret){

		//先删除id='' && 数量=''的行，
		var trs = $('.trRow');
		var len = trs.length;
		var tpl = trs.eq(0).clone(true);
		var pNode = trs.parent();
		// debugger;
		for(var i=0;trs[i];i++) {
			if($('[name="id[]"]',trs[i]).val()!='') continue;
			if($('[name="cnt[]"]',trs[i]).val()!='') continue;
			trs.eq(i).remove();
		}

		//插入订单明细的情况
		var url='?controller=trade_order&action=GetMinxiByOrderId';
		var param={'orderId':this.value};
		// debugger;
		$.post(url,param,function(ret){
			// debugger;
			if(!ret.success) {
				alert('服务器端错误');
				return;
			}
			var json = ret.order.Products;
			if(!json) {
				alert('未发现数据集');
				return;
			}
			for(var i=0;json[i];i++) {
				var nt = tpl.clone(true);
				$('input,select',nt).val('');

				//为控件赋值
				$('[name="textBox"]',nt).val(json[i].proCode);
				$('[name="proName[]"]',nt).val(json[i].proName);
				$('[name="guige[]"]',nt).val(json[i].guige);
				$('[name="color[]"]',nt).val(json[i].color);
				$('[name="menfu[]"]',nt).val(json[i].menfu);
				$('[name="kezhong[]"]',nt).val(json[i].kezhong);
				$('[name="productId[]"]',nt).val(json[i].productId);
				$('[name="ord2proId[]"]',nt).val(json[i].ord2proId);
				$('[name="dengji[]"]',nt).val('一等品');
				//$('[name="jiagonghuId[]"]',nt).val(_jiagonghuId);
				// $('proCode',nt).val(json[i].proCode);
				// $('proCode',nt).val(json[i].proCode);
				pNode.append(nt);
			}
			tpl = null;
			return;
		},'json');

	});
    $('[name="productId[]"]').bind('onSel',function(event,ret){
        var tr = $(this).parents(".trRow");
	    $('[name="proCode"]',tr).val(ret.proCode);
	    $('[name="productId[]"]',tr).val(ret.id);
	    $('[name="proName[]"]',tr).val(ret.proName);
	    $('[name="guige[]"]',tr).val(ret.guige);
	    $('[name="menfu[]"]',tr).val(ret.menfu);
	    $('[name="kezhong[]"]',tr).val(ret.kezhong);
	    //alert(ret.color);
	    $('[name="color[]"]',tr).val(ret.color);
    });
});
</script>
{/literal}