{literal}
<script language="javascript">
function setEmploy(o) {	
		var isCaiyang=0;
		if(o.checked==true) isCaiyang=1;
		else isCaiyang=0;
		var url = '?controller=Sample_Caiyang&action=SetEmploy';		
		var param={id:o.value,isCaiyang:isCaiyang};
		$.getJSON(url,param,function(json){
			if(json.success===false) {
				if(o.checked==true)o.checked=false;
				else o.checked=true;
				window.parent.showMsg('保存失败!');
			}else
			{				
				window.parent.showMsg('保存成功!');
			}
		});
}
</script>
{/literal}

