$(function(){
	//获取input的宽度，然后下拉框自适应宽度
	//获取所在控件的最外面div
	setCombobox();
	$(window).bind('resize',function(event){
		setCombobox();
	});

	//显示下拉框
	$('.jeffCombobox li').click(function(){
		var p = $(this).parents('.input-group');
		$('input',p).val($(this).attr('v'));
	});
});
/**
 * 设置combobox宽度
 * Time：2014/06/17 11:57:41
 * @author li
*/
function setCombobox(){
	//界面中可能存在多个使用自动控件
	var boxwidth=new Array();
	//遍历所有控件，改变样式
	$('.jeffCombobox').each(function(){
		var group = $(this).parents('.input-group');
		var input = $('.form-control',group).attr('name');//控件名字作为数组的键值，一个名称对应一个长度
		// alert(input);
		if(!boxwidth[input]){
			var inputWidth = $('.form-control',group).width();
			var widthCbox = parseInt(inputWidth)||0;
			boxwidth[input]=widthCbox + 50;
		}
		$(this).css({'width':boxwidth[input]+'px','min-width':'100px','max-height':'200px','overflow':'auto'});
	});
}