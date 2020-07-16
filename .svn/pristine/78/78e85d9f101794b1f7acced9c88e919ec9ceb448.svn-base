
function dump(obj){
	 /*var objPlayer = httpRequest;
	 var str = '<table border=1>';
	 for (var i in objPlayer)
	 {
	  str += '<tr><td>' + i + ' </td><td> ' + objPlayer[i] + '</td></tr>';
	  i++;
	 }
	 str += '</table>';
	 document.getElementById('spanInfo').innerHTML = str;

	var ret = '';
	for (var i in obj){
		ret += i + '=>' + obj[i] + "\n";
	}
	alert(ret);*/
	var ret = '';
	for (var i in obj){
		ret += i + '=>' + obj[i] + "\n";
	}
	try {
		document.getElementById('divDebug').innerText = ret;
	} catch(e) {
	 	alert(ret);
		//return ret;
	}
}
function trim(str) {
	return (str + '').replace(/(\s+)$/g, '').replace(/^\s+/g, '');
}
//动态载入css文件
function loadCss(file){
	var head = document.getElementsByTagName("head").item(0);
	var style = document.createElement("link");
	style.href = file;
	style.rel = "stylesheet";
	style.type = "text/css";
	head.appendChild(style);
}
// JavaScript Document
function getTop(e){
	var offset=e.offsetTop;
	if(e.offsetParent!=null) offset+=getTop(e.offsetParent);
	return offset;
}
//获取元素的横坐标
function getLeft(e){
	var offset=e.offsetLeft;
	if(e.offsetParent!=null) offset+=getLeft(e.offsetParent);
	return offset;
}
//类似php中的explode函数
function explode(separator,str) {
	return str.split(separator);
}

//得到供应商联动控件的options
function setSupplierOpts(o,type) {
	//清空o.option
	while (o.options.length>0) {o.remove(0);};
	//从ajax取得options
	var url='?controller=JiChu_Supplier&action=GetJson';
	var params={type:type};
	$.getJSON(url, params, function(json){
		//设置o的option
		for (var i=0;i<json.length;i++){
			var opt=new Option(json[i].compName,json[i].id);
			o.options.add(opt);
		}
	}) ;
	return false;
}
//得到支出项目的联动options
function setExpenseTypeOpts(o,type) {
	//清空o.option
	while (o.options.length>0) {o.remove(0);};
	//从ajax取得options
	var url='?controller=CaiWu_ExpenseItem&action=GetJson';
	var params={type:type};
	$.getJSON(url, params, function(json){
		//设置o的option
		for (var i=0;i<json.length;i++){
			var opt=new Option(json[i].itemName,json[i].id);
			o.options.add(opt);
		}
	}) ;
	return false;
}
//弹出选择客户的对话框，并在目标select控件 o 中加入选中的客户option
function popClient(o,arType) {
	//alert(o.value);
	var url='Index.php?controller=JiChu_Client&action=Popup';
	if (arType) url += '&arType=' + arType;
	var Client = showModalDialog(url);
	if (!Client) return false;
	//清空o.option
	while (o.options.length>0) {o.remove(0);};
	var opt=new Option(Client.compName,Client.clientId);
	o.options.add(opt);
}
//仓库领用出库时弹出入库批次选择的对话框，主要在染料领用时需要弹出。
//objCondition:条件对象{wareId:'23',dateFrom:''}
function popRuku(objCondition) {
	var url='Index.php?controller=CangKu_RuKu&action=popup';
	if (objCondition.wareId) url += '&wareId='+objCondition.wareId;
	var Client = showModalDialog(url);
	return Client;
}

