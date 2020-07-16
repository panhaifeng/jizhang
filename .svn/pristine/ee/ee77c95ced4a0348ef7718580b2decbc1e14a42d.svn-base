{literal}
<script language="javascript">
$(function(){
	var clientId=document.getElementById('clientId');
	if(clientId != 'undefined' && clientId != '' && clientId != null){
		getClientBytrader();
		$('#traderId').change(function(){
				getClientBytrader();
		})
	}
});
 //通过业务员查找客户
function getClientBytrader(){
		var o=document.getElementById('clientId');
		var clientId=$('#clientId').val();
		//alert(clientId);
		var traderId=$('#traderId').val();
		//if(traderId=='')return false;
		var url="?controller=Yixiang_Client&action=GetJsonByTraderId";
		var param={traderId:traderId,'all':'n'};
		$.getJSON(url,param,function(json){
				//删除所有客户信息
				$('#clientId option').remove();
				$('#clientId optgroup').remove();
				$('#clientId').append(json);
				//选中原来选中的客户（如果存在）
				$('#clientId option[value='+clientId+']').attr('selected',true);
		});
}
</script>
{/literal}