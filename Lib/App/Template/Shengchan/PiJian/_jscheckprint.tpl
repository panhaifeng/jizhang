
<script language='javascript'>

var arrInfo = {$arrInfo|@json_encode};
{literal}
function fnPrint() {
	
	var ck = $('[name="ck"]:checked');


	//将所有的选中的id用,连接后传递
	var id=[];
	ck.each(function(i){
		id.push(this.value);
	});
	if(id.length>31){
		// alert('打印个数超出限制，请不要选择超过30条记录！');
		// return false;
	}
	var url="?controller=Shengchan_PiJian_Plan&action=Print&ids="+id.join(',')+"&temp=biaoqian&ord2proId="+arrInfo.ord2proId;
	// window.location.href=url;
	window.open(url);
}
$(function(){
	$('#ckAll').toggle(function(){
		$('[name="ck"]').attr('checked',true);
	},function(){
		$('[name="ck"]').attr('checked',false);
	});
});
</script>
{/literal}