//将控件改变成原料选择控件
function makeYlSel(obj,isArr,ufn){
	var btnName = '_btnSelYl';
	var hideName = 'ylId';
	var arr = [];
	if(obj.length||isArr) {
		btnName+='[]';
		hideName+='[]';
	}
	if(!obj.name) arr = obj;
	else arr.push(obj);
	//debugger;
	$(arr).each(function(pos){
		//debugger;
		var indexOfThis = $('input[name="'+this.name+'"]').index(this);
		function callBack(ret){
			if(typeof(ret)!='object') return false;
			if(ret===null) {
				arr[pos].value='';
				document.getElementsByName(hideName)[indexOfThis].value='';
				return false;
			}
			if(arr[pos].name.indexOf('ylCode')>-1) arr[pos].value=ret.ylCode;
			else arr[pos].value=$.trim(ret.ylName + ' ' + ret.guige);
			document.getElementsByName(hideName)[indexOfThis].value=ret.id;
			if(typeof(ufn)=='function') ufn(ret,arr[pos]);
			return false;
		}
		//设置外观
		$(this).css('width','120px');

		//增加hidden控件,如果已经存在则不增加
		var hid = $(arr[pos]).parent().children("input[name='"+hideName+"']");
		if(hid.length<1) $(arr[pos]).after('<input type="hidden" name="'+hideName+'" id="'+hideName+'"/>');

		//设置按钮的onclick
		var temp = $('<input type="button" name="'+btnName+'" id="'+btnName+'" value="..." />');
		temp.click(function(){
			var url = "?controller=Jichu_Yl&action=popup";
			ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择原料',iframe:true});
			return false;

		});

		$(arr[pos]).after(temp);

		//设置onkeydow
		$(this).keydown(function(e){
			if(e.keyCode==13) {
				//ajax获得相匹配的数据
				var url='?controller=Jichu_Yl&action=getJsonByKey';
				var param = {code:this.value};
				$.getJSON(url,param,function(json){
					if(!json||json.length==0) {
						alert('未发现匹配记录');return false;
					}
					//如果是单一记录，直接赋值
					if(json.length==1) {
						if(arr[pos].name.indexOf('ylCode')>-1) arr[pos].value=json[0].ylCode;
						else arr[pos].value=$.trim(json[0].ylName + ' ' + json[0].guige);
						document.getElementsByName(hideName)[indexOfThis].value=json[0].id;
						if(typeof(ufn)=='function') ufn(json[0],arr[pos]);
						return false;
					}

					//如果多条记录,弹出选择框
					var url = "?controller=Jichu_Yl&action=popup&key="+encodeURI($.trim(arr[pos].value));
					ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择原料',iframe:true});
					return false;
				});
				return false;
			}
			return true;
		});
	});
}
//将控件改变成成品组成选择控件
function makeproductSel(obj,isArr,ufn){
	var btnName = '_btnSelproduct';
	var hideName = 'sonId';
	var arr = [];
	if(obj.length||isArr) {
		btnName+='[]';
		hideName+='[]';
	}
	if(!obj.name) arr = obj;
	else arr.push(obj);
	//debugger;
	$(arr).each(function(pos){
		//debugger;
		var indexOfThis = $('input[name="'+this.name+'"]').index(this);
		function callBack(ret){
			if(typeof(ret)!='object') return false;
			if(ret===null) {
				arr[pos].value='';
				document.getElementsByName(hideName)[indexOfThis].value='';
				return false;
			}
			if(arr[pos].name.indexOf('proCode')>-1) arr[pos].value=ret.proCode;
			else arr[pos].value=$.trim(ret.proName + ' ' + ret.guige);
			document.getElementsByName(hideName)[indexOfThis].value=ret.id;
			if(typeof(ufn)=='function') ufn(ret,arr[pos]);
			return false;
		}
		//设置外观
		$(this).css('width','120px');

		//增加hidden控件,如果已经存在则不增加
		var hid = $(arr[pos]).parent().children("input[name='"+hideName+"']");
		if(hid.length<1) $(arr[pos]).after('<input type="hidden" name="'+hideName+'" id="'+hideName+'"/>');

		//设置按钮的onclick
		var temp = $('<input type="button" name="'+btnName+'" id="'+btnName+'" value="..." />');
		temp.click(function(){
			var url = "?controller=Jichu_Product&action=popup";
			ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择原料',iframe:true});
			return false;

		});

		$(arr[pos]).after(temp);

		//设置onkeydow
		$(this).keydown(function(e){
			if(e.keyCode==13) {
				//ajax获得相匹配的数据
				var url='?controller=Jichu_Product&action=getJsonByKey';
				var param = {code:this.value};
				$.getJSON(url,param,function(json){
					if(!json||json.length==0) {
						alert('未发现匹配记录');return false;
					}
					//如果是单一记录，直接赋值
					if(json.length==1) {
						if(arr[pos].name.indexOf('productCode')>-1) arr[pos].value=json[0].productCode;
						else arr[pos].value=$.trim(json[0].productName + ' ' + json[0].guige);
						document.getElementsByName(hideName)[indexOfThis].value=json[0].id;
						if(typeof(ufn)=='function') ufn(json[0],arr[pos]);
						return false;
					}

					//如果多条记录,弹出选择框
					var url = "?controller=Jichu_Product&action=popup&key="+encodeURI($.trim(arr[pos].value));
					ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择原料',iframe:true});
					return false;
				});
				return false;
			}
			return true;
		});
	});
}

