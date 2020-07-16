
{literal}
<script language='javascript'>
function afterRender(){
	//全选/反选
	$('#sel').click(function(){
			$('[name="editBtn[]"]').attr('checked',this.checked);
	});
	//保存按钮绑定事件
	$('#remove').click(function(){
		removeByAjax();
	});
}

function removeByAjax(){
	var id=[];
	$('[name="editBtn[]"]').each(function(){
			if(this.checked)id.push(this.value);							  
	});
	var str_id=id.join(',');
	if(str_id==''){
		alert('至少选择一条数据');return false;	
	}
	if(!confirm('确定删除数据吗？'))return false;
	
	var url="?controller=Mail&action=RemoveByAjax";

	$.getJSON(url,{id:str_id},function(json){
		if(json.success==false){
			alert(json.msg);return false;
		}
		else {
			window.parent.showMsg('操作成功');
			window.location.href=window.location.href;
		}
	});
}

</script>
{/literal}