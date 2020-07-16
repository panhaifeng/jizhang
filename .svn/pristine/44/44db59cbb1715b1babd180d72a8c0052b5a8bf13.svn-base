
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

//类似php中的explode函数
function explode(separator,str) {
	return str.split(separator);
}

//将控件改变成客户选择控件
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
//动态载入css文件
function loadCss(file){
	var head = document.getElementsByTagName("head").item(0);
	var style = document.createElement("link");
	style.href = file;
	style.rel = "stylesheet";
	style.type = "text/css";
	head.appendChild(style);
}
//将文本输入始终显示为大写
function makeUpper(obj){
	obj.style.textTransform = 'uppercase';
}

//将表单中所有emptyText属性的 text控件，进行渲染，同时在表单提交前处理其值。
function renderForm(f) {
	//debugger;
	if(!f) return false;
	var name = f.name;
	//渲染所有有emptyText属性的输入框格式
	$('#'+name+' :input').each(function(i){
		var text = $(this).attr('emptyText');
		if(text) {
			//alert(this.value);
			this.title=text;
			this.onfocus=function(){
				if(this.value==text) {
					this.value='';
					this.style.color='';
				}
			}
			this.onblur=function(){
				if(this.value=='') {
					this.value=text;
					this.style.color='#ccc';
				}
			}
		}
		if(text && this.value=='') {
			this.style.color='#ccc';
			this.value=text;
		}
	});
	$(f).submit(function(){
		//移除表单下所有 type='text'的控件的emptyText值
		$('#'+name+' input[type=text]').each(function(i){
			if(this.value==$(this).attr('emptyText')) this.value='';
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