// JavaScript Document
function TMis() {
}
//构造url地址，因为list模板基本上都是基于同一个model的action ，所以model指定为当前的model
TMis.prototype.url = function (action,str) {
	var url = location.protocol + '//' + location.host + location.pathname;
	var args = location.search;
	var reg = new RegExp('[\?&](action=[a-zA-Z0-9_]+)', 'gi');
	args = args.replace(reg,"");
	if (args == '' || args == null) {
		args += '?action=' + action;
	} else if (args.substr(args.length - 1,1) == '?' || args.substr(args.length - 1,1) == '&') {
			args += 'action=' + action;
	} else {
			args += '&action=' + action;
	}
	if (str==null) return args;
	if (str.substr(0,1)=="&")	args += str;
	else args += "&" + str;	
	return args;
}

TMis.prototype.redirect = function (action,str) {
	window.location.href = this.url(action,str);
}