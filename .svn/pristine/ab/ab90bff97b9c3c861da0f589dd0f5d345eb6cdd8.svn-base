{literal}
<script language="javascript">
$(function(){
    $('[name="cnt"],[name="danjia"],[name="money"]').change(function(){
        var danjia =parseFloat($('#danjia').val())||0;
        var cnt = parseFloat($('#cnt').val())||0;
       var money =(cnt*danjia).toFixed(2);
       $('#money').val(money);
    });

	$('[name="waixie2proId"]').bind('onSel',function(event,ret){
		// debugger;
		// $(this).siblings('#rukuCode').val(ret.rukuCode);//产品编码
	 	// $(this).siblings('#ord2proId').val(ret.id);//从表id
	 	// $('#textBox').val(ret.orderCode);
	 	// $('#ord2proId').val(ret.ord2proId);
	    $('#proName').val(ret.proName);//产品名字
	    $('#guige').val(ret.guige);//产品规格
	    $('#jiagonghu').val(ret.compName);
	    $('#cnt').val(ret.cntSend);
	});
});
</script>
{/literal}