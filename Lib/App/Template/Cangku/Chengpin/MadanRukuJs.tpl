{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
<script language="javascript">
{literal}
$(function(){
	/**
	* 码单按钮单机事件
	* 打开码单入库界面
	*/
	$('[name="btnMadan"]').live('click',function(){
		
		//url地址
		var url="?controller=Cangku_Chengpin_Ruku&action=RkMadan";
		var trRow = $(this).parents(".trRow");
		// var ruku2proId = $('[name="id[]"]',trRow).val();
		// url+="&ruku2proId="+ruku2proId;
		//弹出窗口，设置宽度高度
		var width = screen.width;
		var height = screen.height;
		width = width>1300?1300:width;
		height = height>640?640:height;
		//获取码单选择信息
		// var madan = $('[name="Madan[]"]',trRow).val();
		// var ret = window.showModalDialog(url,{data:$.toJSON(madan)},'dialogWidth:'+width+'px;dialogHeight:'+height+'px;');
		//取得隐藏字段的值，保存的是码单信息
		var madan = $('[name="Madan[]"]',trRow).val();
		if(madan=='')madan='""';//第一次进入的时候，隐藏字段不能为空
		var ret = window.showModalDialog(url,{data:madan},'dialogWidth:'+width+'px;dialogHeight:'+height+'px;');
	    if(!ret){
	    	//window.returnValue取得返回值
	    	ret=window.returnValue;
	    	//第二次如果没有返回值的话，会取上一次的值，因此设置window.returnValue=null
	    	window.returnValue=null;
	    }
	    if(!ret) return;
		if(ret.ok!=1)return false;
		// debugger;
		$('[name="cntJian[]"]',trRow).val(ret.cntJian);
		$('[name="cnt[]"]',trRow).val(ret.cnt);
		$('[name="cntM[]"]',trRow).val(ret.cntM);
		$('[name="cntMadan[]"]',trRow).val(ret.cntMadan);
		$('[name="Madan[]"]',trRow).val(ret.jsonStr);
	});
});
{/literal}
</script>