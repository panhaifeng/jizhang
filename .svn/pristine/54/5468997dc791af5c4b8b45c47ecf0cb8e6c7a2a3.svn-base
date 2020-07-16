{literal}
<script language="javascript">
$(function(){
	$('input[name="danjia[]"]').keydown(function(e){
						var objs = document.getElementsByName(this.name);
						var p = $(objs).index(this);
						//处理键盘事件
						if(e.keyCode==38){
							//上
							//如果是select的向下，需要屏蔽
							if(p<1)return false;
							if(objs[p-1]) objs[p-1].focus();
						}else if(e.keyCode==40 || e.keyCode==13){
							//下，回车
							//如果是select的向下，需要屏蔽
							if(p+2>objs.length)return false;
							if(objs[p+1]) objs[p+1].focus();
							return false;
						}		
	});
});
//统一交付
function kp(){	
	//debugger;
	var id=new Array();
		var cks = $("[name='sel[]']:checked");
	if(!cks.length) {
		alert('请选择要交付的记录'); 
		return false;
	}
		cks.each(function(){
			id.push($(this).val());
		});
		id = id.join(',',id);

    var url = '?controller=Shouzhiyang_Chanliang&action=SetIsOver&fromAction=ShouyangOver&id='+id;
	window.location.href=url;
}

function editDanjia(o,id){
	//id 不正确，不修改
	if(!id>0)return false;
	if(isNaN(parseFloat(o.value)) && o.value!=''){
		//o.value='';
		return false;
	}
	var url="?controller=Shouzhiyang_Chanliang&action=SavehsDanjiaByAjax";
	var param={id:id,danjia:o.value};
	$.getJSON(url,param,function(json){
		if(json.success===false)alert(json.msg);
		else window.parent.showMsg('保存成功');
	});
}

//编辑交付说明
function editShuoming(o,planId){
	if(!planId>0)return false;
	// if(o.value=='')
	var url="?controller=Trade_Order&action=SaveBeizhuByAjax";
	var param={
		id:planId,
		memo:o.value
	};
	$.getJSON(url,param,function(json){
		if(json.success===false)alert(json.msg);
		else window.parent.showMsg('保存成功');
	})
}

function editDateJiaoqi(o,id){
	//id 不正确，不修改
	if(!id>0)return false;
	var url="?controller=Shouzhiyang_Chanliang&action=SaveJiaoqiByAjax";
	var param={id:id,dateJiaoqi:o.value};
	$.getJSON(url,param,function(json){
		if(json.success===false)alert(json.msg);
		else window.parent.showMsg('保存成功');
	});
}
</script>
{/literal}