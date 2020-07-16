<script language='javascript'>
//建立数据集缓存，用来双击某行时提取数据
var _ds = {$arr_field_value|@json_encode};
//定义ondbclick事件
{literal}
$(function(){
	$('.trRow').dblclick(function(e){
		var pos = $('.trRow').index(this);
		//ds可能为对象，不是纯粹的array,所以这里不能直接使用_ds[pos]
		var i=0;
		for(var k in _ds) {
			if(typeof(_ds[k])=='function') continue;
			if(i==pos) {
				var obj = _ds[k];
				break;
			}
			i++;
		}
		if(window.opener.onSelect!=null) {
			window.opener.onSelect(obj);//return false;
			window.close();
			return;
		}
		if(window.parent.ymPrompt) {
			window.parent.ymPrompt.doHandler(obj,true);//return false;
			window.close();
			return;
		}
		if(window.parent.thickboxCallBack) {
			window.parent.tb_remove();
			window.parent.thickboxCallBack(obj,pos);
			window.close();
			return;
		}
		if(window.opener!=undefined) {
			window.opener.returnValue = obj;//alert(obj['orderCode']);
			window.close();
			return;
		}
		window.returnValue = obj;
		window.close();
		return;
	});
});

{/literal}
</script>