//将控件改变成产品选择控件
function makeProSel(obj,isArr,ufn){
	var btnName = '_btnSelPro';
	var hideName = 'proId';
	var arr = [];
	if(obj.length||isArr) {
		btnName+='[]';
		hideName+='[]';
	}
	//debugger;
	if(!obj.name) arr = obj;
	else arr.push(obj);

	$(arr).each(function(pos){
		var indexOfThis = $('input[name="'+this.name+'"]').index(this);
		function callBack(ret){
			if(typeof(ret)!='object') return false;

			if(ret===null) {
				arr[pos].value='';
				document.getElementsByName(hideName)[indexOfThis].value='';
				return false;
			}
			//debugger;
			if(arr[pos].name.indexOf('proCode')>-1) arr[pos].value=ret.proCode;
			else arr[pos].value=$.trim(ret.proName + ' ' + ret.guige);
			document.getElementsByName(hideName)[indexOfThis].value=ret.id;
			if(typeof(ufn)=='function') ufn(ret,arr[pos]);
			return false;
		}
		//设置外观
		$(this).css('width','60px');

		//增加hidden控件,如果已经存在则不增加
		var hid = $(arr[pos]).parent().children("input[name='"+hideName+"']");
		//var hid1 = $(arr[pos]).parent().children("input[name='"+hideName+"']");
		if(hid.length<1) $(arr[pos]).after('<input type="hidden" name="'+hideName+'" id="'+hideName+'"/>');
		//else hid[0][0].type='text';

		//设置按钮的onclick
		var temp = $('<input type="button" name="'+btnName+'" id="'+btnName+'" value="..." />');
		temp.click(function(){
			var url = "?controller=Jichu_Product&action=popup";
			ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择原料',iframe:true});
			return false;
		});
		$(arr[pos]).after(temp);


		//设置onkeydow
		$(this).keydown(function(e){
			if(e.keyCode==13) {
				//ajax获得相匹配的数据
				var url='?controller=Jichu_Product&action=getJsonByKey';
				var param = {code:this.value};
				$.getJSON(url,param,function(json){
					if(!json||json.length==0) {
						alert('未发现匹配记录');return false;
					}
					//如果是单一记录，直接赋值
					if(json.length==1) {
						if(arr[pos].name.indexOf('proCode')>-1) arr[pos].value=json[0].proCode;
						else arr[pos].value=$.trim(json[0].proName + ' ' + json[0].guige);
						document.getElementsByName(hideName)[indexOfThis].value=json[0].id;
						if(typeof(ufn)=='function') ufn(json[0],arr[pos]);
						return false;
					}

					//如果多条记录,弹出选择框
					var url = "?controller=Jichu_Product&action=popup&key="+encodeURI($.trim(arr[pos].value));
					ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择产品',iframe:true});
					return false;
				});
				return false;
			}
			return true;
		});
	});
}

