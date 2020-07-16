<script language="javascript">
{literal}
//ret为弹出选择的对象
function onSelOrder(obj,ret) {
	//客户关联改变
	// alert(ret.compName);
	// alert(ret.clientId);
	$('#clientId').val(ret.clientId);
	$('#clientName').val(ret.compName);
	// alert('选择定单后，先删除所有空行,然后将订单明细的信息作为新行插入,注意，请在Cangku/Yuanliao/jsXsck.tpl中编写');
	//判断下面表格中的id[]，如果为空删除，否则保留(主要是为了考虑修改时，不能把已保存过的明细直接删除)
	//将选中订单的明细形成新行插入
}
{/literal}
</script>