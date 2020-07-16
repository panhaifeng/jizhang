/*var TmisGrid = function () {
}
var TmisGrid.edit = function (obj,fldName,value) {
	alert(obj.id);
}
alert('asdf');
*/
function gridEdit(obj,fieldName,pkv) {
	//alert(obj.innerHTML+fieldName+value);
	var tag = obj.firstChild.tagName;
	if (typeof(tag) != "undefined" && tag.toLowerCase() == "input"){
		return;
	}
	var oValue = obj.innerHTML;
	var text = document.createElement('input');
	text.style.width = (obj.offsetWidth + 15) + "px" ;
	text.style.margin = "0px" ;
	text.style.padding = "0px" ;
	text.style.height = "15px" ;
	//text.style.borderWidth = "1px" ;
	text.value=oValue;
	obj.innerHTML='';
	obj.appendChild(text);
	text.select();
	text.onblur = function() {
		var controller = $.query.get('controller');
		var value = obj.firstChild.value;
		if (value.length==0||value==oValue){
			obj.innerHTML = oValue;
			return false;
		}
		var url = "";
		var param = {
			controller:controller,
			action : 'AjaxEdit',
			fieldName: fieldName,
			value : value,
			id:pkv
		}
		$.getJSON(url,param,function(json){
			debugger;
			if (json.success) obj.innerHTML = text.value;
			else {
				obj.innerHTML = oValue;
				alert(json.msg);
			}

		});
	};
}