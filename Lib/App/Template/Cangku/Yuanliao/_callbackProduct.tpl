{literal}
<script language="javascript">
$(function(){
	//设置方向键,方法一包装在jquery 中
	var dir=['ganghao[]','dengji[]','cntJian[]','cnt[]','jiagonghuId[]','memo[]'];
	//*******direct(true)表示聚焦到下一个焦点时候，会选中里面的内容,false或没有参数则表示不选中
	direct({
		cellname:dir,
		selectedorfocus:true,
		optionfocus:true
	});

	//对控件的回调函数进行定义
	//ret为选中对象
	//为控件绑定一个自定义的事件,注意参数的写法
	$('[name="productId[]"]').bind('onSel',function(event,ret){
		// debugger;
	    var tr = $(this).parents(".trRow");
	    $('[name="proCode"]',tr).val(ret.proCode);
	    $('[name="productId[]"]',tr).val(ret.id);
	    $('[name="proName[]"]',tr).val(ret.proName);
	    $('[name="guige[]"]',tr).val(ret.guige);
	    $('[name="menfu[]"]',tr).val(ret.menfu);
	    $('[name="kezhong[]"]',tr).val(ret.kezhong);
	    //alert(ret.color);
	    $('[name="color[]"]',tr).val(ret.color);
	    if(!ret.unit){
	      $('[name="unit[]"]',tr).val('吨');
	    }
	    else{
	      $('[name="unit[]"]',tr).val(ret.unit);
	    }
	    $('[name="cnt[]"]',tr).focus();

	    // if(onSelProduct) onSelProduct(this);
	    return;
	});
	$('[name="supplierId"]').bind('onSel',function(event,ret){

    });


    $('[name="productId[]"]').bind('beforeOpen',function(event,url){
		var supplierId = $('#supplierId').val();
		if(supplierId>0){
			url+="&supplierId="+supplierId;
		}
		return url;
	});

});


</script>
{/literal}