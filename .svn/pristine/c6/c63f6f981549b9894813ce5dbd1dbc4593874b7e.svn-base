<script language='javascript'>
//建立数据集缓存，用来双击某行时提取数据
var _ds = {$arr_field_value|@json_encode};
//定义ondbclick事件
{literal}
$(function(){
	var _p = '';
	_p = window.parent.getdata;
	
	var pArr = _p.split(',');
	// console.log(pArr);
	
	pArr = pArr.unique();
	//已经选中的码单id复选框选中
	for(var k in pArr){
		if(k>=0){
			$('.chk_'+pArr[k]).prop("checked",true);
			$('.chk_'+pArr[k]).prop("checked",this.checked);
		}			
	}	

	//全选或者反选
	$("#_chkAll").click(function(){
    	$('input[name="_chk"]').prop("checked",this.checked);
    });
    $('input[name="_chk"]:checked').each(function () {
        $(this).prop("disabled", "disabled");
	});

	//去掉重复值
	Array.prototype.unique=function(){
		var o={},newArr=[],i,j;
		for( i=0;i<this.length;i++){
			if(typeof(o[this[i]])=="undefined")
			{
			    o[this[i]]="";
			}
		}
		for(j in o){
		     newArr.push(j)
		}
		return newArr;
	}

});

{/literal}
</script>