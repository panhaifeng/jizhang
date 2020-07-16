/*
tip控件需用的函数,使用时需要定义title属性和onmouseOver,onmouseout事件。
后期可以考虑再css中定义onmouseover和onmouseout事件，但是比较耗浏览器资源，而且不能兼容ff.
*/
function tipOver(obj,e) {
	if (!obj.oTitle&&obj.title=='') return;
	var div=$("#tipbox");
	if (div.length==0){ 
		div=$('<div id="tipbox"></div>').appendTo('body');
		//debugger;
		//div.css('width','300px');
		//div.css('offsetWidth','4000');
		div.css('position','absolute');
		div.css('visibility','hidden');
		div.css('background','#FFFFCC');
		div.css('border','1px solid #FF9900');
		div.css('padding','3px');
	}
	
	div.css("visibility","visible");
	div.css("top",e.pageY||e.y+15);
	div.css("left",e.pageX||e.x);
	
	if(obj.title){obj.oTitle=obj.title; obj.title="";} 
	div.html(obj.oTitle);
}
function tipOut(obj) {
	var div=$("#tipbox");
	//obj.title=obj.oTitle;
	div.css("visibility","hidden");
}