//将控件改变成产品选择控件
function makeClientSel(obj,isArr){
	var btnName = '_btnSelClient';
	var hideName = 'clientId';
	var arr = [];
	if(obj.length||isArr) {
		btnName+='[]';
		hideName+='[]';
	}
	if(!obj.name) arr = obj;
	else arr.push(obj);

	$(arr).each(function(pos){
		var indexOfThis = $('input[name="'+this.name+'"]').index(this);
		function callBack(ret){
			if(typeof(ret)!='object') return false;
			if(ret===null) {
				arr[pos].value='';
				document.getElementsByName(hideName)[indexOfThis].value='';
				return false;
			}
			arr[pos].value=$.trim(ret.compName);
			document.getElementsByName(hideName)[indexOfThis].value=ret.id;
			return false;
		}
		//设置外观
		$(this).css('width','120px');

		//增加hidden控件,如果已经存在则不增加
		var hid = $(arr[pos]).parent().children("input[name='"+hideName+"']");
		//var hid1 = $(arr[pos]).parent().children("input[name='"+hideName+"']");
		if(hid.length<1) $(arr[pos]).after('<input type="hidden" name="'+hideName+'" id="'+hideName+'"/>');

		//设置按钮的onclick
		var temp = $('<input type="button" name="'+btnName+'" id="'+btnName+'" value="..." />');
		temp.click(function(){
			var url = "?controller=Jichu_Client&action=popup";
			ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择客户',iframe:true});
			return false;
		});
		$(arr[pos]).after(temp);


		//设置onkeydow
		$(this).keydown(function(e){
			if(e.keyCode==13) {
				//ajax获得相匹配的数据
				var url='?controller=Jichu_Client&action=getJsonByKey';
				var param = {code:this.value};
				$.getJSON(url,param,function(json){
					if(!json||json.length==0) {
						alert('未发现匹配记录');return false;
					}
					//如果是单一记录，直接赋值
					if(json.length==1) {
						arr[pos].value=$.trim(json[0].compName);
						document.getElementsByName(hideName)[indexOfThis].value=json[0].id;
						return false;
					}

					//如果多条记录,弹出选择框
					var url = "?controller=Jichu_Client&action=popup&key="+encodeURI($.trim(arr[pos].value));
					ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择客户',iframe:true});
					return false;
				});
				return false;
			}
			//return true;
		});
	});
}

//将控件改变成产品选择控件
function makeSupplierSel(obj,isArr){
	var btnName = '_btnSelSupplier';
	var hideName = 'supplierId';
	var arr = [];
	if(obj.length||isArr) {
		btnName+='[]';
		hideName+='[]';
	}
	if(!obj.name) arr = obj;
	else arr.push(obj);

	$(arr).each(function(pos){
		var indexOfThis = $('input[name="'+this.name+'"]').index(this);
		function callBack(ret){
			if(typeof(ret)!='object') return false;
			if(ret===null) {
				arr[pos].value='';
				document.getElementsByName(hideName)[indexOfThis].value='';
				return false;
			}
			arr[pos].value=$.trim(ret.compName);
			document.getElementsByName(hideName)[indexOfThis].value=ret.id;
			return false;
		}
		//设置外观
		$(this).css('width','120px');

		//增加hidden控件,如果已经存在则不增加
		var hid = $(arr[pos]).parent().children("input[name='"+hideName+"']");
		//var hid1 = $(arr[pos]).parent().children("input[name='"+hideName+"']");
		if(hid.length<1) $(arr[pos]).after('<input type="hidden" name="'+hideName+'" id="'+hideName+'"/>');

		//设置按钮的onclick
		var temp = $('<input type="button" name="'+btnName+'" id="'+btnName+'" value="..." />');
		temp.click(function(){
			var url = "?controller=Jichu_Supplier&action=popup";
			ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择供应商',iframe:true});
			return false;
		});
		$(arr[pos]).after(temp);


		//设置onkeydow
		$(this).keydown(function(e){
			if(e.keyCode==13) {
				//ajax获得相匹配的数据
				var url='?controller=Jichu_Supplier&action=getJsonByKey';
				var param = {code:this.value};
				$.getJSON(url,param,function(json){
					if(!json||json.length==0) {
						alert('未发现匹配记录');return false;
					}
					//如果是单一记录，直接赋值
					if(json.length==1) {
						arr[pos].value=$.trim(json[0].compName);
						document.getElementsByName(hideName)[indexOfThis].value=json[0].id;
						return false;
					}

					//如果多条记录,弹出选择框
					var url = "?controller=Jichu_Supplier&action=popup&key="+encodeURI($.trim(arr[pos].value));
					ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择客户',iframe:true});
					return false;
				});
				return false;
			}
			return true;
		});
	});
}

