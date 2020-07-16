// JavaScript Document




function calValue() {
	//var formName = formName;
	document.getElementById('SpanMoney').innerText=(Math.round(form1.cnt.value*form1.danJia.value*100))/100;
	return false;
}

String.prototype.getParam = function(name) //Get value from arguments by name
  {
    var reg = new RegExp("(^|;|\\s)"+name+"\\s*(=|:)\\s*(\"|\')([^\\3;]*)\\3(\\s|;|$)", "i");
    var r = this.match(reg); if (r!=null) return r[4]; return "";
  };
  
  
function PopUpMaterial(url) {	

	var rooturl=url;
	if (window.event.keyCode==13||window.event.keyCode==0) {		
		var src=rooturl+"&mCode="+$("mCode").value+"&mName="+$("mName").value;	
		var JsonObj=showModalDialog(src,window,'dialogWidth:600px;dialogHeight:500px;center: yes;help:no;resizable:no;status:no');		
		return false;	
	}
	return true;
}

function PopUpPro(url) {	

	var rooturl=url;
	if (window.event.keyCode==13||window.event.keyCode==0) {		
		var src=rooturl+"&name="+$("name").value;	
		var JsonObj=showModalDialog(src,window,'dialogWidth:600px;dialogHeight:500px;center: yes;help:no;resizable:no;status:no');		
		return false;	
	}
	return true;
}


  
function SetVarsMaterial() {	
//alert(window.dialogArguments.location.href); 
	var ParentWindow=window.dialogArguments;
	var str=arguments[0];	//取得传递参数对象
	var ArrVar=new Array("materialId","mCode","mName","danWei");
	var len=ArrVar.length;
	for (i=0;i<len;i++) {
		ctrVar=ParentWindow.document.getElementById(ArrVar[i]);
		if (ctrVar!=null) ctrVar.value=str.getParam(ArrVar[i]);
	}
	//window.returnValue={danWei:str.getParam("danWei")};
	window.close();
}

function SetVarsPro() {	
//alert(window.dialogArguments.location.href); 
	var ParentWindow=window.dialogArguments;
	var str=arguments[0];	//取得传递参数对象
	var ArrVar=new Array("productId","name","guige","material","standardCode");
	var len=ArrVar.length;
	for (i=0;i<len;i++) {
		ctrVar=ParentWindow.document.getElementById(ArrVar[i]);
		if (ctrVar!=null) ctrVar.value=str.getParam(ArrVar[i]);
	}
	//window.returnValue={danWei:str.getParam("danWei")};
	window.close();
}

function SetVarsRuku() {	
//alert(window.dialogArguments.location.href); 
	var ParentWindow=window.dialogArguments;
	var str=arguments[0];	//取得传递参数对象
	var ArrVar=new Array("ruKuId", "ruKuNum", "totalMoney", "yiFuKuan");
	var len=ArrVar.length;
	for (i=0;i<len;i++) {
		ctrVar=ParentWindow.document.getElementById(ArrVar[i]);
		if (ctrVar!=null) ctrVar.value=str.getParam(ArrVar[i]);
	}
	//window.returnValue={danWei:str.getParam("danWei")};
	window.close();
}
function SetVarsChuku() {	
//alert(window.dialogArguments.location.href); 
	var ParentWindow=window.dialogArguments;
	var str=arguments[0];	//取得传递参数对象
	var ArrVar=new Array("chuKuId", "chuKuNum", "totalMoney", "yiShouKuan");
	var len=ArrVar.length;
	for (i=0;i<len;i++) {
		ctrVar=ParentWindow.document.getElementById(ArrVar[i]);
		if (ctrVar!=null) ctrVar.value=str.getParam(ArrVar[i]);
	}
	//window.returnValue={danWei:str.getParam("danWei")};
	window.close();
}


