{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
{literal}
<script language="javascript">
$(function(){
	$('[name="plan2hzlId[]"]').bind('onSel',function(event,ret){
		var currentTr = $(this).parents('.trRow');
		$('[name="proName[]"]',currentTr).val(ret.proName);
		$('[name="guige[]"]',currentTr).val(ret.guige);
		$('[name="color[]"]',currentTr).val(ret.color);
		$('[name="planId[]"]',currentTr).val(ret.planId);
		$('[name="orderId[]"]',currentTr).val(ret.orderId);
		$('[name="ord2proId[]"]',currentTr).val(ret.order2proId);
		$('[name="productId[]"]',currentTr).val(ret.productId);
	});

	$('[name="plan2hzlId[]"]').bind('beforeOpen',function(event,url){
		var jiagonghuId = $('#jiagonghuId').val();
		var gongxuId = $('#gongxuId').val();

		if(jiagonghuId>0){
			url+="&jiagonghuId="+jiagonghuId;
		}
		if(gongxuId>0){
			url+="&gongxuId="+gongxuId;
		}
		return url;
	});

	// 订单选择返回填充方法
	// $('[name="ord2proId[]"]').bind('onSel',function(event,ret){
	// 	var currentTr = $(this).parents('.trRow');

	// 	var div = $('[name="productId[]"]',currentTr).parents('.clsPop');
	// 	$('[name="textBox"]',div).val(ret.proCode);
	// 	$('[name="proName[]"]',currentTr).val(ret.proName);
	// 	$('[name="guige[]"]',currentTr).val(ret.guige);
	// 	$('[name="color[]"]',currentTr).val(ret.color);
	// 	$('[name="menfu[]"]',currentTr).val(ret.menfu);
	// 	$('[name="kezhong[]"]',currentTr).val(ret.kezhong);
	// 	$('[name="orderId[]"]',currentTr).val(ret.orderId);
	// 	$('[name="ord2proId[]"]',currentTr).val(ret.ord2proId);
	// 	$('[name="productId[]"]',currentTr).val(ret.productId);
	// });

	// 产品选择后返回填充
	$('[name="productId[]"]').bind('onSel',function(event,ret){
		// debugger;
	    var tr = $(this).parents(".trRow");
	    $('[name="proCode"]',tr).val(ret.proCode);
	    $('[name="productId[]"]',tr).val(ret.id);
	    $('[name="proName[]"]',tr).val(ret.proName);
	    $('[name="guige[]"]',tr).val(ret.guige);
	    $('[name="menfu[]"]',tr).val(ret.menfu);
	    $('[name="kezhong[]"]',tr).val(ret.kezhong);
	    $('[name="color[]"]',tr).val(ret.color);
	    $('[name="cnt[]"]',tr).focus();

	    return;
	});
	//设置方向键
	var dir=['cntJian[]','cnt[]','dengjidanjia[]','memo[]'];
	direct({
		cellname:dir,
		selectedorfocus:true,
		optionfocus:true
	});
    
    $('[name="ord2proId[]"]').bind('onSel',function(event,ret){
 //    	if (ret.length==undefined) {
	// 	var tr = $(this).parents('.trRow');
	// 	$('[name="planId[]"]',tr).val(ret.planId);
	// 	$('[name="productId[]"]',tr).val(ret.productId);
	// 	$('[name="proCode"]',tr).val(ret.proCode);
	// 	$('[name="proName[]"]',tr).val(ret.proName);
	// 	$('[name="guige[]"]',tr).val(ret.guige);
	// 	$('[name="color[]"]',tr).val(ret.color);
	// 	if(ret.ganghao!=0) $('[name="pihao[]"]',tr).val(ret.ganghao);
	// }else{
		var pos=$('[name="ord2proId[]"]').index(this);
		var trs = $('.trRow');
		var trTpl = trs.eq(pos).clone(true);
		//$('input,select',trTpl).val('');
		var parent = trs.eq(pos).parent();
		for(var i=pos;trs[i];i++) {
			trs.eq(i).remove();
		}
   		for(var i=0;i<ret.length;i++){
   		  var trNew = trTpl.clone(true);
		  parent.append(trNew);
		  var div = $('[name="productId[]"]',trNew).parents('.clsPop');
		  $('[name="textBox"]',div).val(ret[i].proCode);
		  var divCode = $('[name="ord2proId[]"]',trNew).parents('.clsPop');
		  $('[name="textBox"]',divCode).val(ret[i].orderCode);
          $('[name="productId[]"]',trNew).val(ret[i].productId);  
          $('[name="proName[]"]',trNew).val(ret[i].proName);  
          $('[name="guige[]"]',trNew).val(ret[i].guige);
          $('[name="menfu[]"]',trNew).val(ret[i].menfu);
          $('[name="kezhong[]"]',trNew).val(ret[i].kezhong);
          $('[name="color[]"]',trNew).val(ret[i].color);
          $('[name="cnt[]"]',trNew).val(ret[i].cnt);
          $('[name="orderId[]"]',trNew).val(ret[i].orderId);
          $('[name="ord2proId[]"]',trNew).val(ret[i].id);
		// }

	}
    	//onSelect(event,ret);
    });
    function onSelect(index,ret){
        var trs = $('.trRow')
            ,len = trs.length
            ,trTpl = trs.eq(len-1).clone(true)
            ,parent = trs.eq(0).parent();
        
            $('[name="textBox"]',trTpl).val('');
            $('[name="ord2proId[]"]',trTpl).val('');
            $('[name="productId[]"]',trTpl).val('');
            $('[name="proName[]"]',trTpl).val('');
            $('[name="guige[]"]',trTpl).val('');
            $('[name="orderId[]"]',trTpl).val('');
            $('[name="menfu[]"]',trTpl).val('');
            $('[name="kezhong[]"]',trTpl).val('');
            $('[name="color[]"]',trTpl).val('');
            $('[name="cnt[]"]',trTpl).val('');
            // alert(index);
        
            for(var i=0;trs[i];i++)
            {
              //2017-10-11 by zhang
              var pro = $('[name="productId[]"]').val();
              // alert(pro);
              if(pro){
                  trs.eq(index).remove();
              }else{
                  trs.eq(i).remove();
              }
            }

            //将选中订单的明细形成新行插入    
            //console.log(ret);
            $.each(ret, function(i,vv){
	          var newTr = trTpl.clone(true);
			  var div = $('[name="productId[]"]',newTr).parents('.clsPop');
			  $('[name="textBox"]',div).val(vv.proCode);
			  var divCode = $('[name="ord2proId[]"]',newTr).parents('.clsPop');
			  $('[name="textBox"]',divCode).val(vv.orderCode);
              $('[name="productId[]"]',newTr).val(vv.productId);  
              $('[name="proName[]"]',newTr).val(vv.proName);  
              $('[name="guige[]"]',newTr).val(vv.guige);
              $('[name="menfu[]"]',newTr).val(vv.menfu);
              $('[name="kezhong[]"]',newTr).val(vv.kezhong);
              $('[name="color[]"]',newTr).val(vv.color);
              $('[name="cnt[]"]',newTr).val(vv.cnt);
              $('[name="orderId[]"]',newTr).val(vv.orderId);
              $('[name="ord2proId[]"]',newTr).val(vv.id);
              // $('[name="orderCode"]',newTr).val(vv.orderCode); 
              parent.append(newTr);
            });
    };

    $("[name='plan2touliaoId[]']").bind('onSel',function(event,ret){
	
	});

	/**
	* 码单按钮单机事件
	* 打开码单出库界面
	*/
	$('[name="btnMadan"]').live('click',function(){
		//url地址
		var url="?controller=Shengchan_PiJian_Chanliang&action=ViewMadan";
		var trRow = $(this).parents(".trRow");
		var jghFromId = $('#jghFromId').val();//取得发出加工户
		var jiagonghuId = $('#jiagonghuId').val();//取得后整理厂
		var planId = $('[name="planId[]"]',trRow).val();
		var ruku2proId = $('[name="ruku2proId[]"]',trRow).val();
		var chuku2proId = $('[name="id[]"]',trRow).val();
		var seCode = $('[name="seCode[]"]',trRow).val();
		var chanliangId = $('[name="chanliangId[]"]',trRow).val();
		url+="&chuku2proId="+chuku2proId;
		url+="&jghFromId="+jghFromId;
		url+="&jiagonghuId="+jiagonghuId;
		url+="&chanliangId="+chanliangId;
		url+="&seCode="+seCode;
		// if(!planId>0){
		// 	alert('无法查找到对应的码单信息，请刷新后重新操作');return;
		// }
		//弹出窗口，设置宽度高度
		var width = screen.width;
		var height = screen.height;
		width = width>1000?1000:width;
		height = height>640?640:height;
		//获取码单选择信息
		var madan = $('[name="Madan[]"]',trRow).val();
		window.returnValue=null;
		var ret = window.showModalDialog(url,{data:$.toJSON(madan)},'dialogWidth:'+width+'px;dialogHeight:'+height+'px;');

	    if(!ret){
	    	ret=window.returnValue;
	    }
	    if(!ret) return;
		if(ret.ok!=1)return false;
		// dump(ret);
		$('[name="cntJian[]"]',trRow).val(ret.cntJian);
		$('[name="cnt[]"]',trRow).val(ret.cnt);
		$('[name="Madan[]"]',trRow).val(ret.data);
		$('[name="cnt[]"]',trRow).change();
	});
});
</script>
{/literal}