//生成选择订单控件
function makeOrderSel(obj,isArr,ufn){
	var btnName = '_btnSelOrder';
	var hideName = 'orderId';
	var arr = [];
	if(obj.length||isArr) {
		btnName+='[]';
		hideName+='[]';
	}
	if(!obj.name) arr = obj;
	else arr.push(obj);

	$(arr).each(function(pos){
		//debugger;
		var indexOfThis = $('input[name="'+this.name+'"]').index(this);
		function callBack(ret){
			if(typeof(ret)!='object') return false;
			if(ret===null) {
				arr[pos].value='';
				document.getElementsByName(hideName)[indexOfThis].value='';
				return false;
			}
			arr[pos].value=$.trim(ret.orderCode);
			document.getElementsByName(hideName)[indexOfThis].value=ret.orderId;
			ufn(ret);
			return false;
		}
		//设置外观
		$(this).css('width','120px');

		//增加hidden控件,如果已经存在则不增加
		var hid = $(arr[pos]).parent().children("input[name='"+hideName+"']");
		if(hid.length<1) $(arr[pos]).after('<input type="hidden" name="'+hideName+'" id="'+hideName+'"/>');

		//设置按钮的onclick
		var temp = $('<input type="button" name="'+btnName+'" id="'+btnName+'" value="..." />');
		temp.click(function(){
			var url = "?controller=Trade_Order&action=popup";
			ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择订单',iframe:true});
			return false;

		});

		$(arr[pos]).after(temp);

		//设置onkeydow
		$(this).keydown(function(e){
			if(e.keyCode==13) {
				//ajax获得相匹配的数据
				var url='?controller=Trade_Order&action=getJsonByKey';
				var param = {code:this.value};
				$.getJSON(url,param,function(json){
					if(!json||json.length==0) {
						alert('未发现匹配记录');return false;
					}
					//如果是单一记录，直接赋值
					if(json.length==1) {
						arr[pos].value=$.trim(json[0].orderCode);
						document.getElementsByName(hideName)[indexOfThis].value=json[0].id;
						return false;
					}

					//如果多条记录,弹出选择框
					var url = "?controller=Trade_Order&action=popup&key="+encodeURI($.trim(arr[pos].value));
					ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择订单',iframe:true});
					return false;
				});
				return false;
			}
			return true;
		});
	});
}

