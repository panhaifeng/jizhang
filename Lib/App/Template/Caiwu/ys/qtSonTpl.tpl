{literal}
<script language="javascript">
$(function(){
	$('#_money,#zhekouMoney').change(function(){
		var _money = parseFloat($('#_money').val())||0;
		var zhekouMoney = parseFloat($('#zhekouMoney').val())||0;
		var money = (_money-zhekouMoney).toFixed(2);
		$('#money').val(money);
	});
	// debugger;

	//自动计算金额
	// $('#money').change(function(){
	// 	var s=$('#money').val();//保存初值
	// 	var cnt=parseFloat($('#cnt').val())||0;
	// 	var danjia=parseFloat($('#danjia').val())||0;
	// 	var money =(cnt*danjia).toFixed(2);
	// 	if(money=='0.00'){
	// 		$('#money').val(s);
	// 	}else{
	// 		$('#money').val(money);
	// 	}
		
	// })

    $('[name="cntOrg"],[name="danjia"]').change(function(){
        var danjia =parseFloat($('#danjia').val())||0;
        var cntOrg = parseFloat($('#cntOrg').val())||0;
        $('[name="money"]').val((danjia*cntOrg).toFixed(2));
        return;
    });

	$("[name='chuku2proId[]']").bind('onSel',function(event,ret){
		if (ret.length==undefined) {
			var tr = $(this).parents(".trRow");
		    $('[name="qitaMemo[]"]',tr).val(ret.qitaMemo);
		    $('[name="clientName"]',tr).val(ret.compName);
			$('[name="clientId[]"]',tr).val(ret.clientId);
		    $('[name="chukuDate[]"]',tr).val(ret.chukuDate);
		    $('[name="cnt[]"]',tr).val(ret.cnt);
		    $('[name="cntOrg[]"]',tr).val(ret.cntOrg);
		    $('[name="danjia[]"]',tr).val(ret.danjia);
	        $('[name="cntJian[]"]',tr).val(ret.cntJian);
	        $('[name="songhuoCode[]"]',tr).val(ret.songhuoCode);
	        $('[name="money[]"]',tr).val((ret.danjia*ret.cntOrg).toFixed(2));
		    if(!ret.unit){
		      $('[name="unit[]"]',tr).val('公斤');
		    }
		    else{
		      $('[name="unit[]"]',tr).val(ret.unit);
		    }
		    $('[name="danjia[]"]',tr).val(ret.danjia);
		    $('#_money').change();
		    $('#cnt').change();
			$('#cntOrg').change();
		    if(!ret.huilv){
			      $('[name="huilv[]"]',tr).val('1');
			    }
			else{
			    $('[name="huilv[]"]',tr).val(ret.huilv);
			}
		    $('[name="memo[]"]',tr).val(ret.memo);
		    if(!ret.bizhong){
		      $('[name="bizhong[]"]',tr).val('RMB');
		    }
		    else{
		      $('[name="bizhong[]"]',tr).val(ret.bizhong);
		    }
		    $('[name="gzType[]"]',tr).val('1');
		    $('[name="productId[]"]',tr).val(ret.productId);
			$('[name="chukuId[]"]',tr).val(ret.chukuId);
			$('[name="orderCode[]"]',tr).val(ret.orderCode);
			$('[name="ganghao[]"]',tr).val(ret.ganghao);
			$('[name="orderId[]"]',tr).val(ret.orderId);
			$('[name="ord2proId[]"]',tr).val(ret.ord2proId);
			$('[name="kind[]"]',tr).val(ret.kind);
		}else{
			var pos=$('[name="chuku2proId[]"]').index(this);
			var trs = $('.trRow');
			var trTpl = trs.eq(pos).clone(true);
			var parent = trs.eq(pos).parent();
			for(var i=pos;trs[i];i++) {
				trs.eq(i).remove();
			}
	   		for(var i=0;i<ret.length;i++){
	   			var trNew = trTpl.clone(true);
				parent.append(trNew);
				$('[name="qitaMemo[]"]',trNew).val(ret[i].qitaMemo);
				$('[name="clientId[]"]',trNew).val(ret[i].clientId);
				$('[name="clientName"]',trNew).val(ret[i].compName);
			    $('[name="chukuDate[]"]',trNew).val(ret[i].chukuDate);
			    $('[name="cntOrg[]"]',trNew).val(ret[i].cntOrg);
		        $('[name="cntJian[]"]',trNew).val(ret[i].cntJian);
		        $('[name="songhuoCode[]"]',trNew).val(ret[i].songhuoCode);
		        $('[name="money[]"]',trNew).val((ret[i].danjia*ret[i].cntOrg).toFixed(2));
			    if(!ret[i].unit){
			      $('[name="unit[]"]',trNew).val('公斤');
			    }
			    else{
			      $('[name="unit[]"]',trNew).val(ret[i].unit);
			    }
			    $('[name="danjia[]"]',trNew).val(ret[i].danjia);
			    $('#_money').change();
			    $('#cnt').change();
				$('#cntOrg').change();
			    if(!ret[i].huilv){
			      $('[name="huilv[]"]',trNew).val('1');
			    }
				else{
				    $('[name="huilv[]"]',trNew).val(ret[i].huilv);
				}
			    $('[name="memo[]"]',trNew).val(ret[i].memo);
			    if(!ret[i].bizhong){
			      $('[name="bizhong[]"]',trNew).val('RMB');
			    }
			    else{
			      $('[name="bizhong[]"]',trNew).val(ret[i].bizhong);
			    }
			    $('[name="gzType[]"]',trNew).val('1');
			    $('[name="productId[]"]',trNew).val(ret[i].productId);
				$('[name="chukuId[]"]',trNew).val(ret[i].chukuId);
				$('[name="orderCode[]"]',trNew).val(ret[i].orderCode);
				$('[name="ganghao[]"]',trNew).val(ret[i].ganghao);
				$('[name="orderId[]"]',trNew).val(ret[i].orderId);
				$('[name="ord2proId[]"]',trNew).val(ret[i].ord2proId);
				$('[name="kind[]"]',trNew).val(ret[i].kind);
			    $('[name="chuku2proId[]"]',trNew).val(ret[i].id);
			    $('#textBox',trNew).val(ret[i].chukuCode);
			}

		}
	});

	$('[name="clientId[]"]').bind('onSel',function(event,ret){
		var tr = $(this).parents(".trRow");
        $('[name="clientId[]"]',tr).val(ret.id);
		$('[name="clientName[]"]',tr).val(ret.compName);
    });

	
});
</script>
{/literal}