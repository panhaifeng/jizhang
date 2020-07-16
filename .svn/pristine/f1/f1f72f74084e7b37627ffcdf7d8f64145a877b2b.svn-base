var tabPath = 'Resource/Script/tab';
var currentTab = $('#tabbar-div').children()[0];
var currentIframe = $('#content-div').children()[0];

document.getElementById("tabbar-div").onmouseover =function (e) {	
    var obj = (document.all) ? window.event.srcElement : e.target;
    if (obj.className == "tab-back") {
        obj.className = "tab-hover";
    }
}
document.getElementById("tabbar-div").onmouseout =function (e) {	
    var obj = document.all ? window.event.srcElement : e.target;
    if (obj.className == "tab-hover") {
        obj.className = "tab-back";
    }
}
document.getElementById("tabbar-div").onclick =function (e) {
    var obj = document.all ? window.event.srcElement : e.target;
	showTab(obj);
}


function appendTab(id,title,href){
	var t = $('<span class="tab-back" id="_tab'+id+'">'+title+'<span style="color:#F00; margin-left:5px;" onclick="removeTab(this.parentNode)"><sup style=" font-family:Arial, Helvetica, sans-serif;">x</sup></span></span>').appendTo('#tabbar-div');

	//var div = $('<div style=" border:1px solid #000;display:block"></div>');
	var t1 = $('<div><iframe id="_frm'+id+'" class="ui-layout-center" width="100%" height="600" frameborder="0" scrolling="auto" src=""></iframe></div>').appendTo('#content-div');	
	showTab(t[0]);
	t1.children()[0].src=href;
}

function removeTab(tab) {
	//如果只有一个tab，返回
	if($('#tabbar-div').children().length==1) return false;
	var re=false;//是否重新指定初始的tab;
	if(currentTab==tab) {
		var re = true;
	}
	var pos = getPos(tab);
	$(tab).remove();
	var iframe = $('#content-div').children()[pos];
	$(iframe).remove();
	if(re) {
		//currentTab = ;
		showTab($('#tabbar-div').children()[0]);
	}
}

function showTab(tab) {
	if(tab==currentTab) return false;
	var pos = getPos(tab);
	if(pos==-1) return false;
	
	currentTab.className = "tab-back";
	tab.className = "tab-front";
	currentTab = tab;	
	
	$(currentIframe).hide();
	currentIframe = $('#content-div').children()[pos];
	
	$(currentIframe).show();
}
function getPos(tab) {
	var pos =-1;
	//debugger;
	for(var i=0;$('#tabbar-div').children()[i];i++) {
		if ($('#tabbar-div').children()[i]==tab) {
			return i;
		}
	}
	return pos;
}