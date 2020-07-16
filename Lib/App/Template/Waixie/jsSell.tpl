{literal}
<script language="javascript">
$(function(){	

	$('[name="ord2proId"]').bind('onSel',function(event,ret){
		// debugger;
		// $(this).siblings('#rukuCode').val(ret.rukuCode);//产品编码
	 	// $(this).siblings('#ord2proId').val(ret.id);//从表id
	 	// $('#textBox').val(ret.orderCode);
	 	// $('#ord2proId').val(ret.ord2proId);
	    $('#proName').val(ret.proName);//产品名字
	    $('#guige').val(ret.guige);//产品规格
	    $('#pihao').val(ret.pihao);//批号
        $('#color').val(ret.color);//颜色
        $('#productId').val(ret.productId);//产品ID
       
	    return;
	});
});
</script>
{/literal}