{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
<script language="javascript">
{literal}
$(function(){
	/**
	* 码单按钮单机事件
	* 打开码单出库界面
	*/
	$('[name="btnMadan"]').live('click',function(){
		//url地址
		var url="?controller=Cangku_Chengpin_CkWithMadan&action=ViewMadan";
		var trRow = $(this).parents(".trRow");
		var ruku2proId = $('[name="ruku2proId[]"]',trRow).val();
		var chuku2proId = $('[name="id[]"]',trRow).val();
		url+="&ruku2proId="+ruku2proId;
		url+="&chuku2proId="+chuku2proId;
		if(!ruku2proId>0){
			alert('无法查找到对应的码单信息，请刷新后重新操作');return;
		}
		//弹出窗口，设置宽度高度
		var width = screen.width;
		var height = screen.height;
		width = width>1300?1300:width;
		height = height>640?640:height;
		//获取码单选择信息
		var madan = $('[name="Madan[]"]',trRow).val();//存放码单id 与 number
		var ret = window.showModalDialog(url,{data:$.toJSON(madan)},'dialogWidth:'+width+'px;dialogHeight:'+height+'px;');

	    if(!ret) ret=window.returnValue;
	    if(!ret) return;
		if(ret.ok!=1)return false;
		// dump(ret);
		$('[name="cntJian[]"]',trRow).val(ret.cntJian);
		$('[name="cnt[]"]',trRow).val(ret.cnt);
		$('[name="cntM[]"]',trRow).val(ret.cntM);
		$('[name="cntMadan[]"]',trRow).val(ret.cntMadan);
		$('[name="Madan[]"]',trRow).val(ret.data);
	});
});
{/literal}
</script>