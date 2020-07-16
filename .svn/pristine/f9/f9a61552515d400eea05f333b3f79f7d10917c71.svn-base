/*
*通过小键盘改变方向
*适用：上下（相同控件名），左右，根据定义name字符串的顺序
*需要添加jquery.js
*/
//初始化该方法名即可适用
//定义上下左右与回车键是否允许可用，回车默认情况下不可用，回车为Tab功能
//字符串name定义规则:"name1,name2,name3,name4……"
//name必填，其他的可为空

/*
*参数：name,回车，上，下，左，右(true:启用按键,false:关闭按键),默认支持小键盘，不支持回车键，当参数center为true时，支持回车键，小键盘一样
*回车键 center 的参数有：false=不启用，left：向左,right：向右，top：向上：,under：向下；参数为空则定义为false
**/

function SetDirection(name,center,top,under,left,right){
	//初始化支持的键盘
	if(center==undefined)
	center=false;
	if(top==undefined)
	top=true;
	if(under==undefined)
	under=true;
	if(left==undefined)
	left=true;
	if(right==undefined)
	right=true;

	//判断字符串(名字)是否为空
	if(name=='')return false;
	//把字符串改变为数组
	var nameArr=new Array();
	nameArr=name.split(',');
	//判断数组是否为空
	if(nameArr==null)return false;

	//把字符串改为'input[name="name1"],input[name="name2"]……'形式
	var css=setName(nameArr);
	//alert(css);
	$(css).keydown(function(event){
			var ev = document.all ? window.event : event;
			//定义回车键
			if(ev.keyCode==13){//回车
				//如果回车定义为false,则不允许改变回车键功能
				if(center==false)return false;
				//回车改变为Tab键功能
				if(center=='right'){
					ev.keyCode=39;
				}else if(center=='left'){
					ev.keyCode=37;
				}else if(center=='top'){
					ev.keyCode=38;
				}else if(center=='under'){
					ev.keyCode=40;
				}
			}
			//定义上下左右的方向键
			if(ev.keyCode==38){//上
				if(top==false)return false;

				//获得当前行数
				var row_index=getIndexInRow(this.name,this);
				//第一行则不移动
				if(row_index==0)return false;
				var o=document.getElementsByName(this.name)[row_index-1];
				o.focus();
				return false;
			}
			else if(ev.keyCode==40){//下
				if(under==false)return false;

				//获得当前行数
				var row_index=getIndexInRow(this.name,this);
				//最后一行则不移动
				var o=document.getElementsByName(this.name);
				if(row_index==o.length-1)return false;
				var t=o[row_index+1];
				t.focus();
				return false;
			}
			else if(ev.keyCode==37){//左
				if(left==false)return false;

				//获得name在数组中序号
				var arr_index=getIndexInArr(nameArr,this.name);
				if(arr_index==0)return false;
				var name_left=nameArr[arr_index-1];
				//获得当前行数
				var row_index=getIndexInRow(this.name,this);
				var o=document.getElementsByName(name_left)[row_index];
				o.focus();
				return false;
			}
			else if(ev.keyCode==39){//右
				if(right==false)return false;
				
				//获得name在数组中序号
				var arr_index=getIndexInArr(nameArr,this.name);
				if(arr_index==nameArr.length-1)return false;
				var name_right=nameArr[arr_index+1];
				//获得当前行数
				var row_index=getIndexInRow(this.name,this);
				var o=document.getElementsByName(name_right)[row_index];
				o.focus();
				return false;
				//return true;
			}
			
	});
}
//整理名字字符串为数组
function setName(arr){
	//定义新数组，保存处理后的数据
	var css_name_arr=new Array();
	
	for(var i=0;i<arr.length;i++){
		//查找控件类型
		var type=getType(arr[i]);
		css_name_arr[i]=type+'[name="'+arr[i]+'"]';
	}
	if(css_name_arr==null)return '';
	var css_name_str=css_name_arr.join(',');
	return css_name_str;
}

//获得当前name位于数组的序号
function getIndexInArr(arr,name){
	for(var i=0;i<arr.length;i++){
		if(arr[i]==name){
			return i;
		}
	}
}

//获得当前name的行数
function getIndexInRow(name,obj){
	var index=0;
	//查找控件类型
	var type=getType(name);
	index=$(type+'[name="'+name+'"]').index(obj);
	return index;
}

//查找控件的类型
function getType(name){
	//查找控件类型
	var o=document.getElementsByName(name)[0];
	var type=o.type;
	if(type=='text')type='input';
	else if(type=='select-one')type='select';
	else if(type=='textarea')type='textarea';
	else type='input';
	return type;
}