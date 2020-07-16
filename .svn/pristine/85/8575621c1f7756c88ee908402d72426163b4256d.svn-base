<script language="javascript">
{literal}
$(function(){
    $('[name="productId[]"]').bind('onSel',function(event,ret){
        var tr = $(this).parents(".trRow");
	    $('[name="proCode"]',tr).val(ret.proCode);
	    $('[name="productId[]"]',tr).val(ret.id);
	    $('[name="proName[]"]',tr).val(ret.proName);
	    $('[name="guige[]"]',tr).val(ret.guige);
	    $('[name="menfu[]"]',tr).val(ret.menfu);
	    $('[name="kezhong[]"]',tr).val(ret.kezhong);
    });

    $('[name="colorId[]"]').bind('onSel',function(event,ret){
        var tr = $(this).parents(".trRow");

	    $('[name="colorId[]"]',tr).val(ret.id);
    });
});
{/literal}
</script>