//生成选择采购计划控件
function makeCaigouSel(obj,isArr,ufn){
	var btnName = '_btnSelCaigou';
	var hideName = 'planId';
	var arr = [];
	if(obj.length||isArr) {
		btnName+='[]';
		hideName+='[]';
	}
	if(!obj.name) arr = obj;
	else arr.push(obj);

	$(arr).each(function(pos){
		//debugger;
		var indexOfThis = $('input[name="'+this.name+'"]').index(this);
		function callBack(ret){
			if(typeof(ret)!='object') return false;
			if(ret===null) {
				arr[pos].value='';
				document.getElementsByName(hideName)[indexOfThis].value='';
				return false;
			}
			arr[pos].value=$.trim(ret.orderCode);
			document.getElementsByName(hideName)[indexOfThis].value=ret.id;
			ufn(ret);
			return false;
		}
		//设置外观
		$(this).css('width','120px');

		//增加hidden控件,如果已经存在则不增加
		var hid = $(arr[pos]).parent().children("input[name='"+hideName+"']");
		if(hid.length<1) $(arr[pos]).after('<input type="hidden" name="'+hideName+'" id="'+hideName+'"/>');

		//设置按钮的onclick
		var temp = $('<input type="button" name="'+btnName+'" id="'+btnName+'" value="..." />');
		temp.click(function(){
			var url = "?controller=Caigou_Plan&action=popup";
			ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择采购计划',iframe:true});
			return false;

		});

		$(arr[pos]).after(temp);

		//设置onkeydow
		$(this).keydown(function(e){
			if(e.keyCode==13) {
				//ajax获得相匹配的数据
				var url='?controller=Caigou_Plan&action=getJsonByKey';
				var param = {code:this.value};
				$.getJSON(url,param,function(json){
					if(!json||json.length==0) {
						alert('未发现匹配记录');return false;
					}
					//如果是单一记录，直接赋值
					if(json.length==1) {
						arr[pos].value=$.trim(json[0].orderCode);
						document.getElementsByName(hideName)[indexOfThis].value=json[0].id;
						return false;
					}

					//如果多条记录,弹出选择框
					var url = "?controller=Caigou_Plan&action=popup&key="+encodeURI($.trim(arr[pos].value));
					ymPrompt.win({message:url,handler:callBack,width:700,height:500,title:'选择采购计划',iframe:true});
					return false;
				});
				return false;
			}
			return true;
		});
	});
}

//将文本输入始终显示为大写
function makeUpper(obj){
	obj.style.textTransform = 'uppercase';
}

//将表单中所有emptyText属性的 text控件，进行渲染，同时在表单提交前处理其值。
function renderForm(f) {
	//debugger;
	var name = f.name;
	//渲染所有有emptyText属性的输入框格式
	$('#'+name+' :input').each(function(i){
		if(this.emptyText) {
			//alert(this.value);
			this.title=this.emptyText;
			this.onfocus=function(){
				if(this.value==this.emptyText) {
					this.value='';
					this.style.color='';
				}
			}
			this.onblur=function(){
				if(this.value=='') {
					this.value=this.emptyText;
					this.style.color='#ccc';
				}
			}
		}
		if(this.emptyText && this.value=='') {
			this.style.color='#ccc';
			this.value=this.emptyText;
		}
	});
	$(f).submit(function(){
		//移除表单下所有 type='text'的控件的emptyText值
		$('#'+name+' input[type=text]').each(function(i){
			if(this.value==this.emptyText) this.value='';
			//alert(this.value);
		});
	});
}

//回车键切换为cab键
function ret2cab(){
	document.onkeydown=function(e){
		var ev = document.all ? window.event : e;
		if(ev.keyCode!=13&&ev.keyCode!=37&&ev.keyCode!=38&&ev.keyCode!=39&&ev.keyCode!=40) return true;
		var target = document.all ? ev.srcElement : ev.target;
		//dump(target.type);return false;
		//如果回车,cab
		if(ev.keyCode==13 && target.type!='button' && target.type!='submit' && target.type!='reset' && target.type!='textarea' && target.type!='')  {
			if (document.all) ev.keyCode=9;
			else return false;
		}
	}
}

function  DateDiff(sDate1,  sDate2){    //sDate1和sDate2是2006-12-18格式
	 var  aDate,  oDate1,  oDate2,  iDays
	 aDate  =  sDate1.split("-")
	 oDate1  =  new  Date(aDate[1]  +  '/'  +  aDate[2]  +  '/'  +  aDate[0])    //转换为12-18-2006格式
	 aDate  =  sDate2.split("-")
	 oDate2  =  new  Date(aDate[1]  +  '/'  +  aDate[2]  +  '/'  +  aDate[0])
	 iDays  =  parseInt(Math.abs(oDate1  -  oDate2)  /  1000  /  60  /  60  /24)    //把相差的毫秒数转换为天数
	 return  iDays
 }